<?php

namespace App\Services\Data;

use App\Models\Address;

class AddressService
{
    /**
     * Save data for address.
     */
    public function handleSave(Address $address, array $inputs)
    {
        $address->fill($inputs)->save();
        return $address->id;
    }
}