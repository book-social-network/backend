<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\NotificationInterface;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private $notification;
    public function __construct(NotificationInterface $notificationInterface)
    {
        $this->notification = $notificationInterface;
    }
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $user = auth()->user();
        if (empty($user)) {
            return response()->json(['message' => 'Please login'], 404);
        }
        $quantity = $this->notification->getQuantityNotification($user->id);
        $notifications = $this->notification->getAllNotificationOfUser($user->id, $page, 10);
        $quantityPages = $this->notification->getQuantityPageNotificationOfUser($user->id, 10);
        $data = [];
        foreach ($notifications as $notification) {
            $data[] = [
                'id' => $notification->id,
                'information' => $notification->information,
                'state' => $notification->state,
                'type_id' => $notification->from_id . '-' . $notification->to_id,
                'type' => $notification->from_type . '-' . $notification->to_type,
            ];
        }
        return response()->json([
            'notifications' => $data,
            'quantity_pages' => $quantityPages,
            'quantity' => $quantity
        ]);
    }
    public function get($id)
    {
        $notification = $this->notification->getNotification($id);
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Please login'], 404);
        }
        if ($notification->to_id == $user->id && $notification->to_type == 'member') {
            return response()->json($notification);
        }
        return response()->json(['message' => 'Not found notification'], 404);
    }
    public function updateState($id)
    {
        $notification = $this->notification->getNotification($id);
        if (empty($notification)) {
            return response()->json(['message' => 'Not found notification'], 404);
        }
        $notification = $this->notification->updateNotification(['state' => 1], $notification->id);
        return response()->json($notification);
    }
    public function delete($id)
    {
        $notification = $this->notification->getNotification($id);
        if (empty($notification)) {
            return response()->json(['message' => 'Not found notification'], 404);
        }
        $this->notification->deleteNotification($id);
        return response()->json(['message' => 'delete notification successfully'],);
    }
}
