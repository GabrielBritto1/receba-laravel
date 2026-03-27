
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

   $.get(`${window.location.origin}/parceiros/list?page=${page}`, function (data) {
      if (data.status == 'success') {
         $("#list").html('');
         let parceiros = data.data;
         let pagination = data.pagination;

         if (parceiros.length > 0) {
            parceiros.forEach(item => {
               $("#list").append(`
                  <tr>
                     <td class="align-middle">${item.name}</td>
                     <td class="align-middle">
                        <span class="badge text-uppercase text-dark" style="background-color: ${item.sigla?.color ?? '#f1f1f1'}; font-size: 0.9em;">
                           ${item.sigla?.name ?? 'Sem Sigla'}
                        </span>
                     </td>
                     <td class="align-middle">${formatTelefone(item.telefone)}</td>
                     <td class="align-middle">${item.local_atuacao}</td>
                     <td class="align-middle">${item.status == 1
                     ? '<span class="badge badge-success">ATIVO</span>'
                     : '<span class="badge badge-danger">INATIVO</span>'
                  }
                     </td>
                     <td>
                        <div class="btn-group float-right">
                           <a href="${window.location.origin}/parceiros/${item.id}/show" class="btn btn-sm btn-success btn-md">
                              <i class="fas fa-eye"></i>
                           </a>
                           <a href="${window.location.origin}/parceiros/${item.id}/edit" class="btn btn-sm btn-warning btn-md text-white">
                              <i class="fas fa-edit"></i>
                           </a>
                           <button type="button" class="btn btn-sm btn-secondary btn-md text-white" onClick="storeSecretarioAssociar(${item.id})">
                              <i class="fas fa-user-plus"></i>
                           </button>
                           <button type="button" class="btn btn-sm btn-info btn-md ativar-btn" data-id="${item.id}">
                              <i class="fas fa-check"></i>
                           </button>
                           <button type="button" class="btn btn-sm btn-danger btn-md deletar-btn" data-id="${item.id}">
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

function storeSecretarioAssociar(id) {
   $('#associarSecretario').modal('show');
   $('#parceiro_id').val(id);
}

$(document).on('click', '.ativar-btn', function () {
   let id = $(this).data('id');
   Swal.fire({
      icon: 'question',
      title: 'Você deseja ativar este parceiro?',
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
            text: 'Aguarde o status do parceiro ser mudado.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
               Swal.showLoading();
            },
            preConfirm: () => false,
         });
         $.ajax({
            url: `/parceiros/${id}/toggleStatus`,
            method: 'POST',
            data: {
               _token: csrfToken,
            },
            success: function () {
               Swal.fire({
                  title: 'Ativado!',
                  text: 'Status do parceiro mudado com sucesso.',
                  icon: 'success'
               }).then(() => {
                  location.reload();
               })
            },
            error: function () {
               Swal.fire({
                  title: 'Erro!',
                  text: 'Houve um problema ao mudar o status do parceiro.',
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
      title: 'Você deseja excluir este parceiro?',
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
            title: 'Voce realmente deseja excluir este parceiro?',
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
                  text: 'Aguarde o parceiro ser excluido.',
                  allowOutsideClick: false,
                  allowEscapeKey: false,
                  allowEnterKey: false,
                  didOpen: () => {
                     Swal.showLoading();
                  }
               });
               $.ajax({
                  url: `/parceiros/${id}/destroy`,
                  method: 'POST',
                  data: {
                     _token: csrfToken,
                     _method: 'DELETE'
                  },
                  success: function () {
                     Swal.fire({
                        icon: 'success',
                        title: 'Parceiro excluido com sucesso!',
                     }).then(() => {
                        location.reload();
                     })
                  },
                  error: function () {
                     Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: 'Houve um problema ao excluir o parceiro.',
                     })
                  }
               });
            }
         });
      }
   });
});