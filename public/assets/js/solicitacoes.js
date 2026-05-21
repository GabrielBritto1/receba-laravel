function showError(message) {
   Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: message,
   });
}

loadPrincipal();
loadSecundario();
function loadPrincipal(page = 1) {
   let tabela = $('#listSolicitacoes').closest('table');
   let numColunas = tabela.find('thead tr th').length;
   let tableLoad = `<tr><td colspan="${numColunas}" class="text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></td></tr>`;
   $(`#listSolicitacoes`).html(tableLoad);

   $.get(`${window.APP_URL}/solicitacoes/list?page=${page}`, function (data) {
      if (data.status == 'success') {
         $("#listSolicitacoes").html('');
         let solicitacoes = data.solicitacoes;
         let pagination = data.pagination;

         if (solicitacoes.length > 0) {
            solicitacoes.forEach(item => {
               $("#listSolicitacoes").append(`
                  <tr>
                     <td class="align-middle">${formatDate(item.created_at)}</td>
                     <td class= "align-middle">
                        <span class="badge text-dark" style="background-color: ${item.parceiro.sigla?.color};">
                           ${item.parceiro.sigla?.name ?? item.parceiro.name}
                        </span>
                     </td>
                     <td class="align-middle">${item.quantidade_solicitada}</td>
                     <td class="align-middle">${item.quantidade_aceita ?? '-'}</td>
                     <td class="align-middle">
                     ${item.status == 'Em Análise'
                     ? `<span class="badge badge-primary text-uppercase" style="background-color: #FF9E4A;">Em Análise</span>`
                     : item.status == 'Aceita'
                        ? `<span class="badge badge-info text-uppercase">Aceita</span>`
                        : item.status == 'Montada'
                           ? `<span class="badge badge-warning text-uppercase text-white">Montada</span>`
                           : item.status == 'Entregue'
                              ? `<span class="badge badge-success text-uppercase">Entregue</span>`
                              : ''}
                     </td>
                  </tr>
               `);
            });
         } else {
            $("#listSolicitacoes").append(`
               <tr>
                  <td class="align-middle text-center" colspan="${numColunas}">Nenhum registro encontrado</td>
               </tr>
            `);
         }
         renderPagination(pagination.current_page, pagination.last_page, 'paginationLinksPrincipal', 'loadPrincipal');
      } else if (data.status == "error") {
         showError(data.message)
      }
   })
      .catch(function (data) {
         showError(data.responseJSON.message)
      });
}

function loadSecundario(page = 1) {
   let tabela = $('#listSolicitacoesNaoAceitas').closest('table');
   let numColunas = tabela.find('thead tr th').length;
   let tableLoad = `<tr><td colspan="${numColunas}" class="text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></td></tr>`;
   $(`#listSolicitacoesNaoAceitas`).html(tableLoad);

   $.get(`${window.APP_URL}/solicitacoes/listNaoAceitas?page=${page}`, function (data) {
      if (data.status == 'success') {
         $("#listSolicitacoesNaoAceitas").html('');
         let solicitacoesNaoAceitas = data.solicitacoesNaoAceitas;
         let paginationNaoAceitas = data.paginationNaoAceitas;

         if (solicitacoesNaoAceitas.length > 0) {
            solicitacoesNaoAceitas.forEach(item => {
               $("#listSolicitacoesNaoAceitas").append(`
                  <tr>
                     <td class="align-middle">${formatDate(item.created_at)}</td>
                     <td class= "align-middle">
                        <span class="badge text-dark" style="background-color: ${item.parceiro.sigla?.color};">
                           ${item.parceiro.sigla?.name ?? item.parceiro.name}
                        </span>
                     </td>
                     <td class="align-middle">${item.quantidade_solicitada}</td>
                     <td class="align-middle">${item.quantidade_nao_aceita ?? '-'}</td>
                     <td class="align-middle">
                        <span class="badge badge-danger text-uppercase">Não Aceita</span>
                     </td>
                  </tr>
               `);
            });
         } else {
            $("#listSolicitacoesNaoAceitas").append(`
               <tr>
                  <td class="align-middle text-center" colspan="${numColunas}">Nenhum registro encontrado</td>
               </tr>
            `);
         }
         renderPagination(paginationNaoAceitas.current_page, paginationNaoAceitas.last_page, 'paginationLinksSecundario', 'loadSecundario');
      } else if (data.status == "error") {
         showError(data.message)
      }
   })
      .catch(function (data) {
         showError(data.responseJSON.message)
      });
}