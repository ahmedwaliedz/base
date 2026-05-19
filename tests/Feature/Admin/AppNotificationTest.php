<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class AppNotificationTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = Admin::factory()->create();
    }

    private function actingAsAdmin(): static
    {
        return $this->actingAs($this->admin, 'admin');
    }

    public function test_controller_can_be_instantiated(): void
    {
        $controller = new \App\Http\Controllers\Admin\AppNotificationController(
            new \App\Services\Admin\AppNotificationService()
        );

        $this->assertInstanceOf(\App\Http\Controllers\Admin\AppNotificationController::class, $controller);
    }

    public function test_index_returns_view_for_authenticated_admin(): void
    {
        $response = $this->actingAsAdmin()->get(route('admin.app-notifications.index'));

        $response->assertStatus(200);
        $response->assertViewHas('summary');
        $response->assertViewHas('notifications');
    }

    public function test_index_redirects_unauthenticated_users(): void
    {
        $response = $this->get(route('admin.app-notifications.index'));

        $response->assertRedirectToRoute('admin.loginPage');
    }

    public function test_mark_as_read_successfully_marks_notification(): void
    {
        $notification = DatabaseNotification::create([
            'id' => 'nr' . time(),
            'type' => 'App\\Notifications\\AdminNotification',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $this->admin->id,
            'data' => ['title' => ['en' => 'Test'], 'message' => ['en' => 'Test message']],
            'read_at' => null,
        ]);

        $response = $this->actingAsAdmin()->post(route('admin.app-notifications.markAsRead', [
            'notification' => $notification->id,
        ]));

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message', 'data' => ['summary']]);
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_mark_as_read_returns_failure_for_invalid_notification_id(): void
    {
        $response = $this->actingAsAdmin()->post(route('admin.app-notifications.markAsRead', [
            'notification' => 'invalid-id',
        ]));

        $response->assertStatus(400);
        $response->assertJsonStructure(['status', 'message']);
    }

    public function test_mark_all_as_read_successfully_marks_all_notifications(): void
    {
        DatabaseNotification::create([
            'id' => 'ma1' . time(),
            'type' => 'App\\Notifications\\AdminNotification',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $this->admin->id,
            'data' => ['title' => ['en' => 'Test 1'], 'message' => ['en' => 'Test message 1']],
            'read_at' => null,
        ]);

        DatabaseNotification::create([
            'id' => 'ma2' . time(),
            'type' => 'App\\Notifications\\AdminNotification',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $this->admin->id,
            'data' => ['title' => ['en' => 'Test 2'], 'message' => ['en' => 'Test message 2']],
            'read_at' => null,
        ]);

        $response = $this->actingAsAdmin()->post(route('admin.app-notifications.markAllAsRead'));

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message', 'data' => ['count', 'summary']]);
        $this->assertEquals(0, $this->admin->unreadNotifications()->count());
    }

    public function test_index_returns_empty_data_when_no_notifications(): void
    {
        $response = $this->actingAsAdmin()->get(route('admin.app-notifications.index'));

        $response->assertStatus(200);
        $response->assertViewHas('summary', [
            'total' => 0,
            'unread' => 0,
            'read' => 0,
        ]);
    }

    public function test_service_dashboard_data_returns_correct_structure(): void
    {
        $service = new \App\Services\Admin\AppNotificationService();
        $data = $service->dashboardData($this->admin);

        $this->assertArrayHasKey('adminNotificationSummary', $data);
        $this->assertArrayHasKey('adminLatestNotifications', $data);
        $this->assertArrayHasKey('total', $data['adminNotificationSummary']);
        $this->assertArrayHasKey('unread', $data['adminNotificationSummary']);
        $this->assertArrayHasKey('read', $data['adminNotificationSummary']);
    }

    public function test_service_get_notifications_data_returns_correct_structure(): void
    {
        $service = new \App\Services\Admin\AppNotificationService();
        $data = $service->getNotificationsData($this->admin);

        $this->assertArrayHasKey('summary', $data);
        $this->assertArrayHasKey('notifications', $data);
        $this->assertArrayHasKey('latestNotifications', $data);
    }

    public function test_service_get_summary_returns_correct_structure(): void
    {
        $service = new \App\Services\Admin\AppNotificationService();
        $summary = $service->getSummary($this->admin);

        $this->assertArrayHasKey('total', $summary);
        $this->assertArrayHasKey('unread', $summary);
        $this->assertArrayHasKey('read', $summary);
    }

    public function test_service_mark_as_read_returns_false_for_invalid_id(): void
    {
        $service = new \App\Services\Admin\AppNotificationService();
        $result = $service->markAsRead($this->admin, 'non-existent-id');

        $this->assertFalse($result);
    }

    public function test_service_mark_as_read_returns_true_for_valid_notification(): void
    {
        $notification = DatabaseNotification::create([
            'id' => 'mr' . time(),
            'type' => 'App\\Notifications\\AdminNotification',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $this->admin->id,
            'data' => ['title' => ['en' => 'Test'], 'message' => ['en' => 'Test message']],
            'read_at' => null,
        ]);

        $service = new \App\Services\Admin\AppNotificationService();
        $result = $service->markAsRead($this->admin, $notification->id);

        $this->assertTrue($result);
    }

    public function test_service_mark_all_as_read_returns_count(): void
    {
        DatabaseNotification::create([
            'id' => 'n1' . time(),
            'type' => 'App\\Notifications\\AdminNotification',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $this->admin->id,
            'data' => ['title' => ['en' => 'Test'], 'message' => ['en' => 'Test message']],
            'read_at' => null,
        ]);

        DatabaseNotification::create([
            'id' => 'n2' . time(),
            'type' => 'App\\Notifications\\AdminNotification',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $this->admin->id,
            'data' => ['title' => ['en' => 'Test'], 'message' => ['en' => 'Test message']],
            'read_at' => null,
        ]);

        $service = new \App\Services\Admin\AppNotificationService();
        $result = $service->markAllAsRead($this->admin);

        $this->assertIsArray($result);
        $this->assertEquals(2, $result['count']);
        $this->assertArrayHasKey('latestNotifications', $result);
    }

    public function test_one_admin_cannot_mark_another_admins_notification(): void
    {
        $otherAdmin = Admin::factory()->create();

        $notification = DatabaseNotification::create([
            'id' => 'na1' . time(),
            'type' => 'App\\Notifications\\AdminNotification',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $otherAdmin->id,
            'data' => ['title' => ['en' => 'Test'], 'message' => ['en' => 'Test message']],
            'read_at' => null,
        ]);

        $service = new \App\Services\Admin\AppNotificationService();
        $result = $service->markAsRead($this->admin, $notification->id);

        $this->assertFalse($result);
        $this->assertNull($notification->fresh()->read_at);
    }

    public function test_ajax_mark_as_read_endpoint_returns_json(): void
    {
        $notification = DatabaseNotification::create([
            'id' => 'ajax1' . time(),
            'type' => 'App\\Notifications\\AdminNotification',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $this->admin->id,
            'data' => ['title' => ['en' => 'Test'], 'message' => ['en' => 'Test message']],
            'read_at' => null,
        ]);

        $response = $this->actingAsAdmin()
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->post(route('admin.app-notifications.markAsRead', [
                'notification' => $notification->id,
            ]));

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }

    public function test_unread_counts_updated_after_mark_all_as_read(): void
    {
        DatabaseNotification::create([
            'id' => 'uc1' . time(),
            'type' => 'App\\Notifications\\AdminNotification',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $this->admin->id,
            'data' => ['title' => ['en' => 'Test'], 'message' => ['en' => 'Test message']],
            'read_at' => null,
        ]);

        $service = new \App\Services\Admin\AppNotificationService();
        $summaryBefore = $service->getSummary($this->admin);
        $this->assertEquals(1, $summaryBefore['unread']);

        $service->markAllAsRead($this->admin);
        $summaryAfter = $service->getSummary($this->admin);

        $this->assertEquals(0, $summaryAfter['unread']);
        $this->assertEquals(1, $summaryAfter['read']);
    }

    public function test_mark_as_read_returns_latest_notifications_for_navbar_sync(): void
    {
        $notification = DatabaseNotification::create([
            'id' => 'ln1' . time(),
            'type' => 'App\\Notifications\\AdminNotification',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $this->admin->id,
            'data' => ['title' => ['en' => 'Test'], 'message' => ['en' => 'Test message']],
            'read_at' => null,
        ]);

        $response = $this->actingAsAdmin()
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->post(route('admin.app-notifications.markAsRead', [
                'notification' => $notification->id,
            ]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'summary' => ['total', 'unread', 'read'],
                'latestNotifications' => [
                    '*' => ['id', 'title', 'message', 'is_read', 'tone', 'icon', 'created_human']
                ]
            ]
        ]);
    }

    public function test_mark_all_as_read_returns_latest_notifications_for_navbar_sync(): void
    {
        DatabaseNotification::create([
            'id' => 'ln2' . time(),
            'type' => 'App\\Notifications\\AdminNotification',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $this->admin->id,
            'data' => ['title' => ['en' => 'Test 1'], 'message' => ['en' => 'Test message 1']],
            'read_at' => null,
        ]);

        $response = $this->actingAsAdmin()
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->post(route('admin.app-notifications.markAllAsRead'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'count',
                'summary' => ['total', 'unread', 'read'],
                'latestNotifications' => [
                    '*' => ['id', 'title', 'message', 'is_read', 'tone', 'icon', 'created_human']
                ]
            ]
        ]);
    }

    public function test_latest_notifications_returns_raw_data_frontend_must_escape(): void
    {
        DatabaseNotification::create([
            'id' => 'ln3' . time(),
            'type' => 'App\\Notifications\\AdminNotification',
            'notifiable_type' => Admin::class,
            'notifiable_id' => $this->admin->id,
            'data' => [
                'title' => ['en' => '<script>alert("xss")</script>'],
                'message' => ['en' => '<img onerror="alert(1)" src="x">']
            ],
            'read_at' => null,
        ]);

        $service = new \App\Services\Admin\AppNotificationService();
        $latest = $service->latestNotifications($this->admin)->first();

        $this->assertIsString($latest['title']);
        $this->assertIsString($latest['message']);
        $this->assertStringContainsString('<script>', $latest['title']);
        $this->assertStringContainsString('<img', $latest['message']);

        $this->assertArrayHasKey('title', $latest);
        $this->assertArrayHasKey('message', $latest);
        $this->assertArrayHasKey('icon', $latest);
        $this->assertArrayHasKey('tone', $latest);
        $this->assertArrayHasKey('is_read', $latest);
    }
}