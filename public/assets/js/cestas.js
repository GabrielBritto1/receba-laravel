cestasNaoSairam();
cestasEmRota();
cestasEntregue();

$('#btn-filtrar-cesta').on('click', function () {
   cestasNaoSairam(1);
   cestasEmRota(1);
   cestasEntregue(1);
});

function getCestaFilters() {
   const params = new URLSearchParams();
   const parceiro = $('#filtro-parceiro-cesta').val();
   if (parceiro) params.append('parceiro_id', parceiro);
   return params.toString() ? '&' + params.toString() : '';
}
function cestasNaoSairam(page = 1) {
   let tabela = $('#listCestasNaoSairam').closest('table');
   let numColunas = tabela.find('thead tr th').length;
   let tableLoad = `<tr><td colspan="${numColunas}" class="text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></td></tr>`;
   $(`#listCestasNaoSairam`).html(tableLoad);

   $.get(`${window.APP_URL}/cestas/list?page=${page}${getCestaFilters()}`, function (data) {
      if (data.status == 'success') {
         $("#listCestasNaoSairam").html('');
         let cestasNaoSairam = data.cestasNaoSairam;
         let paginationNaoSairam = data.paginationNaoSairam;

         if (cestasNaoSairam.length > 0) {
            cestasNaoSairam.forEach(item => {
               $("#listCestasNaoSairam").append(`
                  <tr>
                     <td class= "align-middle">
                        <span class="badge text-dark" style="background-color: ${item.parceiro.sigla?.color};">
                           ${item.parceiro.sigla?.name ?? item.parceiro.name}
                        </span>
                     </td>
                     <td class="align-middle">${formatDate(item.data_recebimento)}</td>
                     <td class="align-middle">
                        <span class="badge badge-danger text-uppercase">${item.status}</span>
                     </td>
                     <td class="align-middle">
                        <a href="${window.APP_URL}/cestas/entrega_familia/${item.id}" class="btn btn-warning btn-sm text-white">
                           <i class="fas fa-shipping-fast"></i>
                        </a>
                     </td>
                  </tr>
               `);
            });
         } else {
            $("#listCestasNaoSairam").append(`
               <tr>
                  <td class="align-middle text-center" colspan="${numColunas}">Nenhum registro encontrado</td>
               </tr>
            `);
         }
         renderPagination(paginationNaoSairam.current_page, paginationNaoSairam.last_page, 'paginationNaoSairam', 'cestasNaoSairam');
      } else if (data.status == "error") {
         showError(data.message)
      }
   })
      .catch(function (data) {
         showError(data.responseJSON.message)
      });
}

function cestasEmRota(page = 1) {
   let tabela = $('#listCestasEmRota').closest('table');
   let numColunas = tabela.find('thead tr th').length;
   let tableLoad = `<tr><td colspan="${numColunas}" class="text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></td></tr>`;
   $(`#listCestasEmRota`).html(tableLoad);

   $.get(`${window.APP_URL}/cestas/list?page=${page}${getCestaFilters()}`, function (data) {
      if (data.status == 'success') {
         $("#listCestasEmRota").html('');
         let cestasEmRota = data.cestasEmRota;
         let paginationEmRota = data.paginationEmRota;

         if (cestasEmRota.length > 0) {
            cestasEmRota.forEach(item => {
               $("#listCestasEmRota").append(`
                  <tr>
                     <td class="align-middle">${formatDate(item.data_em_rota)}</td>
                     <td class= "align-middle">
                        <span class="badge text-dark" style="background-color: ${item.parceiro.sigla?.color};">
                           ${item.parceiro.sigla?.name ?? item.parceiro.name}
                        </span>
                     </td>
                     <td class="align-middle">${item.familia?.representante?.nome ?? '-'}</td>
                     <td class="align-middle">
                        <span class="badge badge-warning text-uppercase text-white">${item.status}</span>
                     </td>
                     <td class="align-middle">
                        <form action="{{ route('cestas.entrega_ifes', $cesta->id) }}" method="POST" id="form-entregar-cesta-{{ $cesta->id }}">
                           @method('PUT')
                           @csrf
                           <input type="hidden" name="cesta_id" id="cesta_id" value="{{ $cesta->id }}">
                           <button type="button" class="btn btn-warning btn-sm text-white entregar-cesta" data-id="{{ $cesta->id }}">
                              <i class="fas fa-check"></i>
                           </button>
                        </form>
                     </td>
                  </tr>
               `);
            });
         } else {
            $("#listCestasEmRota").append(`
               <tr>
                  <td class="align-middle text-center" colspan="${numColunas}">Nenhum registro encontrado</td>
               </tr>
            `);
         }
         renderPagination(paginationEmRota.current_page, paginationEmRota.last_page, 'paginationEmRota', 'cestasEmRota');
      } else if (data.status == "error") {
         showError(data.message)
      }
   })
      .catch(function (data) {
         showError(data.responseJSON.message)
      });
}

function cestasEntregue(page = 1) {
   let tabela = $('#listCestasEntregue').closest('table');
   let numColunas = tabela.find('thead tr th').length;
   let tableLoad = `<tr><td colspan="${numColunas}" class="text-center"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></td></tr>`;
   $(`#listCestasEntregue`).html(tableLoad);

   $.get(`${window.APP_URL}/cestas/list?page=${page}${getCestaFilters()}`, function (data) {
      if (data.status == 'success') {
         $("#listCestasEntregue").html('');
         let cestasEntregue = data.cestasEntregue;
         let paginationEntregues = data.paginationEntregues;

         if (cestasEntregue.length > 0) {
            cestasEntregue.forEach(item => {
               $("#listCestasEntregue").append(`
                  <tr>
                     <td class="align-middle">${formatDate(item.created_at)}</td>
                     <td class= "align-middle">
                        <span class="badge text-dark" style="background-color: ${item.parceiro.sigla?.color};">
                           ${item.parceiro.sigla?.name ?? item.parceiro.name}
                        </span>
                     </td>
                     <td class="align-middle">${item.familia?.representante?.nome ?? '-'}</td>
                     <td class="align-middle">${item.ponto_origem}</td>
                     <td class="align-middle">
                        <span class="badge badge-success text-uppercase">${item.status}</span>
                     </td>
                  </tr>
               `);
            });
         } else {
            $("#listCestasEntregue").append(`
               <tr>
                  <td class="align-middle text-center" colspan="${numColunas}">Nenhum registro encontrado</td>
               </tr>
            `);
         }
         renderPagination(paginationEntregues.current_page, paginationEntregues.last_page, 'paginationEntregues', 'cestasEntregue');
      } else if (data.status == "error") {
         showError(data.message)
      }
   })
      .catch(function (data) {
         showError(data.responseJSON.message)
      });
}

$('.entregar-cesta').on('click', function () {
   const cestaId = $(this).data('id');
   Swal.fire({
      title: 'Você deseja confirmar a entrega desta cesta?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      confirmButtonText: 'Sim',
      cancelButtonText: 'Não',
   }).then((result) => {
      if (result.isConfirmed) {
         document.getElementById(`form-entregar-cesta-${cestaId}`).submit();
      }
   });
});