@extends('adminlte::page')

@section('title', 'Preview do E-mail de Solicitação')

@section('content_header')
<h1 class="m-0">
    <i class="fas fa-envelope-open-text text-success mr-2"></i>
    Preview do E-mail de Notificação
</h1>
<small class="text-muted">Visualização do e-mail enviado ao registrar uma solicitação</small>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <!-- Barra de informações do envio -->
        <div class="callout callout-info mb-3">
            <h5 class="mb-2"><i class="fas fa-paper-plane mr-1"></i> Dados do disparo</h5>
            <div class="row">
                <div class="col-md-3">
                    <strong>Destinatário:</strong><br>
                    <span class="text-muted">fpsimao@ifes.edu.br</span>
                </div>
                <div class="col-md-3">
                    <strong>Remetente:</strong><br>
                    <span class="text-muted">{{ config('mail.from.address') }}</span>
                </div>
                <div class="col-md-3">
                    <strong>Assunto:</strong><br>
                    <span class="text-muted">
                        Nova Solicitação de {{ $solicitacao->tipo === 'cesta' ? 'Cesta Básica' : 'Item' }} — RECeBa
                    </span>
                </div>
                <div class="col-md-3">
                    <strong>Solicitante:</strong><br>
                    <span class="text-muted">{{ $solicitante->name }}</span>
                </div>
            </div>
        </div>

        <!-- Card com o preview emoldurado -->
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-eye mr-1"></i>
                    Visualização do e-mail
                    <span class="badge badge-{{ $solicitacao->tipo === 'cesta' ? 'success' : 'warning' }} ml-2">
                        Solicitação #{{ $solicitacao->id }} — {{ ucfirst($solicitacao->tipo) }}
                    </span>
                </h3>
                <div class="card-tools">
                    <a href="{{ route('email.preview.raw.solicitacao', ['id' => $solicitacao->id]) }}"
                       target="_blank"
                       class="btn btn-tool btn-sm text-muted"
                       title="Abrir HTML puro em nova aba">
                        <i class="fas fa-external-link-alt"></i> HTML puro
                    </a>
                </div>
            </div>
            <div class="card-body p-0" style="background:#f4f6f9;">
                <iframe
                    id="email-preview-frame"
                    src="{{ route('email.preview.raw.solicitacao', ['id' => $solicitacao->id]) }}"
                    style="width:100%;border:none;min-height:680px;display:block;"
                    onload="autoResizeIframe(this)"
                ></iframe>
            </div>
        </div>

    </div>
</div>
@endsection

@section('js')
<script>
function autoResizeIframe(iframe) {
    try {
        const h = iframe.contentWindow.document.documentElement.scrollHeight;
        iframe.style.height = (h + 30) + 'px';
    } catch(e) {}
}
</script>
@endsection
