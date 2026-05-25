@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Notification Bell --}}
        @auth
        <li class="nav-item dropdown">
            <a class="nav-link position-relative px-3" data-toggle="dropdown" href="#" title="Notificações">
                <i class="far fa-bell" style="font-size: 1.1rem;"></i>
                @if(($totalNotificacoes ?? 0) > 0)
                <span class="badge badge-danger navbar-badge font-weight-bold" style="font-size: 0.6rem;">
                    {{ ($totalNotificacoes ?? 0) > 9 ? '9+' : $totalNotificacoes }}
                </span>
                @endif
            </a>

            <div class="dropdown-menu dropdown-menu-right p-0 shadow-lg" style="width: 360px; border-radius: 8px; overflow: hidden; border: none;">

                {{-- Header --}}
                <div class="d-flex align-items-center justify-content-between px-3 py-2" style="background: #1a73e8;">
                    <span class="text-white font-weight-bold" style="font-size: 0.9rem;">
                        <i class="far fa-bell mr-2"></i>Notificações
                    </span>
                    @if(($totalNotificacoes ?? 0) > 0)
                    <span class="badge" style="background: rgba(255,255,255,0.25); color: #fff; font-size: 0.75rem;">
                        {{ $totalNotificacoes }} pendente(s)
                    </span>
                    @endif
                </div>

                {{-- Lista de notificações --}}
                <div style="max-height: 380px; overflow-y: auto; background: #fff;">
                    @forelse($notificacoes ?? [] as $notif)
                    <a href="{{ $notif['url'] }}"
                       class="d-flex align-items-start px-3 py-2 border-bottom"
                       style="text-decoration: none; color: inherit; transition: background 0.15s;"
                       onmouseover="this.style.background='#f1f3f4'"
                       onmouseout="this.style.background=''">

                        {{-- Ícone circular colorido --}}
                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 mr-3"
                             style="width: 40px; height: 40px; background-color: {{ $notif['bg'] }}; margin-top: 2px;">
                            <i class="{{ $notif['icon'] }} text-white" style="font-size: 0.85rem;"></i>
                        </div>

                        {{-- Conteúdo --}}
                        <div style="flex: 1; min-width: 0;">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="font-weight-bold text-dark" style="font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px;">
                                    {{ $notif['titulo'] }}
                                </span>
                                <span class="text-muted ml-2 flex-shrink-0" style="font-size: 0.7rem;">
                                    {{ $notif['tempo'] }}
                                </span>
                            </div>
                            <div class="text-muted" style="font-size: 0.78rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $notif['detalhe'] }}
                            </div>
                            <div class="mt-1">
                                <span class="badge badge-pill text-white" style="background-color: {{ $notif['bg'] }}; font-size: 0.68rem;">
                                    {{ $notif['status'] }}
                                </span>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-4 px-3">
                        <i class="far fa-check-circle fa-2x mb-2 d-block" style="color: #34a853;"></i>
                        <span class="text-muted" style="font-size: 0.85rem;">Nenhuma pendência no momento</span>
                    </div>
                    @endforelse
                </div>

                {{-- Footer --}}
                <div class="border-top text-center" style="background: #f8f9fa;">
                    @if(Auth::user()->hasRole('Administrador'))
                    <a href="{{ route('solicitacoes.gerenciar_solicitacoes') }}"
                       class="d-block py-2 font-weight-bold"
                       style="color: #1a73e8; text-decoration: none; font-size: 0.82rem;">
                        <i class="fas fa-list-alt mr-1"></i> Gerenciar cestas
                    </a>
                    @else
                    <a href="{{ route('solicitacoes.index') }}"
                       class="d-block py-2 font-weight-bold"
                       style="color: #1a73e8; text-decoration: none; font-size: 0.82rem;">
                        <i class="fas fa-list-alt mr-1"></i> Ver minhas solicitações
                    </a>
                    @endif
                </div>

            </div>
        </li>
        @endauth

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if($layoutHelper->isRightSidebarEnabled())
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

</nav>
