<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notification\SendNotificationsRequest;
use App\Services\Notification\NotificationService;
use App\Traits\Response\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * Controller for handling notification-related actions
 */
class NotificationController extends Controller
{
    use ResponseTrait;
    /**
     * Create a new controller instance.
     *
     * @param NotificationService $notificationService The notification service
     */
    public function __construct( private readonly NotificationService $notificationService ){
    }

    /**
         * Send notifications to users
         *
         * @param SendNotificationsRequest $request The validated request
         * @return JsonResponse
    */
    public function sendNotifications(SendNotificationsRequest $request): JsonResponse
    {
        $data = $request->validated();
        $notificationType = match ($data['type'] ?? null) {
            'mail' => 'mail',
            'sms'  => 'sms',
            default => 'admin_notification',
        };
        $data['notification_type'] = $notificationType;

        $success = $this->notificationService->send($data);

        if ($success)
            return $this->respondWithSuccess(__('admin/main.notification_sent_successfully'));

        if ($notificationType === 'sms')
            return $this->respondWithFail(__('admin/main.sms_not_configured'));

        return $this->respondWithFail(__('admin/main.failed_to_send_notification'));
    }

    /**
     * Display the notifications page
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.notifications.index', $this->notificationService->getNotificationPageData());
    }
}
