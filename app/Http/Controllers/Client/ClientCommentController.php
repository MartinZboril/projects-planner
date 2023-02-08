<?php

namespace App\Http\Controllers\Client;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\{StoreCommentRequest, UpdateCommentRequest};
use App\Models\{Comment, Client};
use App\Services\FlashService;
use App\Services\Data\{ClientService, CommentService};

class ClientCommentController extends Controller
{
    /**
     * Display the comments of client.
     */
    public function index(Client $client)
    {
        return view('clients.comments.index', ['client' => $client, 'comment' => new Comment]);
    }

    /**
     * Store a newly created clients comment in storage.
     */
    public function store(StoreCommentRequest $request, Client $client)
    {
        try {
            (new ClientService)->handleSaveComment(
                $client,
                (new CommentService)->save(new Comment, $request->safe(), $request->file('files'))
            );
            (new FlashService)->flash(__('messages.comment.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('clients.comments.index', [$client]);
    }

    /**
     * Update the specified clients comment in storage.
     */
    public function update(UpdateCommentRequest $request, Client $client, Comment $comment)
    {
        try {
            (new CommentService)->save($comment, $request->safe(), $request->file('files'));
            (new FlashService)->flash(__('messages.comment.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('clients.comments.index', [$client]);
    }
}
