// Capturar CSRF token da meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// Função para mostrar erro
function showError(message) {
   Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: message,
   });
}

loadPrincipal();

function loadPrincipal(page = 1) {
   let tabela = $('#list').closest('table');
   let numColunas = tabela.find('thead tr th').length;
   let tableLoad = `<tr><td colspan="${numColunas}" class="text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></td></tr>`;
   $(`#list`).html(tableLoad);

   $.get(`${window.location.origin}/familias/list?page=${page}`, function (data) {
      if (data.status == 'success') {
         $("#list").html('');
         let familias = data.familias;
         let pagination = data.pagination;

         if (familias.length > 0) {
            familias.forEach(item => {
               $("#list").append(`
                  <tr>
                     <td class="align-middle">${item.representante.nome}</td>
                     <td class="align-middle">${formatCpf(item.representante.cpf) ? formatCpf(item.representante.cpf) : '-'}</td>
                     <td class="align-middle">${formatTelefone(item.representante.telefone) ? formatTelefone(item.representante.telefone) : '-'}</td>
                     <td class= "align-middle">
                        <span class="badge p-2 text-dark" style="background-color: ${item.parceiro.sigla?.color}; font-size: 16px;">
                           ${item.parceiro.sigla?.name ?? item.parceiro.name}
                        </span>
                     </td>
                     <td class="align-middle">
                     ${item.status
                     ? `<span class="badge text-uppercase badge-success">Ativo</span>`
                     : `<span class="badge text-uppercase badge-danger">Inativo</span>`}
                     </td>
                     <td>
                        <div class="btn-group float-right">
                           <a href="${window.location.origin}/familias/${item.id}/show" class="btn btn-success btn-sm">
                              <i class="fas fa-eye"></i>
                           </a>
                           <a href="${window.location.origin}/familias/${item.id}/edit" class="btn btn-warning btn-sm text-white">
                              <i class="fas fa-edit"></i>
                           </a>
                           <button type="button" class="btn btn-info btn-sm text-white ativar-btn" data-id="${item.id}">
                              <i class="fas fa-check"></i>
                           </button>
                           <button type="button" class="btn btn-danger btn-sm deletar-btn" data-id="${item.id}">
                              <i class="fas fa-trash"></i>
                           </button>
                        </div>
                     </td>
                  </tr>
               `);
            });
         } else {
            $("#list").append(`
               <tr>
                  <td class="align-middle text-center" colspan="${numColunas}">Nenhum registro encontrado</td>
               </tr>
            `);
         }
         renderPagination(pagination.current_page, pagination.last_page);
      } else if (data.status == "error") {
         showError(data.message)
      }
   })
      .catch(function (data) {
         showError(data.responseJSON.message)
      });
}

// ==========================================================
//  FUNÇÃO REUTILIZÁVEL PARA VERIFICAR CPF
// ==========================================================
$.ajaxSetup({
   headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   }
});
function verificarCpf(cpf, isConjuge = false) {
   if (!cpf) return;

   $.ajax({
      url: `/familias/check-cpf`,
      type: 'POST',
      data: {
         cpf: cpf
      },
      success: function (response) {
         if (response.exists) {

            // CASO 1: CPF já associado ao parceiro atual
            if (response.status === 'already_associated') {
               Swal.fire({
                  icon: 'error',
                  title: 'Família já Cadastrada!',
                  text: 'Este CPF já pertence a uma família que está associada ao seu parceiro.',
                  confirmButtonText: 'Entendido',
                  confirmButtonColor: '#28a745',
                  allowOutsideClick: false,
               });
               // Limpa o campo para evitar envio do formulário com dados duplicados
               if (isConjuge) {
                  $('#cpf_conjuge').val('');
               } else {
                  $('#cpf').val('');
               }

               // CASO 2: CPF existe, mas em outro parceiro (pode importar)
            } else if (response.status === 'can_import' && !isConjuge) {
               Swal.fire({
                  icon: 'info',
                  title: 'CPF já cadastrado!',
                  text: 'Este CPF já é representante de uma família em outro parceiro. Redirecionando para a página de associação.',
                  confirmButtonText: 'OK',
                  confirmButtonColor: '#28a745',
                  allowOutsideClick: false,
               }).then((result) => {
                  if (result.isConfirmed) {
                     let redirectUrl = `/familias/${response.familia_id}/importacao_cpf`; // `"{{ route('familias.importacao_cpf', ['familia' => ':familiaId']) }}";
                     redirectUrl = redirectUrl.replace(':familiaId', response.familia_id);
                     window.location.href = redirectUrl;
                  }
               });

               // CASO 3: CPF do cônjuge já existe (apenas informa)
            } else if (response.status === 'can_import' && isConjuge) {
               Swal.fire({
                  icon: 'warning',
                  title: 'Atenção!',
                  text: 'O CPF informado para o cônjuge já está cadastrado como representante de outra família.',
                  confirmButtonText: 'Entendido',
               });
            }
         }
      },
      error: function () {
         console.error('Erro ao verificar o CPF.');
      }
   });
}

// ==========================================================
//  EVENTOS DE 'BLUR' PARA OS CAMPOS DE CPF
// ==========================================================
// Evento para o CPF do representante
$('#cpf').on('blur', function () {
   verificarCpf($(this).val(), false);
});

// Evento para o CPF do cônjuge
$('#cpf_conjuge').on('blur', function () {
   verificarCpf($(this).val(), true);
});
// Lógica para mostrar/esconder campo de valor do aluguel
$('#reside').on('change', function () {
   if ($(this).val() === 'Alugada') {
      $('#campo_aluguel').show();
   } else {
      $('#campo_aluguel').hide();
      $('#alugada_valor').val(''); // Limpa o valor se não for alugada
   }
}).trigger('change'); // Dispara o evento ao carregar a página para o caso de old('reside') ser 'Alugada'

// Lógica para mostrar/esconder campo NIS
$('#cadunico').on('change', function () {
   if ($(this).val() === 'Sim') {
      $('#campo_nis').show();
   } else {
      $('#campo_nis').hide();
      $('#campo_nis').val(''); // Limpa o NIS se não tiver CadÚnico
   }
}).trigger('change');

// Lógica para mostrar/esconder campo de quantidade de idosos
$('input[name="possui_idoso"]').on('change', function () {
   if ($('#sim_idoso').is(':checked')) {
      $('#quantidade_idosos').show();
   } else {
      $('#quantidade_idosos').hide();
      $('#quantidade_idosos').val('0'); // Define como 0 se não houver idosos
   }
}).trigger('change');

$(document).on('click', '.ativar-btn', function () {
   let id = $(this).data('id');
   Swal.fire({
      icon: 'question',
      title: 'Você deseja ativar esta família?',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      confirmButtonText: 'Sim',
      cancelButtonText: 'Não',
      allowOutsideClick: false,
      allowEscapeKey: false,
   }).then((result) => {
      if (result.isConfirmed) {
         Swal.fire({
            title: 'Processando...',
            text: 'Aguarde o status da família ser mudado.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
               Swal.showLoading();
            },
            preConfirm: () => false,
         });
         $.ajax({
            url: `/familias/${id}/toggleStatus`,
            method: 'POST',
            data: {
               _token: csrfToken,
            },
            success: function () {
               Swal.fire({
                  title: 'Ativado!',
                  text: 'Status da família mudado com sucesso.',
                  icon: 'success'
               }).then(() => {
                  location.reload();
               })
            },
            error: function () {
               Swal.fire({
                  title: 'Erro!',
                  text: 'Houve um problema ao mudar o status da família.',
                  icon: 'error'
               })
            }
         });
      }
   });
});

$(document).on('click', '.deletar-btn', function () {
   let id = $(this).data('id');
   Swal.fire({
      icon: 'info',
      title: 'Você deseja excluir esta família?',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      confirmButtonText: 'Sim',
      cancelButtonText: 'Não',
      allowOutsideClick: false,
      allowEscapeKey: false,
   }).then((result) => {
      if (result.isConfirmed) {
         Swal.fire({
            icon: 'question',
            title: 'Voce realmente deseja excluir esta família?',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não',
            allowOutsideClick: false,
            allowEscapeKey: false,
         }).then((result) => {
            if (result.isConfirmed) {
               Swal.fire({
                  title: 'Processando...',
                  text: 'Aguarde a família ser excluída.',
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false,
                  didOpen: () => {
                     Swal.showLoading();
                  }
               });
               $.ajax({
                  url: `/familias/${id}/destroy`,
                  method: 'POST',
                  data: {
                     _token: csrfToken,
                     _method: 'DELETE'
                  },
                  success: function () {
                     Swal.fire({
                        icon: 'success',
                        title: 'Família excluida com sucesso!',
                     }).then(() => {
                        location.reload();
                     })
                  },
                  error: function () {
                     Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Houve um problema ao excluir a família.',
                     })
                  }
               });
            }
         });
      }
   });
});