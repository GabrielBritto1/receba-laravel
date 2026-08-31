// Máscaras de formatação de campos, aplicadas via evento "input" para funcionar
// tanto em campos estáticos quanto nos que ficam dentro de modais já presentes no DOM.

(function () {
   function formatarCep(valor) {
      var digitos = valor.replace(/\D/g, '').slice(0, 8);
      if (digitos.length > 5) {
         return digitos.slice(0, 5) + '-' + digitos.slice(5);
      }
      return digitos;
   }

   function aplicarMascaraCep() {
      document.querySelectorAll('input[name="cep"]').forEach(function (input) {
         if (input.value) {
            input.value = formatarCep(input.value);
         }
         input.addEventListener('input', function () {
            var posicaoCursor = input.selectionStart;
            var tamanhoAntes = input.value.length;
            input.value = formatarCep(input.value);
            var diferenca = input.value.length - tamanhoAntes;
            input.setSelectionRange(posicaoCursor + diferenca, posicaoCursor + diferenca);
         });
      });
   }

   if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', aplicarMascaraCep);
   } else {
      aplicarMascaraCep();
   }
})();
