@extends('adminlte::page')
@section('title', 'Configurações - RECeBa')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-cog"></i> Configurações</h1>
@stop

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
                  {{ Auth::user()->name }}
               </p>
               <hr>
               <strong><i class="fas fa-address-card mr-1"></i> CPF</strong>
               <p class="text-muted">
                  111.111.111-11
               </p>
               <hr>
               <strong><i class="fas fa-phone mr-1"></i> Telefone</strong>
               <p class="text-muted">
                  (11) 1111-1111
               </p>
               <hr>
               <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
               <p class="text-muted">
                  {{ Auth::user()->email }}
               </p>
               <hr>
               <strong><i class="fas fa-map-marker-alt mr-1"></i> Endereço</strong>
               <p class="text-muted">
                  Rua dos Bobos, 0
               </p>
               <hr>
               <strong><i class="fas fa-address-card mr-1"></i> Parceiro</strong>
               <p class="text-muted">
                  {{ Auth::user()->parceiros->pluck('name')->join('') }}
               </p>
            </div>
         </div>
      </div>
      <div class="col-md-9">
         <div class="card">
            <div class="card-header p-2"></div>
            <div class="card-body">
               <div class="timeline timeline-inverse">
                  <div class="time-label">
                     <span class="bg-danger">
                        10 Feb. 2014
                     </span>
                  </div>
                  <div>
                     <i class="fas fa-envelope bg-primary"></i>

                     <div class="timeline-item">
                        <span class="time"><i class="far fa-clock"></i> 12:05</span>

                        <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                        <div class="timeline-body">
                           Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                           weebly ning heekya handango imeem plugg dopplr jibjab, movity
                           jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                           quora plaxo ideeli hulu weebly balihoo...
                        </div>
                        <div class="timeline-footer">
                           <a href="#" class="btn btn-primary btn-sm">Read more</a>
                           <a href="#" class="btn btn-danger btn-sm">Delete</a>
                        </div>
                     </div>
                  </div>
                  <div>
                     <i class="fas fa-user bg-info"></i>

                     <div class="timeline-item">
                        <span class="time"><i class="far fa-clock"></i> 5 mins ago</span>

                        <h3 class="timeline-header border-0"><a href="#">Sarah Young</a> accepted your friend request
                        </h3>
                     </div>
                  </div>
                  <div>
                     <i class="fas fa-comments bg-warning"></i>

                     <div class="timeline-item">
                        <span class="time"><i class="far fa-clock"></i> 27 mins ago</span>

                        <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                        <div class="timeline-body">
                           Take me to your leader!
                           Switzerland is small and neutral!
                           We are more like Germany, ambitious and misunderstood!
                        </div>
                        <div class="timeline-footer">
                           <a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
                        </div>
                     </div>
                  </div>
                  <div class="time-label">
                     <span class="bg-success">
                        3 Jan. 2014
                     </span>
                  </div>
                  <div>
                     <i class="fas fa-camera bg-purple"></i>

                     <div class="timeline-item">
                        <span class="time"><i class="far fa-clock"></i> 2 days ago</span>

                        <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                        <div class="timeline-body">
                           <img src="https://placehold.it/150x100" alt="...">
                           <img src="https://placehold.it/150x100" alt="...">
                           <img src="https://placehold.it/150x100" alt="...">
                           <img src="https://placehold.it/150x100" alt="...">
                        </div>
                     </div>
                  </div>
                  <div>
                     <i class="far fa-clock bg-gray"></i>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@stop