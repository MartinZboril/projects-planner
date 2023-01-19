<?php

namespace App\Services\Data;

use App\Models\CommentFile;
use App\Services\FileService;
use App\Services\RouteService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\ValidatedInput;
use App\Enums\Routes\{ClientRouteEnum};
use App\Models\{Client, ClientComment, Comment};

class CommentService
{
    /**
     * Store new comment.
     */
    public function store(ValidatedInput $inputs, ?Array $uploadedFiles): Comment
    {
        $comment = new Comment;
        $comment->user_id = Auth::id();

        $comment = $this->save($comment, $inputs);

        if ($parentId = $inputs->parent_id) {
            $this->saveRelation($comment, $parentId, $inputs->type);
        }

        if ($uploadedFiles) {
            $this->storeFiles($comment, $uploadedFiles);
        }

        return $comment;
    }

    /**
     * Update comment.
     */
    public function update(Comment $comment, ValidatedInput $inputs, ?Array $uploadedFiles): Comment
    {
        $comment = $this->save($comment, $inputs);

        if ($uploadedFiles) {
            $this->storeFiles($comment, $uploadedFiles);
        }

        return $comment;
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
     * Store comments files.
     */
    public function storeFiles(Comment $comment, Array $uploadedFiles): void
    {
        foreach ($uploadedFiles as $uploadedFile) {
            CommentFile::create([
                'comment_id' => $comment->id,
                'file_id' => ((new FileService)->upload($uploadedFile, 'clients/comments'))->id
            ]);
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