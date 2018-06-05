<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Models\Reply;
use App\Http\Requests\Api\ReplyRequest;
use App\Models\User;
use App\Transformers\ReplyTransformer;
use Dingo\Api\Http\Response;

/**
 * Class RepliesController
 * @package App\Http\Controllers\Api
 */
class RepliesController extends Controller
{
    /**
     * @param ReplyRequest $request
     * @param Topic $topic
     * @param Reply $reply
     * @return Response
     */
    public function store ( ReplyRequest $request, Topic $topic, Reply $reply )
    {
        $reply->content  = $request->get('content');
        $reply->topic_id = $topic->id;
        $reply->user_id  = $this->user()->id;
        $reply->save();

        return $this->response->item($reply, new ReplyTransformer())->setStatusCode(201);
    }

    /**
     * @param Topic $topic
     * @param Reply $reply
     * @return \Dingo\Api\Http\Response|void
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy ( Topic $topic, Reply $reply )
    {
        if ( $reply->topic_id != $topic->id ) {
            return $this->response->errorBadRequest();
        }

        $this->authorize('destroy', $reply);
        $reply->delete();

        return $this->response->noContent();
    }

    /**
     * @param Topic $topic
     * @return Response
     */
    public function index( Topic $topic)
    {
        $replies = $topic->replies()->paginate(20);

        return $this->response->paginator($replies, new ReplyTransformer());
    }

    /**
     * @param User $user
     * @return Response
     */
    public function userIndex( User $user)
    {
        $replies = $user->replies()->paginate(20);

        return $this->response->paginator($replies, new ReplyTransformer());
    }
}