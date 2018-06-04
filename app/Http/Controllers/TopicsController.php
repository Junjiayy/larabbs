<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\TopicRequest;
use Auth;

/**
 * Class TopicsController
 * @package App\Http\Controllers
 */
class TopicsController extends Controller
{
    /**
     * TopicsController constructor.
     */
    public function __construct ()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * @param Request $request
     * @param Topic $topic
     * @param User $user
     * @param Link $link
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index ( Request $request, Topic $topic, User $user, Link $link )
    {
        $topics = $topic->withOrder($request->order)->paginate(20);
        $active_users = $user->getActiveUsers();
        $links = $link->getAllCached();

        return view('topics.index', compact('topics','active_users','links'));
    }

    /**
     * @param Request $request
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show ( Request $request, Topic $topic )
    {
        if ( ! empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }

        return view('topics.show', compact('topic'));
    }

    /**
     * @param Category $category
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create ( Category $category, Topic $topic )
    {
        $categories = $category->all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    /**
     * @param TopicRequest $request
     * @param Topic $topic
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store ( TopicRequest $request, Topic $topic )
    {
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();

        return redirect()->to($topic->link())->with('success', '成功创建话题');
    }

    /**
     * @param Category $category
     * @param Topic $topic
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit ( Category $category, Topic $topic )
    {
        $this->authorize('update', $topic);
        $categories = $category->all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    /**
     * @param TopicRequest $request
     * @param Topic $topic
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update ( TopicRequest $request, Topic $topic )
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return redirect()->to($topic->link())->with('success', '更新话题成功');
    }

    /**
     * @param Topic $topic
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy ( Topic $topic )
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return redirect()->route('topics.index')->with('success', '成功删除话题');
    }

    /**
     * @param Request $request
     * @param ImageUploadHandler $uploader
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage ( Request $request, ImageUploadHandler $uploader )
    {
        $data = ['success' => false, 'msg' => '上传失败!', 'file_path' => ''];

        if ( $file = $request->upload_file ) {
            $result = $uploader->save($request->upload_file, 'topics', Auth::id(), 1024);
            if ( $result ) {
                $data['file_path'] = $result['path'];
                $data['msg']       = "上传成功!";
                $data['success']   = true;
            }
        }

        return response()->json($data);
    }
}