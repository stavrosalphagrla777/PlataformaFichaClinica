<?php
namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClinicalCase;
use App\Models\StudentCase;
use App\Models\CaseEvaluation;
use App\Models\AuditEvent;
use App\Support\CourseAccess;
use App\Notifications\ClinicalAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

/**
 * Panel administrativo web: usuarios, roles, niveles, activación, importación CSV y bitácora.
 * Acceso protegido por rutas auth + role:administrador. Auditoría de usuarios mediante audit_events.
 */
class AdminPanelController extends Controller
{
    /** Muestra indicadores agregados y usuarios recientes. */
    public function dashboard()
    {
        return view('admin.operativo.dashboard', [
            'counts' => ['usuarios'=>User::count(), 'estudiantes'=>User::role('estudiante')->count(),
                'docentes'=>User::role('docente')->count(), 'administradores'=>User::role('administrador')->count(),
                'casos'=>ClinicalCase::count(), 'fichas'=>StudentCase::count(), 'evaluadas'=>CaseEvaluation::where('estado','finalizada')->count()],
            'recent' => User::with('roles')->latest()->take(10)->get(),
        ]);
    }
    /** Lista cuentas con filtros combinados y paginación. */
    public function users(Request $request)
    {
        $query=User::with('roles');
        if ($request->filled('buscar')) {
            $q=trim($request->string('buscar')->toString());
            $query->where(fn($q2)=>$q2->where('name','like',"%{$q}%")->orWhere('email','like',"%{$q}%")->orWhere('username','like',"%{$q}%"));
        }
        if ($request->filled('rol') && in_array($request->input('rol'), ['administrador','docente','estudiante'], true)) $query->role($request->input('rol'));
        if ($request->filled('nivel') && in_array($request->input('nivel'), ['3_medio','4_medio'], true)) $query->where('nivel_educativo', $request->input('nivel'));
        $jornada = $request->input('jornada', 'diurno') === 'vespertino' ? 'vespertino' : 'diurno';
        $query->where(fn($q) => $q->whereDoesntHave('roles', fn($roles) => $roles->where('name', 'estudiante'))->orWhere(function($students) use ($jornada) {
            $students->whereHas('roles', fn($roles) => $roles->where('name','estudiante'));
            if ($jornada === 'vespertino') $students->whereIn('curso', ['V3ro','V4to']);
            else $students->where(fn($c)=>$c->whereNull('curso')->orWhereNotIn('curso',['V3ro','V4to']));
        }));
        $cursos = User::role('estudiante')->whereNotNull('curso')->where('curso', '<>', '')->distinct()->orderBy('curso')->pluck('curso')->filter(fn($c)=>CourseAccess::isEvening($c) === ($jornada === 'vespertino') && in_array($c, CourseAccess::allCourses(), true));
        if ($request->filled('curso') && $cursos->contains($request->input('curso'))) $query->where('curso', $request->input('curso'));
        if ($request->filled('estado')) $query->where('activo', $request->input('estado')==='activo');
        return view('admin.operativo.usuarios', ['users'=>$query->orderBy('name')->paginate(50)->withQueryString(), 'cursos'=>$cursos, 'jornada'=>$jornada, 'totales'=>['usuarios'=>User::count(), 'estudiantes'=>User::role('estudiante')->count(), 'docentes'=>User::role('docente')->count(), 'administradores'=>User::role('administrador')->count()]]);
    }
    /** Abre el formulario de creación de cuentas. */
    public function create() { return view('admin.operativo.usuario-form', ['user'=>null]); }
    /** Abre el formulario de edición de una cuenta. */
    public function edit(User $user) { return view('admin.operativo.usuario-form', compact('user')); }
    /** Valida datos, consistencia de nivel/curso y restricciones de administradores. */
    private function validateUser(Request $request, ?User $user=null): array
    {
        $data=$request->validate([
            'name'=>'required|string|max:150',
            'username'=>['required','alpha_dash','max:60',Rule::unique('users','username')->ignore($user?->id)],
            'email'=>['required','email','max:255',Rule::unique('users','email')->ignore($user?->id)],
            'password'=>[$user?'nullable':'required','string',app()->environment('production')?'min:12':'min:8'],
            'rol'=>['required',Rule::in(['administrador','docente','estudiante'])],
            'nivel_educativo'=>['nullable',Rule::in(['3_medio','4_medio'])],
            'curso'=>'nullable|string|max:50',
            'activo'=>'required|boolean',
        ]);
        if ($data['rol']==='estudiante' && (empty($data['nivel_educativo']) || empty($data['curso']))) {
            throw ValidationException::withMessages(['nivel_educativo'=>'Los estudiantes requieren nivel y curso.']);
        }
        if ($data['rol'] === 'estudiante' && !CourseAccess::validCourse((string)($data['curso']??''), ($data['nivel_educativo']??'') === '3_medio' ? '3.º medio' : '4.º medio')) {
            throw ValidationException::withMessages(['curso'=>'El curso no corresponde al nivel del estudiante.']);
        }
        if ($user && $request->user()->is($user) && ($data['rol']!=='administrador'||!$data['activo'])) {
            throw ValidationException::withMessages(['rol'=>'No puedes quitar tu propio acceso administrativo.']);
        }
        if ($data['rol']!=='estudiante') {$data['nivel_educativo']=null;$data['curso']=null;}
        return $data;
    }
    /** Registra acciones administrativas sobre un usuario en audit_events. */
    private function record(Request $request,string $action,User $user): void
    {
        AuditEvent::create(['actor_id'=>$request->user()->id,'target_id'=>$user->id,'action'=>$action,'description'=>$user->name.' · '.$user->email]);
    }
    /** Crea usuario, asigna rol y registra el alta de forma transaccional. */
    public function store(Request $request)
    {
        $data=$this->validateUser($request); $role=$data['rol'];unset($data['rol']);
        $user=DB::transaction(function()use($data,$role,$request){
            $user=User::create($data);$user->syncRoles([$role]);$this->record($request,'crear_usuario',$user);return $user;
        });
        // Aviso administrativo al resto del equipo (sin incluir contraseñas).
        User::role('administrador')->whereKeyNot($request->user()->id)->get()
            ->each(fn(User $admin) => $admin->notify(new ClinicalAlert('Nuevo usuario registrado',
                'Se registró la cuenta de '.$user->name.'.', route('admin.usuarios'))));
        return redirect()->route('admin.usuarios')->with('ok','Usuario creado: '.$user->name);
    }
    /** Actualiza perfil y rol, dejando constancia en la bitácora. */
    public function update(Request $request,User $user)
    {
        $data=$this->validateUser($request,$user);$role=$data['rol'];unset($data['rol']);
        if (empty($data['password'])) unset($data['password']);
        DB::transaction(function()use($data,$role,$user,$request){
            $user->update($data);$user->syncRoles([$role]);
            if (!$user->activo) $user->tokens()->delete();
            $this->record($request,'actualizar_usuario',$user);
        });
        return redirect()->route('admin.usuarios')->with('ok','Usuario actualizado.');
    }
    /** Activa o desactiva una cuenta con reglas de seguridad. */
    public function status(Request $request,User $user)
    {
        if ($request->user()->is($user)) return back()->withErrors(['estado'=>'No puedes desactivar tu cuenta.']);
        $user->update(['activo'=>!$user->activo]);
        if (!$user->activo) $user->tokens()->delete();
        $this->record($request,$user->activo?'activar_usuario':'desactivar_usuario',$user);
        return back()->with('ok','Estado actualizado.');
    }
    /** Lista usuarios para asignación de rol, nivel y curso. */
    public function roles()
    {
        $jornada = request('jornada') === 'vespertino' ? 'vespertino' : 'diurno';
        $users = User::with('roles')->where(function ($query) use ($jornada) {
            $query->whereDoesntHave('roles', fn($roles) => $roles->where('name', 'estudiante'))
                ->orWhere(function ($students) use ($jornada) {
                    $students->whereHas('roles', fn($roles) => $roles->where('name', 'estudiante'));
                    if ($jornada === 'vespertino') $students->whereIn('curso', ['V3ro', 'V4to']);
                    else $students->where(fn($courses) => $courses->whereNull('curso')->orWhereNotIn('curso', ['V3ro','V4to']));
                });
        })->orderBy('name')->paginate(50)->withQueryString();
        return view('admin.operativo.roles', compact('users','jornada'));
    }
    /** Evita dejar la plataforma sin ninguna cuenta administrativa activa. */
    /** Evita operaciones que dejen sin administradores autorizados. */
    private function protectAdmins(Request $request, $users, string $targetRole, bool $targetActive): void
    {
        $willLose = $users->filter(fn (User $u) => $u->activo && $u->hasRole('administrador') && (!$targetActive || $targetRole !== 'administrador'));
        if ($willLose->contains('id', $request->user()->id)) {
            throw ValidationException::withMessages(['usuarios' => 'No puedes desactivar ni quitarte tu propio rol de administrador.']);
        }
        if ($willLose->isNotEmpty()) {
            $other = User::role('administrador')->where('activo', true)->whereNotIn('id', $willLose->pluck('id'))->exists();
            if (!$other) throw ValidationException::withMessages(['usuarios' => 'Debe permanecer al menos un administrador activo.']);
        }
    }

    /** Activación o desactivación real de múltiples cuentas seleccionadas de esta página. */
    /** Activa o desactiva varias cuentas con validaciones. */
    public function bulkStatus(Request $request)
    {
        $data=$request->validate(['ids'=>'required|array|min:1|max:100', 'ids.*'=>'required|integer|distinct|exists:users,id', 'activo'=>'required|boolean']);
        DB::transaction(function()use($data,$request){
            $users=User::whereIn('id',$data['ids'])->lockForUpdate()->get();
            $active=(bool)$data['activo'];
            $this->protectAdmins($request,$users,'administrador',$active);
            foreach($users as $user){
                if ((bool)$user->activo === $active) continue;
                $user->update(['activo'=>$active]);
                if (!$active) $user->tokens()->delete();
                $this->record($request,$active?'activar_usuario':'desactivar_usuario',$user);
            }
        });
        return redirect()->route('admin.usuarios')->with('ok','Estados de usuarios actualizados.');
    }

    /** Asignación de rol, nivel y curso a uno o varios usuarios. */
    /** Actualiza roles, niveles y cursos masivamente. */
    public function bulkRoles(Request $request)
    {
        $data=$request->validate([
            'ids'=>'required|array|min:1|max:100', 'ids.*'=>'required|integer|distinct|exists:users,id',
            'rol'=>['required',Rule::in(['administrador','docente','estudiante'])],
            'nivel_educativo'=>['nullable',Rule::in(['3_medio','4_medio'])],
            'curso'=>'nullable|string|max:50',
        ]);
        if($data['rol']==='estudiante' && (empty($data['nivel_educativo'])||empty($data['curso'])))
            throw ValidationException::withMessages(['nivel_educativo'=>'Selecciona el nivel y curso de los estudiantes.']);
        if ($data['rol'] === 'estudiante' && !CourseAccess::validCourse((string)($data['curso']??''), ($data['nivel_educativo']??'') === '3_medio' ? '3.º medio' : '4.º medio')) {
            throw ValidationException::withMessages(['curso'=>'Curso y nivel educativo no corresponden.']);
        }
        DB::transaction(function()use($data,$request){
            $users=User::whereIn('id',$data['ids'])->lockForUpdate()->get();
            $this->protectAdmins($request,$users,$data['rol'],true);
            foreach($users as $user){
                $user->syncRoles([$data['rol']]);
                $user->update(['nivel_educativo'=>$data['rol']==='estudiante' ? $data['nivel_educativo'] : null,
                    'curso'=>$data['rol']==='estudiante' ? $data['curso'] : null]);
                $user->tokens()->delete();
                $this->record($request,'actualizar_roles_y_niveles',$user);
            }
        });
        return redirect()->route('admin.roles-niveles')->with('ok','Roles y niveles actualizados.');
    }

    /** Importación CSV limitada, validada y transaccional, sin sustituir cuentas existentes. */
    /** Importa usuarios CSV validado, de manera transaccional. */
    public function importUsers(Request $request)
    {
        $request->validate(['archivo'=>'required|file|max:256|mimes:csv,txt']);
        $handle=fopen($request->file('archivo')->getRealPath(),'rb');
        if(!$handle) throw ValidationException::withMessages(['archivo'=>'No se pudo leer el archivo.']);
        try {
            $first=fgets($handle);
            rewind($handle);
            $delimiter=substr_count((string)$first,';')>substr_count((string)$first,',') ? ';' : ',';
            $header=fgetcsv($handle, 0, $delimiter, '"', '');
            if(!$header) throw ValidationException::withMessages(['archivo'=>'El CSV está vacío.']);
            $header=array_map(fn($v)=>mb_strtolower(trim((string)$v)), $header);
            $header[0]=preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);
            $required=['nombre','usuario','correo','contrasena','rol','nivel','curso','activo'];
            if($header!==$required) throw ValidationException::withMessages(['archivo'=>'Cabeceras requeridas: '.implode(';',$required)]);
            $rows=[]; $line=1;
            while(($values=fgetcsv($handle, 0, $delimiter, '"', ''))!==false){
                $line++;
                if(count($values)===1 && trim((string)$values[0])==='')continue;
                if(count($values)!==8)throw ValidationException::withMessages(['archivo'=>"Fila $line: se esperan 8 columnas."]);
                if(count($rows)>=100)throw ValidationException::withMessages(['archivo'=>'Máximo de 100 usuarios por archivo.']);
                $r=array_combine($required,array_map('trim',$values));
                $r['rol']=mb_strtolower($r['rol']); $r['nivel']=mb_strtolower($r['nivel']);
                $r['activo']=mb_strtolower($r['activo']);
                $validator=Validator::make($r,[
                    'nombre'=>'required|string|max:150', 'usuario'=>['required','alpha_dash','max:60',Rule::unique('users','username')],
                    'correo'=>['required','email','max:255',Rule::unique('users','email')],
                    'contrasena'=>['required','string',app()->environment('production')?'min:12':'min:8'],
                    'rol'=>['required',Rule::in(['administrador','docente','estudiante'])],
                    'nivel'=>['nullable',Rule::in(['','3_medio','4_medio'])],
                    'curso'=>'nullable|string|max:50', 'activo'=>['required',Rule::in(['1','0'])],
                ]);
                if($validator->fails())throw ValidationException::withMessages(['archivo'=>"Fila $line: ".$validator->errors()->first()]);
                if($r['rol']==='estudiante' && (!$r['nivel'] || !$r['curso'])) throw ValidationException::withMessages(['archivo'=>"Fila $line: el estudiante requiere nivel y curso."]);
                if($r['rol']==='estudiante' && !CourseAccess::validCourse($r['curso'], $r['nivel']==='3_medio' ? '3.º medio' : '4.º medio')) throw ValidationException::withMessages(['archivo'=>"Fila $line: curso no autorizado para el nivel."]);
                $rows[]=$r;
            }
            if(!$rows)throw ValidationException::withMessages(['archivo'=>'No hay usuarios para importar.']);
        } finally { fclose($handle); }
        DB::transaction(function()use($rows,$request){
            foreach($rows as $r){
                $u=User::create(['name'=>$r['nombre'],'username'=>$r['usuario'],'email'=>$r['correo'],
                    'password'=>$r['contrasena'],'nivel_educativo'=>$r['rol']==='estudiante'?$r['nivel']:null,
                    'curso'=>$r['rol']==='estudiante'?$r['curso']:null,'activo'=>$r['activo']==='1']);
                $u->syncRoles([$r['rol']]);$this->record($request,'importar_usuario',$u);
            }
        });
        return redirect()->route('admin.usuarios')->with('ok',count($rows).' usuarios importados correctamente.');
    }

    /** Filtra y pagina 15 actividades por página; contempla responsables de cualquier rol. */
    public function history(Request $request)
    {
        $filters = $request->validate([
            'buscar' => 'nullable|string|max:120',
            'responsable' => 'nullable|integer|min:1',
            'administrador' => 'nullable|integer|min:1', // Enlaces previos compatibles
            'accion' => 'nullable|string|max:100',
            'desde' => 'nullable|date_format:Y-m-d',
            'hasta' => 'nullable|date_format:Y-m-d|after_or_equal:desde',
        ]);

        $query = AuditEvent::query()->with(['actor', 'target']);
        $search = trim((string) ($filters['buscar'] ?? ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $like = '%' . $search . '%';
                $q->where('description', 'like', $like)
                    ->orWhere('action', 'like', $like)
                    ->orWhereHas('actor', fn ($u) => $u->where('name', 'like', $like)
                        ->orWhere('username', 'like', $like)->orWhere('email', 'like', $like))
                    ->orWhereHas('target', fn ($u) => $u->where('name', 'like', $like)
                        ->orWhere('username', 'like', $like)->orWhere('email', 'like', $like));
            });
        }
        $actorId = $filters['responsable'] ?? $filters['administrador'] ?? null;
        if ($actorId) {
            $query->where('actor_id', $actorId);
        }
        if (!empty($filters['accion'])) {
            $query->where('action', $filters['accion']);
        }
        if (!empty($filters['desde'])) {
            $query->whereDate('created_at', '>=', $filters['desde']);
        }
        if (!empty($filters['hasta'])) {
            $query->whereDate('created_at', '<=', $filters['hasta']);
        }

        return view('admin.operativo.historial', [
            'events' => $query->orderByDesc('created_at')->orderByDesc('id')
                ->paginate(15)->withQueryString(),
            'acciones' => AuditEvent::query()->distinct()->orderBy('action')->pluck('action'),
            'responsables' => User::query()->whereIn('id', AuditEvent::query()
                ->whereNotNull('actor_id')->select('actor_id')->distinct())
                ->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
