<?php

namespace App\Observers;

use App\Jobs\GeocodificarFamiliaJob;
use App\Models\Familia;

class FamiliaObserver
{
    public function created(Familia $familia): void
    {
        GeocodificarFamiliaJob::dispatch($familia->id);
    }

    public function updated(Familia $familia): void
    {
        if ($familia->wasChanged(['endereco', 'numero_casa', 'bairro', 'cep', 'cidade'])) {
            $familia->updateQuietly(['latitude' => null, 'longitude' => null]);
            GeocodificarFamiliaJob::dispatch($familia->id);
        }
    }
}
