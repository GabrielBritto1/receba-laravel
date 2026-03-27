<?php

namespace App\Support;

class Formatter
{
   public static function money($value)
   {
      return number_format($value, 2, ',', '.');
   }

   public static function telefone($value)
   {
      $value = preg_replace('/\D/', '', $value);

      if (!$value) return null;

      if (strlen($value) === 11) {
         return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $value);
      }

      if (strlen($value) === 10) {
         return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $value);
      }

      return $value; // fallback
   }

   public static function cnpj($value)
   {
      $value = preg_replace('/\D/', '', $value);

      if (strlen($value) !== 14) {
         return $value;
      }

      return preg_replace(
         '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
         '$1.$2.$3/$4-$5',
         $value
      );
   }

   public static function cep($value)
   {
      $value = preg_replace('/\D/', '', $value);

      if (strlen($value) !== 8) {
         return $value;
      }

      return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $value);
   }

   public static function cpf($value)
   {
      $value = preg_replace('/\D/', '', $value);

      if (strlen($value) !== 11) {
         return $value;
      }

      return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $value);
   }
}
