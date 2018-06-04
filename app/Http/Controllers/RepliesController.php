<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Requests\ReplyRequest;
use Auth;

/**
 * Class RepliesController
 * @package App\Http\Controllers
 */
class RepliesController extends Controller
{
    /**
     * RepliesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param ReplyRequest $request
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store( ReplyRequest $request, Reply $reply)
    {
        $reply->content = $request->get('content');
        $reply->user_id = Auth::id();
        $reply->topic_id = $request->topic_id;
        $reply->save();

        return redirect()->to($reply->topic->link())->with('success', '创建成功！');
    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy( Reply $reply)
    {
        $this->authorize('destroy', $reply);
        $reply->delete();

        return redirect()->to($reply->topic->link())->with('success', '成功删除回复！');
    }
}