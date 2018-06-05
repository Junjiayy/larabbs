<?php

namespace App\Http\Controllers\Api;

use App\Models\Topic;
use App\Models\User;
use App\Transformers\TopicTransformer;
use App\Http\Requests\Api\TopicRequest;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

/**
 * Class TopicsController
 * @package App\Http\Controllers\Api
 */
class TopicsController extends Controller
{
    /**
     * @param TopicRequest $request
     * @param Topic $topic
     * @return Response
     */
    public function store ( TopicRequest $request, Topic $topic )
    {
        $topic->fill($request->all());
        $topic->user_id = $this->user()->id;
        $topic->save();

        return $this->response->item($topic, new TopicTransformer())->setStatusCode(201);
    }

    /**
     * @param TopicRequest $request
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update ( TopicRequest $request, Topic $topic )
    {
        $this->authorize('update', $topic);

        $topic->update($request->all());
        return $this->response->item($topic, new TopicTransformer());
    }

    /**
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy ( Topic $topic )
    {
        $this->authorize('update', $topic);

        $topic->delete();
        return $this->response->noContent();
    }

    /**
     * @param Request $request
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function index ( Request $request, Topic $topic )
    {
        $query = $topic->query();

        if ( $categoryId = $request->category_id ) {
            $query->where('category_id', $categoryId);
        }

        // 为了说明 N+1问题，不使用 scopeWithOrder
        switch ( $request->order ) {
            case 'recent': $query->recent(); break;
            default: $query->recentReplied(); break;
        }

        $topics = $query->paginate(20);

        return $this->response->paginator($topics, new TopicTransformer());
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function userIndex( User $user, Request $request)
    {
        $topics = $user->topics()->recent()->paginate(20);

        return $this->response->paginator($topics, new TopicTransformer());
    }

    /**
     * @param Topic $topic
     * @return \Dingo\Api\Http\Response
     */
    public function show( Topic $topic)
    {
        return $this->response->item($topic, new TopicTransformer());
    }
}