@extends('adminlte::page')
@section('title', 'Entrega Família')
@section('content_header')
<h1 class="text-bold"><i class="fas fa-user-friends"></i> Entrega Família</h1>
@stop

@section('content')
<div class="card">
   <div class="card-body">
      <form action="{{ route('cestas.entrega_familia_store', $cesta->id) }}" method="POST" id="form-enviar-cesta">
         @method('PUT')
         @csrf
         <div class="row">
            <div class="col">
               <div class="form-group">
                  <label for="familia_id">Família</label>
                  <select name="familia_id" id="familia_id" class="form-control">
                     <option selected disabled value="">Selecione uma Família</option>
                     @forelse($familias as $familia)
                     <option value="{{ $familia->id }}">{{ $familia->representante->nome ?? 'Nome não encontrado' }}</option>
                     @empty
                     <option value="">Nenhuma Família cadastrada</option>
                     @endforelse
                  </select>
               </div>
            </div>
         </div>
         <button type="button" id="enviar-cesta" class="btn btn-success float-right" data-id="{{ $cesta->id }}">Enviar Cesta</button>
      </form>
      <div id="historico-cestas">
      </div>

   </div>
</div>
@stop

@section('js')
<script>
   $(document).ready(function() {
      $('#enviar-cesta').on('click', function() {
         const cestaId = $(this).data('id');
         const familiaId = $('#familia_id').val();
         const formAction = $('#form-enviar-cesta').attr('action');
         if (!familiaId) {
            Swal.fire({
               title: 'Atenção',
               text: 'Por favor, selecione uma família antes de enviar a cesta.',
               icon: 'warning'
            });
            return;
         }
         Swal.fire({
            icon: 'question',
            title: 'Você deseja entregar a cesta para esta família?',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não',
         }).then((result) => {
            if (result.isConfirmed) {
               Swal.fire({
                  title: 'Processando...',
                  allowOutsideClick: false,
                  didOpen: () => {
                     Swal.showLoading()
                  }
               });

               $.ajax({
                  url: formAction,
                  method: 'POST',
                  data: {
                     _token: '{{ csrf_token() }}',
                     _method: 'PUT',
                     familia_id: familiaId,
                  },
                  success: function(response) {
                     Swal.fire({
                        title: 'Cesta em rota!',
                        text: 'Sucesso!',
                        icon: 'success'
                     }).then(() => {
                        window.location.href = "{{ route('cestas.index') }}";
                     })
                  },
                  error: function(xhr) {
                     const errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Houve um problema ao enviar a cesta.';
                     Swal.fire({
                        title: 'Erro!',
                        text: errorMsg,
                        icon: 'error'
                     })
                  }
               });
            }
         });
      });

      // ==========================================================

      $('#familia_id').on('change', function() {
         const familiaId = $(this).val();
         const container = $('#historico-cestas');
         container.html('<p>Buscando histórico...</p>');

         if (!familiaId) {
            container.html('');
            return;
         }

         let url = "{{ route('familias.getCestas', ['familia' => ':familiaId']) }}";
         url = url.replace(':familiaId', familiaId);

         $.ajax({
            url: url,
            type: 'GET',
            success: function(cestas) {
               container.html('');

               if (cestas.length === 0) {
                  container.html('<p>Nenhuma cesta entregue para esta família.</p>');
                  return;
               }

               let tableHtml = `
                    <h4 class="mt-4">Histórico de Entregas</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Cesta entregue por</th>
                                <th>Data de Saída</th>
                                <th>Data de Entrega</th>
                            </tr>
                        </thead>
                        <tbody>
                `;

               // ==========================================================
               //  INÍCIO DA NOVA LÓGICA (COMPARANDO COM HOJE)
               // ==========================================================
               const hoje = new Date();
               // Zera as horas para comparar apenas os dias
               hoje.setHours(0, 0, 0, 0);

               cestas.forEach(function(cesta) {

                  let classeCor = 'bg-secondary'; // Cor padrão

                  if (cesta.data_entrega) {
                     const dataEntrega = new Date(cesta.data_entrega);

                     // Calcula a diferença em milissegundos entre hoje e a data da entrega
                     const diffMilliseconds = hoje - dataEntrega;

                     // Converte a diferença para dias
                     const diffDays = diffMilliseconds / (1000 * 60 * 60 * 24);

                     // Aplica a regra de cores
                     if (diffDays <= 30) {
                        classeCor = 'bg-danger'; // Vermelho: Entregue nos últimos 30 dias
                     } else {
                        classeCor = 'bg-success'; // Verde: Entregue há mais de 30 dias
                     }
                  }

                  // Formata as datas para exibição
                  const dataSaida = cesta.data_em_rota ? new Date(cesta.data_em_rota).toLocaleDateString('pt-BR', {
                     timeZone: 'UTC'
                  }) : '-';
                  const dataEntregaFormatada = cesta.data_entrega ? new Date(cesta.data_entrega).toLocaleDateString('pt-BR', {
                     timeZone: 'UTC'
                  }) : '-';

                  tableHtml += `
                        <tr>
                            <td>${cesta.parceiro.name}</td>
                            <td class="text-white text-center font-weight-bold ${classeCor}">${dataSaida}</td>
                            <td class="text-white text-center font-weight-bold ${classeCor}">${dataEntregaFormatada}</td>
                        </tr>
                    `;
               });
               // ==========================================================
               //  FIM DA NOVA LÓGICA
               // ==========================================================

               tableHtml += '</tbody></table>';
               container.html(tableHtml);
            },
            error: function() {
               container.html('<p class="text-danger">Erro ao buscar o histórico. Tente novamente.</p>');
            }
         });
      });
   });
</script>
@stop