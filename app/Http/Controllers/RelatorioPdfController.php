<?php

namespace App\Http\Controllers;

use App\Models\Parceiro;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioPdfController extends Controller
{
   public function relatorio_saida_de_cesta()
   {
      $pdf = Pdf::loadView('relatorios.relatorios_pdf.relatorio_saida_de_cesta_pdf')->setPaper('a4', 'landscape');
      return $pdf->stream('relatorio_saida_de_cesta_pdf.pdf');
   }

   public function relatorio_parceiro()
   {
      $parceiros = Parceiro::all();
      $pdf = Pdf::loadView('relatorios.relatorios_pdf.relatorio_parceiro_pdf', compact('parceiros'))->setPaper('a4', 'landscape');
      return $pdf->stream('relatorio_parceiro_pdf.pdf');
   }
}
