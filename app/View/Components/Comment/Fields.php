<?php

namespace App\View\Components\Comment;

use Illuminate\View\Component;
use App\Models\Comment;

class Fields extends Component
{
    public $comment;
    public $type;

    public function __construct(?Comment $comment, string $type)
    {
        $this->comment = $comment;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.comment.fields');
    }
}
