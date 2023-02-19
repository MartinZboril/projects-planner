<?php

namespace App\View\Components\Rate;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Rate;

class Table extends Component
{
    public $rates;
    public $tableId;

    public function __construct(Collection $rates, string $tableId)
    {
        $this->rates = $rates->each(function (Rate $rate) {
            $rate->edit_route = route('users.rates.edit', ['user' => $rate->user, 'rate' => $rate]);
            $rate->active = $rate->is_active ? 'Yes' : 'No';
        });
        $this->tableId = $tableId;
    }

    public function render()
    {
        return view('components.rate.table');
    }
}
