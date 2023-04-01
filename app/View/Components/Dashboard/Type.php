<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class Type extends Component
{
    public $type;
    public $displayIcon;
    public $icon;
    
    public function __construct(string $type, ?bool $displayIcon=true)
    {
        $this->type = __('pages.title.' . $type);
        $this->displayIcon = $displayIcon;
        
        switch($type) {
            case('client'):
                $this->icon = 'fas fa-address-book';
                break;

            case('project'):
                $this->icon = 'fas fa-clock';
                break;
                
            case('milestone'):
                $this->icon = 'far fa-calendar-times';
                break;

            case('task'):
                $this->icon = 'fas fa-tasks';
                break;
                
            case('ticket'):
                $this->icon = 'fas fa-life-ring';
                break;
        
            case('todo'):
                $this->icon = 'fas fa-check-square';
                break;
        
            default:
                $this->icon = 'far fa-list';
        }
    }

    public function render()
    {
        return view('components.dashboard.type');
    }
}
