function showItemError(message) {
   Swal.fire({
      icon: 'error',
      title: 'Erro!',
      text: message,
   });
}

loadItensPrincipal();
loadItensSecundario();

$('#btn-filtrar-item').on('click', function () {
   loadItensPrincipal(1);
   loadItensSecundario(1);
});

function getItemFilters() {
   const params = new URLSearchParams();
   const parceiro = $('#filtro-parceiro-item').val();
   const itemId = $('#filtro-item-id').val();
   const dataInicio = $('#filtro-data-inicio-item').val();
   const dataFim = $('#filtro-data-fim-item').val();
   if (parceiro) params.append('parceiro_id', parceiro);
   if (itemId) params.append('item_id', itemId);
   if (dataInicio) params.append('data_inicio', dataInicio);
   if (dataFim) params.append('data_fim', dataFim);
   return params.toString() ? '&' + params.toString() : '';
}

function itemStatusBadge(status) {
   if (status === 'Em Análise') {
      return `<span class="badge badge-primary text-uppercase" style="background-color: #FF9E4A;">Em Análise</span>`;
   }

   if (status === 'Aceita') {
      return `<span class="badge badge-info text-uppercase">Aceita</span>`;
   }

   if (status === 'Montada') {
      return `<span class="badge badge-warning text-uppercase text-white">Montada</span>`;
   }

   if (status === 'Entregue') {
      return `<span class="badge badge-success text-uppercase">Entregue</span>`;
   }

   return '-';
}

function itemPartnerBadge(item) {
   const color = item.parceiro.sigla?.color ?? '#f1f1f1';
   const label = item.parceiro.sigla?.name ?? item.parceiro.name;

   return `
      <span class="badge text-dark" style="background-color: ${color};">
         ${label}
      </span>
   `;
}

function loadItensPrincipal(page = 1) {
   const tabela = $('#listItens').closest('table');
   const numColunas = tabela.find('thead tr th').length;
   const tableLoad = `<tr><td colspan="${numColunas}" class="text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></td></tr>`;
   $('#listItens').html(tableLoad);

   $.get(`${window.APP_URL}/itens/list?page=${page}${getItemFilters()}`, function (data) {
      if (data.status === 'success') {
         $('#listItens').html('');
         const solicitacoes = data.solicitacoes;
         const pagination = data.pagination;

         if (solicitacoes.length > 0) {
            solicitacoes.forEach(item => {
               $('#listItens').append(`
                  <tr>
                     <td class="align-middle">${formatDate(item.created_at)}</td>
                     <td class="align-middle">${itemPartnerBadge(item)}</td>
                     <td class="align-middle">${item.item?.nome ?? '-'}</td>
                     <td class="align-middle">${item.quantidade_solicitada}</td>
                     <td class="align-middle">${item.quantidade_aceita ?? '-'}</td>
                     <td class="align-middle">${itemStatusBadge(item.status)}</td>
                  </tr>
               `);
            });
         } else {
            $('#listItens').append(`
               <tr>
                  <td class="align-middle text-center" colspan="${numColunas}">Nenhum registro encontrado</td>
               </tr>
            `);
         }

         renderPagination(pagination.current_page, pagination.last_page, 'paginationLinksItensPrincipal', 'loadItensPrincipal');
      } else if (data.status === 'error') {
         showItemError(data.message);
      }
   }).catch(function (data) {
      showItemError(data.responseJSON.message);
   });
}

function loadItensSecundario(page = 1) {
   const tabela = $('#listItensNaoAceitos').closest('table');
   const numColunas = tabela.find('thead tr th').length;
   const tableLoad = `<tr><td colspan="${numColunas}" class="text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></td></tr>`;
   $('#listItensNaoAceitos').html(tableLoad);

   $.get(`${window.APP_URL}/itens/listNaoAceitas?page=${page}${getItemFilters()}`, function (data) {
      if (data.status === 'success') {
         $('#listItensNaoAceitos').html('');
         const solicitacoesNaoAceitas = data.solicitacoesNaoAceitas;
         const paginationNaoAceitas = data.paginationNaoAceitas;

         if (solicitacoesNaoAceitas.length > 0) {
            solicitacoesNaoAceitas.forEach(item => {
               $('#listItensNaoAceitos').append(`
                  <tr>
                     <td class="align-middle">${formatDate(item.created_at)}</td>
                     <td class="align-middle">${itemPartnerBadge(item)}</td>
                     <td class="align-middle">${item.item?.nome ?? '-'}</td>
                     <td class="align-middle">${item.quantidade_solicitada}</td>
                     <td class="align-middle">${item.quantidade_nao_aceita ?? '-'}</td>
                     <td class="align-middle">
                        <span class="badge badge-danger text-uppercase">Não Aceita</span>
                     </td>
                  </tr>
               `);
            });
         } else {
            $('#listItensNaoAceitos').append(`
               <tr>
                  <td class="align-middle text-center" colspan="${numColunas}">Nenhum registro encontrado</td>
               </tr>
            `);
         }

         renderPagination(paginationNaoAceitas.current_page, paginationNaoAceitas.last_page, 'paginationLinksItensSecundario', 'loadItensSecundario');
      } else if (data.status === 'error') {
         showItemError(data.message);
      }
   }).catch(function (data) {
      showItemError(data.responseJSON.message);
   });
}
