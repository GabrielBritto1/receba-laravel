@extends('adminlte::page')
@section('title', 'Configurações - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-cog"></i> Configurações</h1>
@stop

@section('css')
<style>
   .partner-timeline-card .timeline {
      margin: 0;
   }

   .partner-timeline-card .timeline-item {
      box-shadow: none;
      border: 1px solid rgba(0, 0, 0, 0.08);
   }

   .partner-timeline-card .time-label span {
      font-size: 0.8rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
   }

   .partner-timeline-card .timeline-header {
      font-size: 0.95rem;
   }

   .partner-timeline-card .timeline-body {
      color: #6c757d;
      font-size: 0.9rem;
   }

   .partner-timeline-card .timeline-title {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
   }

   .partner-timeline-card .timeline-subtitle {
      color: #6c757d;
      font-size: 0.875rem;
      margin-bottom: 1rem;
   }

   .partner-timeline-empty {
      border: 1px dashed rgba(0, 0, 0, 0.15);
      border-radius: 0.25rem;
      padding: 1.5rem;
      text-align: center;
      color: #6c757d;
      background: #f8f9fa;
   }
</style>
@endsection

@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-md-3">
         <div class="card card-success">
            <div class="card-header">
               <h3 class="card-title">Meus Dados</h3>
               <a href="{{ route('users.edit', Auth::user()->id) }}">
                  <span class="text-sm float-right"><i class="fas fa-edit"></i></span>
               </a>
            </div>
            <div class="card-body">
               <strong><i class="fas fa-user mr-1"></i> Nome</strong>
               <p class="text-muted">
                  {{ Auth::user()->name ?? '-' }}
               </p>
               <hr>
               <strong><i class="fas fa-address-card mr-1"></i> CPF</strong>
               <p class="text-muted">
                  {{ Auth::user()->coordenador->cpf ?? '-' }}
               </p>
               <hr>
               <strong><i class="fas fa-phone mr-1"></i> Telefone</strong>
               <p class="text-muted">
                  {{ Auth::user()->coordenador->telefone ?? '-' }}
               </p>
               <hr>
               <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
               <p class="text-muted">
                  {{ Auth::user()->email ?? '-' }}
               </p>
               <hr>
               <strong><i class="fas fa-map-marker-alt mr-1"></i> Endereço</strong>
               <p class="text-muted">
                  {{ Auth::user()->coordenador->endereco ?? '-' }}
               </p>
               <hr>
               <strong><i class="fas fa-address-card mr-1"></i> Parceiro</strong>
               <p class="text-muted">
                  {{ Auth::user()->parceiros->pluck('name')->join('') ? : '-' }}
               </p>
            </div>
         </div>
      </div>
      <div class="col-md-9">
         <div class="card partner-timeline-card">
            <div class="card-header p-2"></div>
            <div class="card-body">
               @if ($timelinePartner)
               <div class="row">
                  <div class="col-md-6">
                     <div class="timeline-title">Últimos 7 dias</div>
                     <div class="timeline-subtitle">Eventos recentes do parceiro {{ $timelinePartner->name }}.</div>
                     @if ($weeklyTimeline->isEmpty())
                     <div class="partner-timeline-empty">Nenhuma movimentação encontrada nos últimos 7 dias.</div>
                     @else
                     <div class="timeline timeline-inverse">
                        @foreach ($weeklyTimeline as $group)
                        <div class="time-label">
                           <span class="bg-info">{{ $group['label'] }}</span>
                        </div>
                        @foreach ($group['items'] as $event)
                        <div>
                           <i class="{{ $event['icon'] }} {{ $event['background'] }}"></i>
                           <div class="timeline-item">
                              <span class="time"><i class="far fa-clock"></i> {{ $event['date']->format('H:i') }}</span>
                              <h3 class="timeline-header">{{ $event['title'] }}</h3>
                              <div class="timeline-body">{{ $event['description'] }}</div>
                           </div>
                        </div>
                        @endforeach
                        @endforeach
                        <div>
                           <i class="far fa-clock bg-gray"></i>
                        </div>
                     </div>
                     @endif
                  </div>
                  <div class="col-md-6">
                     <div class="timeline-title">Últimos 30 dias</div>
                     <div class="timeline-subtitle">Resumo mensal da operação do parceiro.</div>
                     @if ($monthlyTimeline->isEmpty())
                     <div class="partner-timeline-empty">Nenhuma movimentação encontrada nos últimos 30 dias.</div>
                     @else
                     <div class="timeline timeline-inverse">
                        @foreach ($monthlyTimeline as $group)
                        <div class="time-label">
                           <span class="bg-success">{{ $group['label'] }}</span>
                        </div>
                        @foreach ($group['items'] as $event)
                        <div>
                           <i class="{{ $event['icon'] }} {{ $event['background'] }}"></i>
                           <div class="timeline-item">
                              <span class="time"><i class="far fa-clock"></i> {{ $event['date']->format('H:i') }}</span>
                              <h3 class="timeline-header">{{ $event['title'] }}</h3>
                              <div class="timeline-body">{{ $event['description'] }}</div>
                           </div>
                        </div>
                        @endforeach
                        @endforeach
                        <div>
                           <i class="far fa-clock bg-gray"></i>
                        </div>
                     </div>
                     @endif
                  </div>
               </div>
               @else
               <div class="partner-timeline-empty">Como você não tem parceiro, nao tem timeline</div>
               @endif
            </div>
         </div>
      </div>
   </div>
</div>
@stop
