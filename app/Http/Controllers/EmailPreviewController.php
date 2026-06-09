<?php

namespace App\Http\Controllers;

use App\Models\Solicitacao;

class EmailPreviewController extends Controller
{
    public function preview(?int $id = null)
    {
        abort_unless(auth()->user()?->hasRole('Administrador'), 403);

        [$solicitacao, $solicitante] = $this->resolvePreviewData($id);

        if (!$solicitacao) {
            return response('Nenhuma solicitação encontrada para preview.', 404);
        }

        return view('emails.preview', compact('solicitacao', 'solicitante'));
    }

    public function raw(?int $id = null)
    {
        abort_unless(auth()->user()?->hasRole('Administrador'), 403);

        [$solicitacao, $solicitante] = $this->resolvePreviewData($id);

        if (!$solicitacao) {
            return response('Nenhuma solicitação encontrada para preview.', 404);
        }

        return view('emails.solicitacao', compact('solicitacao', 'solicitante'));
    }

    private function resolvePreviewData(?int $id): array
    {
        if ($id) {
            $solicitacao = Solicitacao::with(['parceiro', 'item'])->findOrFail($id);
        } else {
            $solicitacao = Solicitacao::with(['parceiro', 'item'])->latest()->first();
        }

        $solicitante = $solicitacao
            ? ($solicitacao->parceiro?->users()->first() ?? auth()->user())
            : auth()->user();

        return [$solicitacao, $solicitante];
    }
}
