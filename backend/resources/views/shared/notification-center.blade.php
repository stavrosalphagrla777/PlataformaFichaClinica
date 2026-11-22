@auth
@php
  $avisosRecientes = auth()->user()->notifications()->latest()->limit(6)->get();
  $avisosPendientes = auth()->user()->unreadNotifications()->count();
@endphp
<div class="ficha-bell" id="fichaBell" data-unread="{{ $avisosPendientes }}">
  <button type="button" class="ficha-bell-toggle" id="fichaBellToggle" aria-label="Notificaciones{{ $avisosPendientes ? ', '.$avisosPendientes.' sin leer' : '' }}" aria-expanded="false" aria-haspopup="true" aria-controls="fichaBellMenu" title="Notificaciones">
    <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M10 21h4"/></svg>
    @if($avisosPendientes > 0)<span class="ficha-bell-badge">{{ $avisosPendientes > 99 ? '99+' : $avisosPendientes }}</span>@endif
  </button>
  <section class="ficha-bell-menu" id="fichaBellMenu" aria-label="Notificaciones recientes" hidden>
    <div class="ficha-bell-heading"><strong>Notificaciones</strong><span>{{ $avisosPendientes }} sin leer</span></div>
    <div class="ficha-bell-items">
      @forelse($avisosRecientes as $aviso)
      <form class="ficha-bell-item{{ $aviso->read_at ? '' : ' is-unread' }}" method="POST" action="{{ route('notifications.read',$aviso->id) }}">
        @csrf
        <button type="submit" aria-label="Abrir: {{ $aviso->data['titulo'] ?? 'Notificación' }}">
          <strong>{{ $aviso->data['titulo'] ?? 'Notificación' }}</strong>
          <span>{{ $aviso->data['mensaje'] ?? '' }}</span>
          <small>{{ $aviso->created_at->format('d/m/Y H:i') }}{{ $aviso->read_at ? '' : ' · Nueva' }}</small>
        </button>
      </form>
      @empty<div class="ficha-bell-empty">No hay notificaciones por el momento.</div>@endforelse
    </div>
    <a class="ficha-bell-all" href="{{ route('notifications.index') }}">Ver todas las notificaciones</a>
  </section>
</div>
@endauth
