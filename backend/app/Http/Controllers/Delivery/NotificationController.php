<?php
namespace App\Http\Controllers\Delivery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/** Notificaciones privadas para las tres clases de usuario, por sesión. */
class NotificationController extends Controller {
    public function index(Request $request) {
        return view('shared.notifications', [
            'notices' => $request->user()->notifications()->latest()->paginate(15),
        ]);
    }
    public function read(Request $request,string $id) {
        $notice=$request->user()->notifications()->whereKey($id)->firstOrFail();
        $notice->markAsRead();
        $link=(string)($notice->data['url']??route('notifications.index'));
        // El aviso jamás puede provocar una redirección externa, incluso si es antiguo.
        $parts=parse_url($link);
        $path=$parts['path'] ?? '';
        if (!is_string($path) || !str_starts_with($path,'/') || str_starts_with($path,'//')) {
            return redirect()->route('notifications.index');
        }
        $query=isset($parts['query']) ? '?'.$parts['query'] : '';
        return redirect($path.$query);
    }
}
