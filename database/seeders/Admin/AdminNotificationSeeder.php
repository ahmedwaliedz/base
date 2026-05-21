<?php

namespace Database\Seeders\Admin;

use App\Models\Admin;
use App\Notifications\UserNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Admin::query()
            ->where('email', env('DASHBOARDEMAIL', 'SGA@gmail.com'))
            ->first()
            ?? Admin::query()->orderBy('id')->first();

        if (! $admin) {
            return;
        }

        $now = now();

        DB::table('notifications')
            ->where('notifiable_type', Admin::class)
            ->where('notifiable_id', $admin->id)
            ->delete();

        $notifications = [
            [
                'type' => 'admin_notification',
                'title' => ['en' => 'You have new order!', 'ar' => 'لديك طلب جديد!'],
                'message' => ['en' => 'Are you going to meet me tonight?', 'ar' => 'هل ستقابلني الليلة؟'],
                'created_at' => $now->copy()->subHours(9),
                'read_at' => null,
            ],
            [
                'type' => 'mail',
                'title' => ['en' => '99% Server load', 'ar' => 'استهلاك الخادم 99%'],
                'message' => ['en' => 'You got new order of goods?', 'ar' => 'وصلتك طلبية جديدة؟'],
                'created_at' => $now->copy()->subHours(5),
                'read_at' => $now->copy()->subHours(4)->subMinutes(15),
            ],
            [
                'type' => 'sms',
                'title' => ['en' => 'Warning Notification', 'ar' => 'تنبيه تحذيري'],
                'message' => ['en' => 'Server has used 99% of CPU', 'ar' => 'الخادم استخدم 99% من المعالج'],
                'created_at' => $now->copy()->subDay(),
                'read_at' => null,
            ],
            [
                'type' => 'admin_notification',
                'title' => ['en' => 'Complete the task', 'ar' => 'أكمل المهمة'],
                'message' => ['en' => 'One of your task is pending.', 'ar' => 'إحدى مهامك قيد الانتظار.'],
                'created_at' => $now->copy()->subWeek(),
                'read_at' => $now->copy()->subDays(6),
            ],
            [
                'type' => 'mail',
                'title' => ['en' => 'Generate monthly report', 'ar' => 'إنشاء التقرير الشهري'],
                'message' => ['en' => 'Your monthly financial summary is ready.', 'ar' => 'ملخصك المالي الشهري أصبح جاهزًا.'],
                'created_at' => $now->copy()->subWeeks(2),
                'read_at' => null,
            ],
            [
                'type' => 'admin_notification',
                'title' => ['en' => 'New user registered', 'ar' => 'تم تسجيل مستخدم جديد'],
                'message' => ['en' => 'A new account joined the platform.', 'ar' => 'انضم حساب جديد إلى المنصة.'],
                'created_at' => $now->copy()->subDays(3),
                'read_at' => $now->copy()->subDays(2),
            ],
            [
                'type' => 'mail',
                'title' => ['en' => 'Payment received', 'ar' => 'تم استلام دفعة'],
                'message' => ['en' => 'Payment completed successfully.', 'ar' => 'تمت عملية الدفع بنجاح.'],
                'created_at' => $now->copy()->subDays(4),
                'read_at' => $now->copy()->subDays(3),
            ],
            [
                'type' => 'admin_notification',
                'title' => ['en' => 'Backup completed', 'ar' => 'اكتمل النسخ الاحتياطي'],
                'message' => ['en' => 'Daily backup finished without errors.', 'ar' => 'اكتمل النسخ الاحتياطي اليومي بدون أخطاء.'],
                'created_at' => $now->copy()->subDays(6),
                'read_at' => $now->copy()->subDays(5),
            ],
            [
                'type' => 'sms',
                'title' => ['en' => 'SMS campaign sent', 'ar' => 'تم إرسال حملة SMS'],
                'message' => ['en' => 'Your SMS campaign has been delivered.', 'ar' => 'تم تسليم حملة الرسائل النصية الخاصة بك.'],
                'created_at' => $now->copy()->subHours(13),
                'read_at' => null,
            ],
            [
                'type' => 'admin_notification',
                'title' => ['en' => 'Profile updated', 'ar' => 'تم تحديث الملف الشخصي'],
                'message' => ['en' => 'Your account settings were updated.', 'ar' => 'تم تحديث إعدادات حسابك.'],
                'created_at' => $now->copy()->subHours(12),
                'read_at' => $now->copy()->subHours(11),
            ],
        ];

        foreach ($notifications as $notification) {
            DB::table('notifications')->insert([
                'id' => (string) Str::uuid(),
                'type' => UserNotification::class,
                'notifiable_type' => Admin::class,
                'notifiable_id' => $admin->id,
                'data' => json_encode([
                    'notification_type' => $notification['type'],
                    'title' => $notification['title'],
                    'message' => $notification['message'],
                ], JSON_UNESCAPED_UNICODE),
                'read_at' => $notification['read_at'],
                'created_at' => $notification['created_at'],
                'updated_at' => $notification['created_at'],
            ]);
        }
    }
}
