function formatTelefone(value) {
   value = value.replace(/\D/g, '');

   if (value.length === 11) {
      return value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
   }

   return value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
}

function formatCpf(value) {
   value = value.replace(/\D/g, '');

   return value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
}