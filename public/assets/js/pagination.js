function renderPagination(current, last) {
   const $container = $('#paginationLinks');
   $container.html('');
   if (last <= 1) return;

   let html = '';

   const addBtn = (page, label = null, active = false, disabled = false) => {
      label = label || page;
      let classes = 'btn btn-sm mr-1 ';
      if (active) classes += 'btn-success';
      else classes += 'btn-light';
      if (disabled) classes += ' disabled';

      html += `<button type="button" class="${classes}" ${disabled ? 'tabindex="-1" aria-disabled="true"' : `onclick="loadPrincipal(${page})"`}>${label}</button>`;
   };

   // Anterior
   if (current > 1) addBtn(current - 1, '«');

   const windowSize = 2; // páginas antes/depois da atual

   let start = Math.max(2, current - windowSize);
   let end = Math.min(last - 1, current + windowSize);

   // Sempre mostra página 1
   addBtn(1, '1', current === 1);

   // "..." depois da primeira, se necessário
   if (start > 2) {
      addBtn(null, '...', false, true);
   }

   // Páginas do meio
   for (let i = start; i <= end; i++) {
      addBtn(i, String(i), i === current);
   }

   // "..." antes da última, se necessário
   if (end < last - 1) {
      addBtn(null, '...', false, true);
   }

   // Sempre mostra última
   if (last > 1) {
      addBtn(last, String(last), current === last);
   }

   // Próxima
   if (current < last) addBtn(current + 1, '»');

   $container.html(html);
}