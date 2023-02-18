<?php

namespace App\View\Components\Comment;

use Illuminate\View\Component;

class Fields extends Component
{
    public $comment;
    public $type;

    public function __construct($comment, $type)
    {
        $this->comment = $comment;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.comment.fields');
    }
}
