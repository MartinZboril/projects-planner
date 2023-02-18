<?php

namespace App\View\Components\Comment;

use Illuminate\View\Component;

class Card extends Component
{
    public $comments;
    public $parent;
    public $storeFormRoute;
    public $updateFormRouteName;
    public $displayHeader;

    public function __construct($comments, $parent, $storeFormRoute, $updateFormRouteName, $displayHeader)
    {
        $this->comments = $comments;
        $this->parent = $parent;
        $this->storeFormRoute = $storeFormRoute;
        $this->updateFormRouteName = $updateFormRouteName;
        $this->displayHeader = $displayHeader;
    }

    public function render()
    {
        return view('components.comment.card');
    }
}
