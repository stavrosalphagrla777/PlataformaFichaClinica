<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /** HU-13 - Listado con búsqueda y filtros */
    public function index(Request $request)
    {
        $request->validate([
            'rol' => ['nullable', Rule::in(User::ROLES)],
            'nivel_educativo' => ['nullable', Rule::in(User::NIVELES)],
            'activo' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = User::query()->with('roles');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($rol = $request->query('rol')) {
            $query->role($rol);
        }
        if ($nivel = $request->query('nivel_educativo')) {
            $query->where('nivel_educativo', $nivel);
        }
        if ($request->filled('curso')) {
            $query->where('curso', $request->query('curso'));
        }
        if ($request->has('activo') && $request->query('activo') !== '') {
            $query->where('activo', $request->boolean('activo'));
        }

        return UserResource::collection(
            $query->orderBy('name')->paginate($request->integer('per_page', 15))
        );
    }

    /** HU-11 - Crear usuario (con rol y nivel: HU-12) */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
            $this->aplicarRolYNivel($user, $data);

            return $user;
        });

        return (new UserResource($user->load('roles')))
            ->response()->setStatusCode(201);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user->load('roles'));
    }

    /** HU-11 - Modificar usuario */
    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        $data = $request->validated();

        DB::transaction(function () use ($user, $data) {
            $user->fill(collect($data)->only(['name', 'email'])->all());
            if (! empty($data['password'])) {
                $user->password = $data['password'];
            }
            $user->save();

            $this->aplicarRolYNivel($user, $data);
        });

        return new UserResource($user->fresh()->load('roles'));
    }

    /** HU-12 - Asignar rol y nivel educativo */
    public function asignarRolNivel(Request $request, User $user): UserResource
    {
        $data = $request->validate([
            'rol' => ['required', Rule::in(User::ROLES)],
            'nivel_educativo' => ['nullable', Rule::in(User::NIVELES)],
            'curso' => ['nullable', 'string', 'max:20'],
        ]);

        DB::transaction(fn () => $this->aplicarRolYNivel($user, $data));

        return new UserResource($user->fresh()->load('roles'));
    }

    /** HU-11 - Desactivar: bloquea el login y cierra sus sesiones */
    public function desactivar(Request $request, User $user): UserResource
    {
        if ($request->user()->is($user)) {
            throw ValidationException::withMessages([
                'user' => ['No puedes desactivar tu propia cuenta.'],
            ]);
        }

        $user->update(['activo' => false]);
        $user->tokens()->delete();

        return new UserResource($user->load('roles'));
    }

    public function activar(User $user): UserResource
    {
        $user->update(['activo' => true]);

        return new UserResource($user->load('roles'));
    }

    /**
     * Regla de negocio: el estudiante exige nivel (3º/4º medio) y curso;
     * docentes y administradores no tienen nivel ni curso.
     */
    private function aplicarRolYNivel(User $user, array $data): void
    {
        $user->loadMissing('roles');
        $rol = $data['rol'] ?? $user->roles->first()?->name;

        // Evita que el admin se quite a sí mismo el acceso
        if (isset($data['rol']) && auth()->id() === $user->id && $data['rol'] !== User::ROL_ADMIN) {
            throw ValidationException::withMessages([
                'rol' => ['No puedes quitarte el rol de administrador a ti mismo.'],
            ]);
        }

        if ($rol === User::ROL_ESTUDIANTE) {
            $nivel = array_key_exists('nivel_educativo', $data) ? $data['nivel_educativo'] : $user->nivel_educativo;
            $curso = array_key_exists('curso', $data) ? $data['curso'] : $user->curso;

            if (! $nivel || ! $curso) {
                throw ValidationException::withMessages([
                    'nivel_educativo' => ['Los estudiantes requieren nivel educativo y curso.'],
                ]);
            }
            $user->nivel_educativo = $nivel;
            $user->curso = $curso;
        } else {
            $user->nivel_educativo = null;
            $user->curso = null;
        }

        $user->save();

        if (isset($data['rol'])) {
            $user->syncRoles([$data['rol']]);
        }
    }
}

