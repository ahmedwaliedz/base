<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AppNotificationService;
use App\Traits\Response\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class AppNotificationController extends Controller
{
    use ResponseTrait;

    public function __construct(private readonly AppNotificationService $appNotificationService)
    {}

    public function index(): View
    {
        $admin = auth('admin')->user();

        return view('admin.app-notifications.index', $this->appNotificationService->getNotificationsData($admin));
    }

    public function markAsRead(string $notification): JsonResponse
    {
        $admin = auth('admin')->user();

        $success = $this->appNotificationService->markAsRead($admin, $notification);

        if (! $success) {
            return $this->respondWithFail(__('admin/main.invalid_notification'), [], Response::HTTP_BAD_REQUEST);
        }

        $summary = $this->appNotificationService->getSummary($admin);
        $latestNotifications = $this->appNotificationService->latestNotifications($admin)->values()->all();

        return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
            'summary' => $summary,
            'latestNotifications' => $latestNotifications,
        ]);
    }

    public function markAllAsRead(): JsonResponse
    {
        $admin = auth('admin')->user();

        $result = $this->appNotificationService->markAllAsRead($admin);
        $summary = $this->appNotificationService->getSummary($admin);

        return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
            'count' => $result['count'],
            'summary' => $summary,
            'latestNotifications' => $result['latestNotifications'],
        ]);
    }
}
