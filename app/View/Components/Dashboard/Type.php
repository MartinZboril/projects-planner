<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class Type extends Component
{
    public $type;
    public $icon;
    
    public function __construct($type)
    {
        $this->type = __('pages.title.' . $type);
        switch($type) {
            case('client'):
                $this->icon = 'fas fa-address-book';
                break;

            case('project'):
                $this->icon = 'fas fa-clock';
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
