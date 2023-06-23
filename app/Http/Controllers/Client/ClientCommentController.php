<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Requests\Comment\UpdateCommentRequest;
use App\Models\Client;
use App\Models\Comment;
use App\Services\Data\ClientService;
use App\Services\Data\CommentService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ClientCommentController extends Controller
{
    use FlashTrait;

    public function __construct(
        private ClientService $clientService,
        private CommentService $commentService
    ) {
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
            $this->commentService->handleSave(new Comment, $request->validated(), $client, $request->file('files'));
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
            $this->commentService->handleSave($comment, $request->validated(), $client, $request->file('files'));
            $this->flash(__('messages.comment.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('clients.comments.index', $client);
    }

    /**
     * Remove the clients comment from storage.
     */
    public function destroy(Client $client, Comment $comment): JsonResponse
    {
        try {
            $this->commentService->handleDelete($comment);
        } catch (Exception $exception) {
            Log::error($exception);
        }

        return response()->json([
            'message' => __('messages.comment.delete'),
        ]);
    }
}
