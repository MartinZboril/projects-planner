<?php

namespace App\Services\Data;

use App\Enums\Routes\{ClientRouteEnum};
use App\Models\{Client, ClientComment, Comment};
use App\Services\RouteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    /**
     * Store new comment.
     */
    public function store(ValidatedInput $inputs): Comment
    {
        $comment = new Comment;
        $comment->user_id = Auth::id();

        $comment = $this->save($comment, $inputs);

        if($parentId = $inputs->parent_id) {
            $this->saveRelation($comment, $inputs->parent_id, $inputs->type);
        }

        return $comment;
    }

    /**
     * Update comment.
     */
    public function update(Comment $comment, ValidatedInput $inputs): Comment
    {
        return $this->save($comment, $inputs);
    }

    /**
     * Save data for comment.
     */
    protected function save(Comment $comment, ValidatedInput $inputs): Comment
    {
        $comment->content = $inputs->content;
        $comment->save();

        return $comment;
    }

    protected function saveRelation(Comment $comment, int $parentId, string $type): void
    {
        if ($type == 'client') {
            $clientComment = new ClientComment;
            $clientComment->client_id = $parentId;
            $clientComment->comment_id = $comment->id;
            $clientComment->save(); 
        }
    }

    /**
     * Set up redirect for the action
     */
    public function setUpRedirect(Comment $comment, $type, $parentId): RedirectResponse
    {
        switch ($type) {                        
            case 'client':
                $redirectAction = ClientRouteEnum::Comments;
                $redirectVars = ['client' => Client::find($parentId)];
                break;  

            default:
                return redirect()->back();
                break;
        }
        
        return (new RouteService)->redirect($redirectAction->value, $redirectVars);
    }
}