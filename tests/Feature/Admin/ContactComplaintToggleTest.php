<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Enums\ComplaintStatus;
use App\Models\Admin;
use App\Models\Complaint;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactComplaintToggleTest extends TestCase
{
    use RefreshDatabase;

    private Admin $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->superAdmin = Admin::factory()->create();
    }

    private function actingAsSuperAdmin(): static
    {
        return $this->actingAs($this->superAdmin, 'admin');
    }

    public function test_contact_message_read_toggle_toggles_is_read(): void
    {
        $message = ContactMessage::factory()->create(['is_read' => false]);

        $response = $this->actingAsSuperAdmin()->put(
            route('admin.contact-messages.switchIsRead', $message->id),
            [],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(200);
        $response->assertJson(['data' => ['is_read' => true]]);
        $this->assertDatabaseHas('contact_messages', ['id' => $message->id, 'is_read' => true]);

        $response = $this->actingAsSuperAdmin()->put(
            route('admin.contact-messages.switchIsRead', $message->id),
            [],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(200);
        $response->assertJson(['data' => ['is_read' => false]]);
        $this->assertDatabaseHas('contact_messages', ['id' => $message->id, 'is_read' => false]);
    }

    public function test_contact_message_delete_and_restore(): void
    {
        $message = ContactMessage::factory()->create();
        $this->assertNull($message->fresh()->deleted_at);

        $this->actingAsSuperAdmin()->delete(
            route('admin.contact-messages.destroy', ['contact_message' => $message->id]),
            [],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $this->assertNotNull($message->fresh()->deleted_at);

        $this->actingAsSuperAdmin()->put(
            route('admin.contact-messages.restore', $message->id),
            [],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $this->assertNull($message->fresh()->deleted_at);
    }

    public function test_complaint_read_toggle_toggles_is_read(): void
    {
        $complaint = Complaint::factory()->create(['is_read' => false]);

        $response = $this->actingAsSuperAdmin()->put(
            route('admin.complaints.switchIsRead', $complaint->id),
            [],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(200);
        $response->assertJson(['data' => ['is_read' => true]]);
        $this->assertDatabaseHas('complaints', ['id' => $complaint->id, 'is_read' => true]);
    }

    public function test_complaint_status_cycles_through_enum_values(): void
    {
        $complaint = Complaint::factory()->create(['status' => ComplaintStatus::Pending->value]);

        $cases = ComplaintStatus::cases();
        for ($i = 0; $i < count($cases); $i++) {
            $response = $this->actingAsSuperAdmin()->put(
                route('admin.complaints.switchStatus', $complaint->id),
                [],
                ['X-Requested-With' => 'XMLHttpRequest']
            );

            $response->assertStatus(200);

            $expectedIndex = ($i + 1) % count($cases);
            $expectedStatus = $cases[$expectedIndex];
            $response->assertJson(['data' => ['status' => $expectedStatus->value]]);

            $complaint->refresh();
            $this->assertEquals($expectedStatus->value, $complaint->status?->value);
        }
    }

    public function test_complaint_delete_and_restore(): void
    {
        $complaint = Complaint::factory()->create();
        $this->assertNull($complaint->fresh()->deleted_at);

        $this->actingAsSuperAdmin()->delete(
            route('admin.complaints.destroy', ['complaint' => $complaint->id]),
            [],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $this->assertNotNull($complaint->fresh()->deleted_at);

        $this->actingAsSuperAdmin()->put(
            route('admin.complaints.restore', $complaint->id),
            [],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $this->assertNull($complaint->fresh()->deleted_at);
    }

    public function test_delete_missing_contact_message_returns_404(): void
    {
        $response = $this->actingAsSuperAdmin()->deleteJson(
            route('admin.contact-messages.destroy', ['contact_message' => 99999])
        );

        $response->assertStatus(404);
    }

    public function test_restore_missing_contact_message_returns_404(): void
    {
        $response = $this->actingAsSuperAdmin()->putJson(
            route('admin.contact-messages.restore', 99999)
        );

        $response->assertStatus(404);
    }

    public function test_contact_message_show_marks_unread_as_read(): void
    {
        $message = ContactMessage::factory()->create(['is_read' => false]);

        $response = $this->actingAsSuperAdmin()->get(
            route('admin.contact-messages.show', ['contact_message' => $message->id])
        );

        $response->assertOk();
        $this->assertDatabaseHas('contact_messages', ['id' => $message->id, 'is_read' => true]);
    }

    public function test_contact_message_show_keeps_already_read_as_read(): void
    {
        $message = ContactMessage::factory()->create(['is_read' => true]);

        $response = $this->actingAsSuperAdmin()->get(
            route('admin.contact-messages.show', ['contact_message' => $message->id])
        );

        $response->assertOk();
        $this->assertDatabaseHas('contact_messages', ['id' => $message->id, 'is_read' => true]);
    }

    public function test_guest_cannot_toggle_read(): void
    {
        $message = ContactMessage::factory()->create();

        $response = $this->put(
            route('admin.contact-messages.switchIsRead', $message->id),
            [],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertRedirect(route('admin.loginPage'));
    }
}
