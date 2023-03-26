<?php

namespace App\View\Components\Dashboard;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Enums\ProjectStatusEnum;
use App\Models\Project;

class Listing extends Component
{
    public $items;
    public $title;
    public $type;

    public function __construct(Collection $items, string $title, string $type)
    {
        $items->each(function (Project $item) use($type) {
            if ($type === 'project') {
                $checkRoute = route('projects.change_status', $item);
                $finishStatus = ProjectStatusEnum::finish;
                // assign routes to items
                $item->check_action = "onclick=\"changeProjectStatus('{$checkRoute}', {$finishStatus->value}, 'list', '', '', '')\"";
                $item->edit_route = route('projects.edit', $item);
            }
        });
        $this->items = $items;
        $this->title = $title;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.dashboard.listing');
    }
}
