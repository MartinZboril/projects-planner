<?php

namespace App\View\Components\Project\UI;

use Illuminate\View\Component;
use App\Enums\ProjectStatusEnum;

class StatusBadge extends Component
{
    public $title;
    public $background;
    public $text;

    public function __construct(ProjectStatusEnum $status, string $text)
    {
        $this->text = $text;
        switch($status) {
            case(ProjectStatusEnum::active):
                $this->title = __('pages.content.projects.statuses.active');
                $this->background = 'info';
                break;
                
            case(ProjectStatusEnum::finish):
                $this->title = __('pages.content.projects.statuses.finish');
                $this->background = 'success';
                break;
    
            case(ProjectStatusEnum::archive):
                $this->title = __('pages.content.projects.statuses.archive');
                $this->background = 'primary';
                break;
    
            default:
                $this->title = 'NaN';
                $this->background = 'info';
        }
    }

    public function render()
    {
        return view('components.project.ui.status-badge');
    }
}
