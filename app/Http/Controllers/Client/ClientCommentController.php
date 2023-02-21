<?php

namespace App\Http\Controllers\Client;

use Exception;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\{StoreCommentRequest, UpdateCommentRequest};
use App\Models\{Comment, Client};
use App\Traits\FlashTrait;
use App\Services\Data\{ClientService, CommentService};

class ClientCommentController extends Controller
{
    use FlashTrait;

    public function __construct(private ClientService $clientService, private CommentService $commentService)
    {
    }
    
    /**
     * Display the comments of client.
     */
    public function index(Client $client)
    {
        return view('clients.comments.index', ['client' => $client]);
    }

    /**
     * Store a newly created clients comment in storage.
     */
    public function store(StoreCommentRequest $request, Client $client)
    {
        try {
            $this->clientService->handleSaveComment(
                $client,
                $this->commentService->handleSave(new Comment, $request->safe(), $request->file('files'))
            );
            $this->flash(__('messages.comment.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('clients.comments.index', $client);
    }

    /**
     * Update the specified clients comment in storage.
     */
    public function update(UpdateCommentRequest $request, Client $client, Comment $comment)
    {
        try {
            $this->commentService->handleSave($comment, $request->safe(), $request->file('files'));
            $this->flash(__('messages.comment.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);
            return redirect()->back()->with(['error' => __('messages.error')]);
        }
        return redirect()->route('clients.comments.index', $client);
    }
}
