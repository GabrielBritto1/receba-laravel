@extends('adminlte::page')

@section('title', 'Controle de Acessos')

@section('content_header')
   <h1>Controle de Acessos por Papel</h1>
@stop

@section('content')
   @if(session('success'))
      <div class="alert alert-success">
         {{ session('success') }}
      </div>
   @endif

   <div class="card">
      <div class="card-header">
         <h3 class="card-title">Roles x Permissões</h3>
      </div>
      <form method="POST" action="{{ route('roles.permissions.update') }}">
         @csrf
         <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered mb-0">
               <thead>
               <tr>
                  <th>Papel</th>
                  @foreach($permissions as $permission)
                     <th class="text-center">
                        <small>{{ $permission->name }}</small>
                     </th>
                  @endforeach
               </tr>
               </thead>
               <tbody>
               @foreach($roles as $role)
                  <tr>
                     <td>
                        <strong>{{ $role->name }}</strong>
                     </td>
                     @foreach($permissions as $permission)
                        <td class="text-center align-middle">
                           <input
                              type="checkbox"
                              name="roles[{{ $role->id }}][permissions][]"
                              value="{{ $permission->id }}"
                              @if($role->permissions->contains($permission)) checked @endif
                           >
                        </td>
                     @endforeach
                  </tr>
               @endforeach
               </tbody>
            </table>
         </div>
         <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">
               <i class="fas fa-save"></i> Salvar alterações
            </button>
         </div>
      </form>
   </div>
@stop

