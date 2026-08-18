<?php

namespace App\Console\Commands;

use App\Models\Familia;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GeocodificarFamilias extends Command
{
    protected $signature = 'familias:geocodificar {--force : Regeocodificar famílias que já têm coordenadas}';
    protected $description = 'Busca coordenadas geográficas das famílias via Nominatim (OpenStreetMap)';

    public function handle(): int
    {
        $query = Familia::query();

        if (! $this->option('force')) {
            $query->where(function ($q) {
                $q->whereNull('latitude')->orWhereNull('longitude');
            });
        }

        if ($this->option('force')) {
            Familia::query()->update(['latitude' => null, 'longitude' => null]);
            $this->info('Coordenadas zeradas. Iniciando regeocodificação...');
        }

        $familias = $query->get();

        if ($familias->isEmpty()) {
            $this->info('Nenhuma família pendente de geocodificação.');
            return self::SUCCESS;
        }

        $this->info("Geocodificando {$familias->count()} família(s)...");
        $bar = $this->output->createProgressBar($familias->count());
        $bar->start();

        $ok = 0;
        $falhas = 0;

        $usaGoogle = (bool) config('services.google.maps_key');
        $this->info($usaGoogle ? 'Usando Google Maps API.' : 'Usando Nominatim (OpenStreetMap).');

        foreach ($familias as $familia) {
            try {
                $resultado = $usaGoogle
                    ? $this->geocodificarGoogle($familia)
                    : $this->geocodificarComFallback($familia);

                if ($resultado) {
                    $familia->update([
                        'latitude'  => (float) $resultado['lat'],
                        'longitude' => (float) ($resultado['lon'] ?? $resultado['lng']),
                    ]);
                    $ok++;
                } else {
                    $this->newLine();
                    $this->warn("Sem resultado: família #{$familia->id} ({$familia->endereco}, {$familia->cidade})");
                    $falhas++;
                }
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Erro família #{$familia->id}: {$e->getMessage()}");
                $falhas++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Concluído: {$ok} geocodificada(s), {$falhas} sem resultado.");

        return self::SUCCESS;
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

    private function geocodificarComFallback(Familia $familia): ?array
    {
        $numero = $this->normalizarNumero($familia->numero_casa);
        $cidade = $familia->cidade ?: 'Alegre';

        $tentativas = [];

        // 1. Rua + número + CEP (mais preciso)
        if ($numero && $familia->cep) {
            $tentativas[] = [
                'street'     => trim($familia->endereco . ', ' . $numero),
                'city'       => $cidade,
                'state'      => 'Espírito Santo',
                'postalcode' => $familia->cep,
            ];
        }

        // 2. Rua + número + cidade + estado
        if ($numero) {
            $tentativas[] = [
                'street' => trim($familia->endereco . ', ' . $numero),
                'city'   => $cidade,
                'state'  => 'Espírito Santo',
            ];
        }

        // 3. Rua + cidade + estado (sem número)
        $tentativas[] = [
            'street' => $familia->endereco,
            'city'   => $cidade,
            'state'  => 'Espírito Santo',
        ];

        // 4. Bairro + cidade + estado
        if ($familia->bairro) {
            $tentativas[] = [
                'street' => $familia->bairro,
                'city'   => $cidade,
                'state'  => 'Espírito Santo',
            ];
        }

        // 5. Só CEP
        if ($familia->cep) {
            $tentativas[] = [
                'postalcode' => $familia->cep,
                'country'    => 'Brasil',
            ];
        }

        // 6. Só cidade + estado (último recurso)
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
                return $dados[0];
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
