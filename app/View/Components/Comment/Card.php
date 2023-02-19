<?php

namespace App\View\Components\Comment;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use App\Models\Comment;

class Card extends Component
{
    public $comments;
    public $storeFormRoute;
    public $displayHeader;

    public function __construct(Collection $comments, array $parent, string $storeFormRoute, string $updateFormRouteName, ?bool $displayHeader=true)
    {
        $this->comments = $comments->each(function (Comment $comment) use($parent, $updateFormRouteName) {
            $comment->update_route = route($updateFormRouteName, $parent + ['comment' => $comment]);
        });
        $this->storeFormRoute = $storeFormRoute;
        $this->displayHeader = $displayHeader;
    }

    public function render()
    {
        return view('components.comment.card');
    }
}
