@php( $logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout') )
@php( $profile_url = View::getSection('profile_url') ?? config('adminlte.profile_url', 'logout') )

@if (config('adminlte.usermenu_profile_url', false))
@php( $profile_url = Auth::user()->adminlte_profile_url() )
@endif

@if (config('adminlte.use_route_url', false))
@php( $profile_url = $profile_url ? route($profile_url) : '' )
@php( $logout_url = $logout_url ? route($logout_url) : '' )
@else
@php( $profile_url = $profile_url ? url($profile_url) : '' )
@php( $logout_url = $logout_url ? url($logout_url) : '' )
@endif

<li class="nav-item dropdown user-menu">

   {{-- User menu toggler --}}
   <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
      @if(config('adminlte.usermenu_image'))
      <img src="{{ Auth::user()->adminlte_image() }}"
         class="user-image img-circle elevation-2"
         alt="{{ Auth::user()->name }}">
      @endif
      <span @if(config('adminlte.usermenu_image')) class="d-none d-md-inline" @endif>
         {{ Auth::user()->name }}
      </span>
   </a>

   {{-- User menu dropdown --}}
   <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

      {{-- User menu header --}}
      @if(!View::hasSection('usermenu_header') && config('adminlte.usermenu_header'))
      <li class="user-header {{ config('adminlte.usermenu_header_class', 'bg-primary') }}
                @if(!config('adminlte.usermenu_image')) h-auto @endif">
         @if(config('adminlte.usermenu_image'))
         <img src="{{ Auth::user()->adminlte_image() }}"
            class="img-circle elevation-2"
            alt="{{ Auth::user()->name }}">
         @endif
         <p class="@if(!config('adminlte.usermenu_image')) mt-0 @endif">
            {{ Auth::user()->name }}
            @if(config('adminlte.usermenu_desc'))
            <small>{{ Auth::user()->adminlte_desc() }}</small>
            @endif
         </p>
      </li>
      @else
      @yield('usermenu_header')
      @endif

      {{-- Configured user menu links --}}
      @each('adminlte::partials.navbar.dropdown-item', $adminlte->menu("navbar-user"), 'item')

      {{-- User menu body --}}
      @hasSection('usermenu_body')
      <li class="user-body">
         @yield('usermenu_body')
      </li>
      @endif

      {{-- User menu footer --}}
      <li class="user-footer">
         @if($profile_url)
         <a href="{{ $profile_url }}" class="nav-link btn btn-default btn-flat d-inline-block">
            <i class="fa fa-fw fa-user text-lightblue"></i>
            {{ __('adminlte::menu.profile') }}
         </a>
         @endif
         <div class="text-bold text-center text-sm">
            <h5>{{ Auth::user()->name }}</h5>
            <span>
               @if(Auth::user()->parceiros())
               {{ Auth::user()->parceiros->pluck('name')->join('') }}
               @else
               Usuário sem Parceiro
               @endif
            </span>
            <br>
            <span>
               @if(Auth::user()->roles->isNotEmpty())
               {{ Auth::user()->roles->pluck('formatted_name')->join('') }}
               @else
               Usuário sem Papel
               @endif
            </span>
         </div>
         <hr>
         <a class="btn btn-default btn-flat float-right text-bold text-dark @if(!$profile_url) btn-block @endif"
            href="{{ route('users.configuracao', Auth::user()->id) }}">
            <i class="fas fa-cog"></i>
            Configuração
         </a>
         @can('is-admin')
            <a class="btn btn-default btn-flat float-right text-bold text-dark @if(!$profile_url) btn-block @endif"
               href="{{ route('users.gerenciar_usuarios') }}">
               <i class="fas fa-id-badge"></i>
               Gerenciar Usuários
            </a>
            <a class="btn btn-default btn-flat float-right text-bold text-dark @if(!$profile_url) btn-block @endif" href="#">
               <i class="fas fa-shopping-bag"></i>
               Controle Itens
            </a>
            <a class="btn btn-default btn-flat float-right text-bold text-dark @if(!$profile_url) btn-block @endif" href="{{ route('users.gerenciar_siglas') }}">
               <i class="fas fa-tag"></i>
               Gerenciar Siglas
            </a>
         @endcan
         <a class="btn btn-default btn-flat float-right text-bold text-dark @if(!$profile_url) btn-block @endif"
            href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-fw fa-power-off text-red"></i>
            {{ __('adminlte::adminlte.log_out') }}
         </a>
         <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
            @if(config('adminlte.logout_method'))
            {{ method_field(config('adminlte.logout_method')) }}
            @endif
            {{ csrf_field() }}
         </form>
      </li>

   </ul>

</li>