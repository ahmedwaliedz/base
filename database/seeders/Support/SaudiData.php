<?php

declare(strict_types=1);

namespace Database\Seeders\Support;

/**
 * Canonical Saudi geography + Arabic copy for factories and seeders.
 */
final class SaudiData
{
    /** Ordered to match legacy regions.json (country Saudi) so region_id 1–13 align with cities.json */
    public const LEGACY_REGION_CODES = [
        'RYD', 'MKK', 'ESP', 'ASR', 'TBK', 'HIL', 'NBN', 'JWF', 'NJR', 'BHA', 'JZN', 'QSM', 'MDN',
    ];

    /** @var array<string, array{en: string, ar: string}> */
    public const REGION_BY_CODE = [
        'RYD' => ['en' => 'Riyadh', 'ar' => 'الرياض'],
        'MKK' => ['en' => 'Makkah', 'ar' => 'مكة'],
        'MDN' => ['en' => 'Medina', 'ar' => 'المدينة المنورة'],
        'QSM' => ['en' => 'Al-Qassim', 'ar' => 'القصيم'],
        'ESP' => ['en' => 'Eastern Province', 'ar' => 'المنطقة الشرقية'],
        'ASR' => ['en' => 'Asir', 'ar' => 'عسير'],
        'TBK' => ['en' => 'Tabuk', 'ar' => 'تبوك'],
        'HIL' => ['en' => 'Hail', 'ar' => 'حائل'],
        'NBN' => ['en' => 'Northern Borders', 'ar' => 'الحدود الشمالية'],
        'JZN' => ['en' => 'Jizan', 'ar' => 'جازان'],
        'NJR' => ['en' => 'Najran', 'ar' => 'نجران'],
        'BHA' => ['en' => 'Al Bahah', 'ar' => 'الباحة'],
        'JWF' => ['en' => 'Al Jawf', 'ar' => 'الجوف'],
    ];

    /** Major cities grouped by region code */
    public const CITIES = [
        'RYD' => [
            ['en' => 'Riyadh', 'ar' => 'الرياض'],
            ['en' => 'Diriyah', 'ar' => 'الدرعية'],
            ['en' => 'Al Kharj', 'ar' => 'الخرج'],
            ['en' => 'Al Majmaah', 'ar' => 'المجمعة'],
            ['en' => 'Az Zulfi', 'ar' => 'الزلفي'],
            ['en' => 'Wadi Ad Dawasir', 'ar' => 'وادي الدواسر'],
        ],
        'MKK' => [
            ['en' => 'Makkah', 'ar' => 'مكة المكرمة'],
            ['en' => 'Jeddah', 'ar' => 'جدة'],
            ['en' => 'Taif', 'ar' => 'الطائف'],
            ['en' => 'Rabigh', 'ar' => 'رابغ'],
            ['en' => 'Khulais', 'ar' => 'خليص'],
        ],
        'MDN' => [
            ['en' => 'Madinah', 'ar' => 'المدينة المنورة'],
            ['en' => 'Yanbu', 'ar' => 'ينبع'],
            ['en' => 'Al Ula', 'ar' => 'العلا'],
            ['en' => 'Badr', 'ar' => 'بدر'],
        ],
        'QSM' => [
            ['en' => 'Buraidah', 'ar' => 'بريدة'],
            ['en' => 'Unaizah', 'ar' => 'عنيزة'],
            ['en' => 'Ar Rass', 'ar' => 'الرس'],
        ],
        'ESP' => [
            ['en' => 'Dammam', 'ar' => 'الدمام'],
            ['en' => 'Al Khobar', 'ar' => 'الخبر'],
            ['en' => 'Dhahran', 'ar' => 'الظهران'],
            ['en' => 'Al Jubail', 'ar' => 'الجبيل'],
            ['en' => 'Al Ahsa', 'ar' => 'الأحساء'],
            ['en' => 'Al Qatif', 'ar' => 'القطيف'],
            ['en' => 'Hafr Al Batin', 'ar' => 'حفر الباطن'],
        ],
        'ASR' => [
            ['en' => 'Abha', 'ar' => 'أبها'],
            ['en' => 'Khamis Mushait', 'ar' => 'خميس مشيط'],
            ['en' => 'Bisha', 'ar' => 'بيشة'],
            ['en' => 'Mahayel', 'ar' => 'محايل عسير'],
        ],
        'TBK' => [
            ['en' => 'Tabuk', 'ar' => 'تبوك'],
            ['en' => 'Duba', 'ar' => 'ضباء'],
            ['en' => 'Tayma', 'ar' => 'تيماء'],
            ['en' => 'NEOM', 'ar' => 'نيوم'],
        ],
        'HIL' => [
            ['en' => 'Hail', 'ar' => 'حائل'],
            ['en' => 'Baqaa', 'ar' => 'بقعاء'],
        ],
        'NBN' => [
            ['en' => 'Arar', 'ar' => 'عرعر'],
            ['en' => 'Rafha', 'ar' => 'رفحاء'],
            ['en' => 'Turaif', 'ar' => 'طريف'],
        ],
        'JZN' => [
            ['en' => 'Jazan', 'ar' => 'جازان'],
            ['en' => 'Sabya', 'ar' => 'صبيا'],
            ['en' => 'Abu Arish', 'ar' => 'أبو عريش'],
        ],
        'NJR' => [
            ['en' => 'Najran', 'ar' => 'نجران'],
            ['en' => 'Sharurah', 'ar' => 'شرورة'],
        ],
        'BHA' => [
            ['en' => 'Al Bahah', 'ar' => 'الباحة'],
            ['en' => 'Baljurashi', 'ar' => 'بلجرشي'],
        ],
        'JWF' => [
            ['en' => 'Sakaka', 'ar' => 'سكاكا'],
            ['en' => 'Dawmat al Jandal', 'ar' => 'دومة الجندل'],
            ['en' => 'Al Qurayyat', 'ar' => 'القريات'],
        ],
    ];

    /** Curated districts per major city (English key = city en name) */
    public const DISTRICTS = [
        'Riyadh' => [
            ['en' => 'Al Olaya', 'ar' => 'العليا'],
            ['en' => 'Al Malaz', 'ar' => 'الملز'],
            ['en' => 'Al Nakheel', 'ar' => 'النخيل'],
            ['en' => 'King Fahd', 'ar' => 'الملك فهد'],
            ['en' => 'Al Sulimaniyah', 'ar' => 'السليمانية'],
            ['en' => 'Al Murabba', 'ar' => 'المربع'],
            ['en' => 'Al Yasmin', 'ar' => 'الياسمين'],
            ['en' => 'Al Mursalat', 'ar' => 'المرسلات'],
            ['en' => 'Al Rabwa', 'ar' => 'الربوة'],
            ['en' => 'Hittin', 'ar' => 'حطين'],
        ],
        'Jeddah' => [
            ['en' => 'Al Hamra', 'ar' => 'الحمراء'],
            ['en' => 'Al Salama', 'ar' => 'السلامة'],
            ['en' => 'Al Rawdah', 'ar' => 'الروضة'],
            ['en' => 'Al Shati', 'ar' => 'الشاطئ'],
            ['en' => 'Al Naeem', 'ar' => 'النعيم'],
            ['en' => 'Obhur', 'ar' => 'أبحر'],
            ['en' => 'Al Andalus', 'ar' => 'الأندلس'],
        ],
        'Makkah' => [
            ['en' => 'Al Aziziyah', 'ar' => 'العزيزية'],
            ['en' => 'Al Mursalat', 'ar' => 'المرسلات'],
            ['en' => 'Al Awali', 'ar' => 'العوالي'],
            ['en' => 'Al Hujun', 'ar' => 'الحجون'],
        ],
        'Dammam' => [
            ['en' => 'Al Shati', 'ar' => 'الشاطئ'],
            ['en' => 'Al Faisaliyah', 'ar' => 'الفيصلية'],
            ['en' => 'Al Adamah', 'ar' => 'العدامة'],
            ['en' => 'Al Hamra', 'ar' => 'الحمراء'],
            ['en' => 'Al Manar', 'ar' => 'المنار'],
        ],
    ];

    /** @var array<int, array{en: string, ar: string}> */
    public const DISTRICT_POOL = [
        ['en' => 'Al Nahda', 'ar' => 'النهضة'],
        ['en' => 'Al Wurud', 'ar' => 'الورود'],
        ['en' => 'Al Rawabi', 'ar' => 'الروابي'],
        ['en' => 'Al Khalidiyah', 'ar' => 'الخالدية'],
        ['en' => 'Al Safa', 'ar' => 'الصفا'],
        ['en' => 'Al Marwah', 'ar' => 'المروة'],
        ['en' => 'Al Aziziyah', 'ar' => 'العزيزية'],
        ['en' => 'Al Faisaliyah', 'ar' => 'الفيصلية'],
        ['en' => 'Al Naseem', 'ar' => 'النسيم'],
        ['en' => 'Al Manar', 'ar' => 'المنار'],
        ['en' => 'Al Salam', 'ar' => 'السلام'],
        ['en' => 'Al Olaya', 'ar' => 'العليا'],
        ['en' => 'Al Worood', 'ar' => 'الورود'],
        ['en' => 'King Abdulaziz', 'ar' => 'الملك عبدالعزيز'],
        ['en' => 'King Fahd', 'ar' => 'الملك فهد'],
    ];

    public const NAMES_MALE = [
        'محمد', 'أحمد', 'عبدالله', 'عبدالعزيز', 'عبدالرحمن', 'خالد', 'سلطان', 'فيصل',
        'ناصر', 'سعد', 'تركي', 'يوسف', 'عمر', 'بدر', 'ماجد', 'فهد', 'بندر', 'وليد',
        'سامي', 'نواف', 'زياد', 'مازن', 'حسام', 'رائد', 'طارق', 'أيمن', 'هشام', 'رامي',
        'إبراهيم', 'سعود', 'مشاري', 'نايف', 'عبدالملك', 'عبدالإله', 'راشد', 'صالح',
    ];

    public const NAMES_FEMALE = [
        'فاطمة', 'عائشة', 'مريم', 'نورة', 'سارة', 'هدى', 'منى', 'ريم', 'لطيفة', 'هيا',
        'الجوهرة', 'شيخة', 'أمل', 'أسماء', 'هند', 'منيرة', 'لمى', 'مها', 'ندى',
        'دانة', 'رهف', 'جواهر', 'بدور', 'حصة', 'عفاف', 'نوف', 'يارا', 'رغد',
    ];

    public const FAMILY_NAMES = [
        'الزهراني', 'الغامدي', 'العتيبي', 'الحربي', 'القحطاني', 'الشهري', 'الدوسري',
        'العنزي', 'الشمري', 'المطيري', 'الرشيدي', 'السهلي', 'الجهني', 'البلوي',
        'البقمي', 'المالكي', 'السبيعي', 'اليامي', 'الخالدي', 'الصاعدي', 'العسيري',
        'الفيفي', 'الزبيدي', 'السلمي', 'العمري', 'الخثعمي', 'الثقفي', 'الحازمي',
        'الحارثي', 'المحمدي', 'المرواني', 'الشريف', 'الراشد', 'السعدي', 'المدني',
    ];

    public const COMPLAINT_SUBJECTS = [
        'تأخر في توصيل الطلب',
        'منتج به عيب من الجودة',
        'خطأ في الفاتورة',
        'لم يصلني الطلب',
        'استرجاع المبلغ',
        'طلب استبدال منتج',
        'خدمة العملاء',
        'مشكلة في تسجيل الدخول',
        'مشكلة في الدفع',
        'منتج لا يطابق الوصف',
    ];

    public const COMPLAINT_BODIES = [
        'السلام عليكم، تم الطلب قبل أكثر من أسبوع ولم يصلني حتى الآن، الرجاء المتابعة.',
        'وصلني المنتج مكسوراً وأطلب استبدالاً عاجلاً أو استرجاع المبلغ.',
        'تم خصم المبلغ مرتين من حسابي بدون مبرر، أرجو التحقق وإعادة الفرق.',
        'المنتج لا يطابق الصورة في الموقع، أرجو حل الموضوع بأسرع وقت.',
        'حاولت الاتصال بخدمة العملاء عدة مرات بدون رد، أرجو التواصل معي.',
        'الدفع تم بنجاح حسب التطبيق لكن الطلب لم يظهر في حسابي.',
    ];

    public const CONTACT_SUBJECTS = [
        'استفسار عن منتج',
        'طلب عرض سعر',
        'الشراكة التجارية',
        'بلاغ عن مشكلة فنية',
        'اقتراح تحسين',
    ];

    public const CONTACT_BODIES = [
        'أود الاستفسار عن توفر المنتج والمدة المتوقعة للتوصيل.',
        'نحن مهتمون بالشراكة معكم في مجال التوزيع.',
        'أواجه مشكلة تقنية عند إتمام الطلب وأرجو المساعدة.',
        'لدي اقتراح لتحسين تجربة المستخدم في التطبيق.',
        'الرجاء إرسال عرض سعر للكمية المطلوبة.',
    ];

    public static function fullNameMale(): string
    {
        return self::NAMES_MALE[array_rand(self::NAMES_MALE)]
            .' '.self::NAMES_MALE[array_rand(self::NAMES_MALE)]
            .' '.self::FAMILY_NAMES[array_rand(self::FAMILY_NAMES)];
    }

    public static function fullNameFemale(): string
    {
        return self::NAMES_FEMALE[array_rand(self::NAMES_FEMALE)]
            .' '.self::NAMES_MALE[array_rand(self::NAMES_MALE)]
            .' '.self::FAMILY_NAMES[array_rand(self::FAMILY_NAMES)];
    }

    public static function fullName(): string
    {
        return random_int(0, 1) === 1 ? self::fullNameMale() : self::fullNameFemale();
    }

    /** Saudi mobile local storage form: 05 + 8 digits */
    public static function phone(): string
    {
        return '05'.str_pad((string) random_int(0, 99_999_999), 8, '0', STR_PAD_LEFT);
    }

    /** Deterministic Saudi-format phone for bulk uniqueness */
    public static function phoneAtIndex(int $index): string
    {
        return '05'.str_pad((string) (($index % 100_000_000)), 8, '0', STR_PAD_LEFT);
    }

    public static function postalCode(): string
    {
        return str_pad((string) random_int(11_000, 99_999), 5, '0', STR_PAD_LEFT);
    }

    /** Matches {@see \App\Traits\HandleNumbersTrait::fixPhone()} for seed rows */
    public static function userPhoneNormalized(?string $countryCode, ?string $phone): string
    {
        $fix = static function (?string $phoneArg): string {
            if ($phoneArg === null || $phoneArg === '') {
                return '';
            }
            $result = str_replace(
                ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
                range(0, 9),
                $phoneArg
            );
            $result = ltrim($result, '00');
            $result = ltrim($result, '0');
            $result = ltrim($result, '+');

            return $result;
        };

        return $fix($countryCode).$fix($phone);
    }

    /** @return list<array{en: string, ar: string}> */
    public static function legacyRegionsOrdered(): array
    {
        $out = [];
        foreach (self::LEGACY_REGION_CODES as $code) {
            $out[] = self::REGION_BY_CODE[$code];
        }

        return $out;
    }
}
