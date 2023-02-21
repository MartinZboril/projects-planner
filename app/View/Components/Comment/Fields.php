<?php

namespace App\View\Components\Comment;

use Illuminate\View\Component;
use App\Models\Comment;

class Fields extends Component
{
    public function __construct(public ?Comment $comment, public string $type)
    {
    }

    public function render()
    {
        return view('components.comment.fields');
    }
}
