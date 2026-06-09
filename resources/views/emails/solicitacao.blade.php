<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Solicitação — RECeBa</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f6f9;font-family:'Segoe UI',Arial,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f4f6f9;">
  <tr>
    <td align="center" style="padding:30px 10px;">

      <!-- Container principal -->
      <table width="620" cellpadding="0" cellspacing="0" border="0" style="max-width:620px;width:100%;background-color:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.10);">

        <!-- CABEÇALHO com logo -->
        <tr>
          <td style="background-color:#1a7a3c;padding:28px 32px;text-align:center;">
            @isset($message)
            <img src="{{ $message->embed(public_path('assets/img/banner_horizontal.png')) }}"
                 alt="RECeBa — Registro de Entrega de Cestas Básicas"
                 style="max-width:320px;width:100%;height:auto;display:block;margin:0 auto;">
            @else
            <img src="{{ asset('assets/img/banner_horizontal.png') }}"
                 alt="RECeBa — Registro de Entrega de Cestas Básicas"
                 style="max-width:320px;width:100%;height:auto;display:block;margin:0 auto;">
            @endisset
          </td>
        </tr>

        <!-- FAIXA DO TIPO DE SOLICITAÇÃO -->
        <tr>
          <td style="background-color:#218f45;padding:12px 32px;text-align:center;">
            <span style="color:#ffffff;font-size:14px;font-weight:600;letter-spacing:1px;text-transform:uppercase;">
              {{ $solicitacao->tipo === 'cesta' ? '🧺 Nova Solicitação de Cesta Básica' : '📦 Nova Solicitação de Item' }}
            </span>
          </td>
        </tr>

        <!-- CORPO DO EMAIL -->
        <tr>
          <td style="padding:36px 36px 24px;">

            <p style="margin:0 0 24px;font-size:15px;color:#444444;line-height:1.6;">
              Uma nova solicitação foi registrada no sistema <strong>RECeBa</strong>. Confira os detalhes abaixo:
            </p>

            <!-- Tabela de informações -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:separate;border-spacing:0 8px;">

              <!-- Solicitante -->
              <tr>
                <td style="background-color:#f8faf9;border-left:4px solid #1a7a3c;padding:12px 16px;border-radius:0 6px 6px 0;width:42%;">
                  <span style="font-size:11px;color:#888888;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:2px;">Solicitante</span>
                  <span style="font-size:15px;color:#222222;font-weight:600;">{{ $solicitante->name }}</span>
                </td>
                <td style="width:12px;"></td>
                <td style="background-color:#f8faf9;border-left:4px solid #1a7a3c;padding:12px 16px;border-radius:0 6px 6px 0;">
                  <span style="font-size:11px;color:#888888;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:2px;">E-mail do Solicitante</span>
                  <span style="font-size:15px;color:#222222;font-weight:600;">{{ $solicitante->email }}</span>
                </td>
              </tr>

              <tr><td colspan="3" style="height:4px;"></td></tr>

              <!-- Parceiro -->
              <tr>
                <td colspan="3" style="background-color:#f8faf9;border-left:4px solid #1a7a3c;padding:12px 16px;border-radius:0 6px 6px 0;">
                  <span style="font-size:11px;color:#888888;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:2px;">Parceiro</span>
                  <span style="font-size:15px;color:#222222;font-weight:600;">{{ $solicitacao->parceiro->name }}</span>
                </td>
              </tr>

              <tr><td colspan="3" style="height:4px;"></td></tr>

              @if($solicitacao->tipo === 'item' && $solicitacao->item)
              <!-- Item solicitado -->
              <tr>
                <td colspan="3" style="background-color:#fff8e1;border-left:4px solid #f59e0b;padding:12px 16px;border-radius:0 6px 6px 0;">
                  <span style="font-size:11px;color:#888888;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:2px;">Item Solicitado</span>
                  <span style="font-size:15px;color:#222222;font-weight:600;">{{ $solicitacao->item->nome }}</span>
                </td>
              </tr>
              <tr><td colspan="3" style="height:4px;"></td></tr>
              @endif

              <!-- Quantidade e datas -->
              <tr>
                <td style="background-color:#f8faf9;border-left:4px solid #1a7a3c;padding:12px 16px;border-radius:0 6px 6px 0;">
                  <span style="font-size:11px;color:#888888;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:2px;">Quantidade Solicitada</span>
                  <span style="font-size:22px;color:#1a7a3c;font-weight:700;">{{ $solicitacao->quantidade_solicitada }}</span>
                  <span style="font-size:13px;color:#666666;"> {{ $solicitacao->tipo === 'cesta' ? 'cestas' : 'unidades' }}</span>
                </td>
                <td style="width:12px;"></td>
                <td style="background-color:#f8faf9;border-left:4px solid #1a7a3c;padding:12px 16px;border-radius:0 6px 6px 0;">
                  <span style="font-size:11px;color:#888888;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:2px;">Status</span>
                  <span style="display:inline-block;background-color:#e0f2e9;color:#1a7a3c;font-size:13px;font-weight:700;padding:4px 12px;border-radius:20px;">Em Análise</span>
                </td>
              </tr>

              <tr><td colspan="3" style="height:4px;"></td></tr>

              <!-- Datas -->
              <tr>
                <td style="background-color:#f8faf9;border-left:4px solid #3b82f6;padding:12px 16px;border-radius:0 6px 6px 0;">
                  <span style="font-size:11px;color:#888888;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:2px;">Data do Pedido</span>
                  <span style="font-size:15px;color:#222222;font-weight:600;">{{ $solicitacao->created_at->format('d/m/Y') }}</span>
                  <span style="font-size:12px;color:#888888;"> às {{ $solicitacao->created_at->format('H:i') }}</span>
                </td>
                <td style="width:12px;"></td>
                <td style="background-color:#f8faf9;border-left:4px solid #3b82f6;padding:12px 16px;border-radius:0 6px 6px 0;">
                  <span style="font-size:11px;color:#888888;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;display:block;margin-bottom:2px;">Previsão de Entrega</span>
                  <span style="font-size:15px;color:#222222;font-weight:600;">{{ $solicitacao->data_previsao_entrega->format('d/m/Y') }}</span>
                  <span style="font-size:12px;color:#888888;"> às {{ $solicitacao->data_previsao_entrega->format('H:i') }}</span>
                </td>
              </tr>

            </table>

            <p style="margin:28px 0 0;font-size:13px;color:#888888;line-height:1.6;border-top:1px solid #eeeeee;padding-top:20px;">
              Este é um e-mail automático gerado pelo sistema <strong>RECeBa</strong>. Por favor, não responda a este e-mail.
            </p>

          </td>
        </tr>

        <!-- RODAPÉ com redes sociais -->
        <tr>
          <td style="background-color:#1a7a3c;padding:22px 32px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td style="color:#d4f0de;font-size:12px;vertical-align:middle;">
                  © {{ date('Y') }} RECeBa — IFES<br>
                  <span style="color:#a3d9b8;font-size:11px;">Registro de Entrega de Cestas Básicas</span>
                </td>
                <td align="right" style="vertical-align:middle;">

                  <!-- Instagram -->
                  <a href="https://www.instagram.com/recebaifes" target="_blank" style="display:inline-block;margin-left:12px;text-decoration:none;">
                    <table cellpadding="0" cellspacing="0" border="0" style="display:inline-table;">
                      <tr>
                        <td style="background-color:#ffffff;border-radius:50%;width:36px;height:36px;text-align:center;vertical-align:middle;">
                          <!-- Instagram SVG -->
                          <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iI0U0NDA1RiIgZD0iTTEyIDIuMTYzYzMuMjA0IDAgMy41ODQuMDEyIDQuODUuMDcgMy4yNTIuMTQ4IDQuNzcxIDEuNjkxIDQuOTE5IDQuOTE5LjA1OCAxLjI2NS4wNjkgMS42NDUuMDY5IDQuODQ5IDAgMy4yMDUtLjAxMiAzLjU4NC0uMDY5IDQuODQ5LS4xNDkgMy4yMjUtMS42NjggNC43NjQtNC45MTkgNC45MTktMS4yNjYuMDU4LTEuNjQ0LjA3LTQuODUuMDctMy4yMDQgMC0zLjU4NC0uMDEyLTQuODQ5LS4wNy0zLjI2LS4xNDktNC43NzEtMS42OTktNC45MTktNC45MTktLjA1OC0xLjI2NS0uMDctMS42NDQtLjA3LTQuODQ5IDAtMy4yMDQuMDEzLTMuNTgzLjA3LTQuODQ5LjE0OS0zLjIyNyAxLjY2NC00Ljc3MSA0LjkxOS00LjkxOSAxLjI2Ni0uMDU3IDEuNjQ1LS4wNjkgNC44NDktLjA2OXptMC0yLjE2M2MtMy4yNTkgMC0zLjY2Ny4wMTQtNC45NDcuMDcyLTQuMzU4LjItNi43OCAyLjYxOC02Ljk4IDYuOTgtLjA1OSAxLjI4MS0uMDczIDEuNjg5LS4wNzMgNC45NDggMCAzLjI1OS4wMTQgMy42NjguMDcyIDQuOTQ4LjIgNC4zNTggMi42MTggNi43OCA2Ljk4IDYuOTggMS4yODEuMDU4IDEuNjg5LjA3MiA0Ljk0OC4wNzIgMy4yNTkgMCAzLjY2OC0uMDE0IDQuOTQ4LS4wNzIgNC4zNTQtLjIgNi43ODItMi42MTggNi45NzktNi45OC4wNTktMS4yOC4wNzMtMS42ODkuMDczLTQuOTQ4IDAtMy4yNTktLjAxNC0zLjY2Ny0uMDcyLTQuOTQ3LS4xOTYtNC4zNTQtMi42MTctNi43OC02Ljk3OS02Ljk4LTEuMjgxLS4wNTktMS42OS0uMDczLTQuOTQ5LS4wNzN6bTAgNS44MzhjLTMuNDAzIDAtNi4xNjIgMi43NTktNi4xNjIgNi4xNjJTOC41OTcgMTguMTYyIDEyIDE4LjE2MnM2LjE2Mi0yLjc1OSA2LjE2Mi02LjE2MlMxNS40MDMgNS44MzggMTIgNS44Mzh6bTAgMTAuMTYyYy0yLjIwOSAwLTQtMS43OTEtNC00czEuNzkxLTQgNC00IDQgMS43OTEgNCA0LTEuNzkxIDQtNCA0em02LjQwNi0xMS44NDVjLS43OTYgMC0xLjQ0MS42NDUtMS40NDEgMS40NHMuNjQ1IDEuNDQgMS40NDEgMS40NGMuNzk1IDAgMS40NC0uNjQ1IDEuNDQtMS40NHMtLjY0NS0xLjQ0LTEuNDQtMS40NHoiLz48L3N2Zz4=" alt="Instagram" width="22" height="22" style="display:block;margin:auto;">
                        </td>
                      </tr>
                    </table>
                  </a>

                  <!-- WhatsApp -->
                  <a href="https://wa.me/5527999999999" target="_blank" style="display:inline-block;margin-left:12px;text-decoration:none;">
                    <table cellpadding="0" cellspacing="0" border="0" style="display:inline-table;">
                      <tr>
                        <td style="background-color:#ffffff;border-radius:50%;width:36px;height:36px;text-align:center;vertical-align:middle;">
                          <!-- WhatsApp SVG -->
                          <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZmlsbD0iIzI1RDM2NiIgZD0iTTEyLjA0IDJjLTUuNDYgMC05LjkxIDQuNDUtOS45MSA5LjkxIDAgMS43NS40NiAzLjM4IDEuMjUgNC44TDIgMjJsNS40LTEuNDFjMS4zNy43MyAyLjkyIDEuMTUgNC41NiAxLjE1IDUuNDYgMCA5LjkxLTQuNDUgOS45MS05LjkxIDAtMi42NS0xLjAzLTUuMTQtMi45LTcuMDFBOS44OTIgOS44OTIgMCAwIDAgMTIuMDQgMnptMCAxLjgxYzIuMTYgMCA0LjE5Ljg0IDUuNzIgMi4zNyAxLjUyIDEuNTMgMi4zNiAzLjU2IDIuMzYgNS43MyAwIDQuNDYtMy42MyA4LjA5LTguMDkgOC4wOS0xLjM5IDAtMi43Ni0uMzctMy45Ni0xLjA3bC0uMjktLjE3LTIuOTguNzguNzktMi45LS4xOC0uMzFhNy45OCA3Ljk4IDAgMCAxLTEuMTYtNC4xM2MuMDEtNC40NiAzLjY0LTguMDkgOC4wOS04LjA5em0tMi44NSA0LjFjLS4xNiAwLS40MS4wNi0uNjMuMjktLjIyLjIzLS44NC44Mi0uODQgMi4wMSAwIDEuMTkuODYgMi4zNC45OCAyLjUxLjEyLjE2IDEuNjggMi43IDQuMDggMy42OC40MDQuMTc1Ljcxby4yNDQuOTMuMjQ0IDEuNDctLjEgMi4yNi0uNjYgMi42LTEuMjkuMzMtLjYzLjMzLTEuMTcuMjMtMS4yOC0uMS0uMTItLjM2LS4xOS0uNzYtLjM4LS40LS4xOS0yLjM3LTEuMTctMi43My0xLjMtLjM2LS4xNS0uNjMtLjIyLS44OS4yMi0uMjYuNDQtMS4wMSAxLjMtMS4yMiAxLjU2cy0uNDUuMjgtLjgzLjA5Yy0uMzgtLjE5LTEuNjEtLjU5LTMuMDYtMS44OC0xLjEzLS45OS0xLjktMi4yMy0yLjEyLTIuNjEtLjIyLS4zNy0uMDItLjU4LjE2LS43Ny4xNy0uMTcuMzgtLjQ0LjU2LS42Ni4xOS0uMjIuMjYtLjM3LjM4LS42My4xMy0uMjUuMDYtLjQ3LS4wMy0uNjYtLjEtLjE5LS44OS0yLjE0LTEuMjMtMi45M3oiLz48L3N2Zz4=" alt="WhatsApp" width="22" height="22" style="display:block;margin:auto;">
                        </td>
                      </tr>
                    </table>
                  </a>

                </td>
              </tr>
            </table>
          </td>
        </tr>

      </table>
      <!-- /Container principal -->

    </td>
  </tr>
</table>

</body>
</html>
