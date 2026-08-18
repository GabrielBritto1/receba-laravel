<?php

namespace App\Jobs;

use App\Models\Familia;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodificarFamiliaJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 10;

    public function __construct(public readonly int $familiaId) {}

    public function handle(): void
    {
        $familia = Familia::find($this->familiaId);

        if (! $familia) {
            return;
        }

        $resultado = config('services.google.maps_key')
            ? $this->geocodificarGoogle($familia)
            : $this->geocodificarNominatim($familia);

        if ($resultado) {
            $familia->updateQuietly([
                'latitude'  => (float) $resultado['lat'],
                'longitude' => (float) $resultado['lng'],
            ]);
        } else {
            Log::warning("Geocodificação sem resultado para família #{$familia->id}: {$familia->endereco}, {$familia->cidade}");
        }
    }

    private function geocodificarGoogle(Familia $familia): ?array
    {
        $numero = $this->normalizarNumero($familia->numero_casa);
        $cidade = $familia->cidade ?: 'Alegre';

        $endereco = implode(', ', array_filter([
            $familia->endereco,
            $numero,
            $familia->bairro,
            $cidade,
            $familia->cep,
            'ES',
            'Brasil',
        ]));

        $response = Http::timeout(10)->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address'    => $endereco,
            'components' => 'country:BR|administrative_area:ES',
            'key'        => config('services.google.maps_key'),
        ]);

        $dados = $response->json();

        if (($dados['status'] ?? '') === 'OK' && ! empty($dados['results'][0]['geometry']['location'])) {
            return $dados['results'][0]['geometry']['location'];
        }

        return null;
    }

    private function geocodificarNominatim(Familia $familia): ?array
    {
        $numero = $this->normalizarNumero($familia->numero_casa);
        $cidade = $familia->cidade ?: 'Alegre';

        $tentativas = [];

        if ($numero && $familia->cep) {
            $tentativas[] = [
                'street'     => trim($familia->endereco . ', ' . $numero),
                'city'       => $cidade,
                'state'      => 'Espírito Santo',
                'postalcode' => $familia->cep,
            ];
        }

        if ($numero) {
            $tentativas[] = [
                'street' => trim($familia->endereco . ', ' . $numero),
                'city'   => $cidade,
                'state'  => 'Espírito Santo',
            ];
        }

        $tentativas[] = [
            'street' => $familia->endereco,
            'city'   => $cidade,
            'state'  => 'Espírito Santo',
        ];

        if ($familia->bairro) {
            $tentativas[] = [
                'street' => $familia->bairro,
                'city'   => $cidade,
                'state'  => 'Espírito Santo',
            ];
        }

        if ($familia->cep) {
            $tentativas[] = [
                'postalcode' => $familia->cep,
                'country'    => 'Brasil',
            ];
        }

        $tentativas[] = [
            'city'  => $cidade,
            'state' => 'Espírito Santo',
        ];

        foreach ($tentativas as $params) {
            $response = Http::withHeaders([
                'User-Agent' => 'RECeBa-Sistema/1.0 (sistema de doacao de cestas basicas)',
            ])->timeout(10)->get('https://nominatim.openstreetmap.org/search', array_merge($params, [
                'format'       => 'json',
                'limit'        => 1,
                'countrycodes' => 'br',
                'country'      => 'Brasil',
            ]));

            $dados = $response->json();

            if (! empty($dados[0]['lat']) && ! empty($dados[0]['lon'])) {
                return ['lat' => $dados[0]['lat'], 'lng' => $dados[0]['lon']];
            }

            usleep(1_100_000);
        }

        return null;
    }

    private function normalizarNumero(?string $numero): ?string
    {
        if (! $numero) {
            return null;
        }

        $lower = strtolower(trim($numero));

        if (in_array($lower, ['s/n', 'sn', 's.n', 's/nº', 'sem numero', 'sem número', 's/numero'])) {
            return null;
        }

        return $numero;
    }
}
