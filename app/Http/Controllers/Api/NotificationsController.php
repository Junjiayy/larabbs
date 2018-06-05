<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Transformers\NotificationTransformer;

/**
 * Class NotificationsController
 * @package App\Http\Controllers\Api
 */
class NotificationsController extends Controller
{
    /**
     * @return \Dingo\Api\Http\Response
     */
    public function index ()
    {
        $notifications = $this->user->notifications()->paginate(20);

        return $this->response->paginator($notifications, new NotificationTransformer());
    }

    /**
     * @return mixed
     */
    public function stats ()
    {
        return $this->response->array([
            'unread_count' => $this->user()->notification_count,
        ]);
    }
}