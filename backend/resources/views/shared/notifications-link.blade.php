@php
    $pendientesAvisos = auth()->user()?->unreadNotifications()->count() ?? 0;
@endphp
<a class="nav-link" href="{{ route('notifications.index') }}">Notificaciones @if($pendientesAvisos)<strong aria-label="{{ $pendientesAvisos }} notificaciones sin leer">({{ $pendientesAvisos }})</strong>@endif</a>
