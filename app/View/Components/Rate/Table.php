<?php

namespace App\View\Components\Rate;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Rate;

class Table extends Component
{
    public function __construct(public Collection $rates, public string $tableId)
    {
        $this->rates->each(function (Rate $rate) {
            $rate->edit_route = route('users.rates.edit', ['user' => $rate->user, 'rate' => $rate]);
            $rate->active = $rate->is_active ? 'Yes' : 'No';
        });
    }

    public function render()
    {
        return view('components.rate.table');
    }
}
