<?php

namespace App\View\Components\Comment;

use Illuminate\View\Component;

class Card extends Component
{
    public $comments;
    public $parent;
    public $storeFormRoute;
    public $updateFormRouteName;

    public function __construct($comments, $parent, $storeFormRoute, $updateFormRouteName)
    {
        $this->comments = $comments;
        $this->parent = $parent;
        $this->storeFormRoute = $storeFormRoute;
        $this->updateFormRouteName = $updateFormRouteName;
    }

    public function render()
    {
        return view('components.comment.card');
    }
}
