loadEntregues();

function loadEntregues(page = 1) {
   const $tbody = $('#listSolicitacoesEntregues');
   const numColunas = $tbody.closest('table').find('thead tr th').length;
   $tbody.html(`<tr><td colspan="${numColunas}" class="text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></td></tr>`);

   $.get(`${window.APP_URL}/solicitacoes/listEntregues?page=${page}`, function (data) {
      if (data.status !== 'success') return;

      $tbody.html('');
      const items = data.solicitacoesEntregues;
      const pagination = data.paginationEntregues;

      if (pagination.total === 0) {
         $('#secaoEntregues').addClass('d-none');
         return;
      }

      $('#secaoEntregues').removeClass('d-none');
      items.forEach(item => {
         const cor = item.parceiro.sigla?.color ?? '#f1f1f1';
         const sigla = item.parceiro.sigla?.name ?? item.parceiro.name;
         $tbody.append(`
            <tr>
               <td class="align-middle">
                  <span class="badge text-dark" style="background-color: ${cor};">${sigla}</span>
               </td>
               <td class="align-middle">${formatDate(item.created_at)}</td>
               <td class="align-middle">${formatDate(item.data_previsao_entrega)}</td>
               <td class="align-middle">${item.quantidade_aceita ?? '-'}</td>
               <td class="align-middle">
                  <span class="badge badge-success text-uppercase">${item.status}</span>
               </td>
            </tr>
         `);
      });

      renderPagination(pagination.current_page, pagination.last_page, 'paginationLinksEntregues', 'loadEntregues');
   }).catch(function () {
      $tbody.html(`<tr><td colspan="${numColunas}" class="text-center text-danger">Erro ao carregar solicitações entregues.</td></tr>`);
   });
}
