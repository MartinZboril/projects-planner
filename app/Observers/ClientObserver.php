<?php

namespace App\Observers;

use App\Models\Client;
use App\Models\File;

class ClientObserver
{
    /**
     * Handle the Client "deleted" event.
     */
    public function deleted(Client $client): void
    {
        $client->logo()->delete();
        $client->notes()->delete();
        $client->files()->delete();
        File::where('fileable_type', 'App\Models\Comment')->whereIn('fileable_id', array_column($client->comments->toArray(), 'id'))->delete();
        $client->comments()->delete();
    }
}
