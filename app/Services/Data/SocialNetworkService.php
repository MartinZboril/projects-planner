<?php

namespace App\Services\Data;

use App\Models\SocialNetwork;

class SocialNetworkService
{
    /**
     * Save data for social network.
     */
    public function handleSave(SocialNetwork $socialNetwork, array $inputs): int
    {
        $socialNetwork->fill($inputs)->save();

        return $socialNetwork->id;
    }
}
