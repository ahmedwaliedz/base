<?php

return [
    
    'unauthorized' => 'غير مصرح لك بالوصول إلى هذا المورد.',
    'unauthenticated' => 'يجب عليك تسجيل الدخول للوصول إلى هذا المورد.',
    'blocked_by_admin' => 'تم حظرك من قبل المسؤول.',
    'forbidden' => 'ممنوع الوصول إلى هذا المورد.',
    'internal_error' => 'حدث خطأ داخلي. يرجى المحاولة مرة أخرى لاحقًا.',
    'unknown_error' => 'خطأ غير معروف. يرجى المحاولة مرة أخرى لاحقًا.',
    'need_activation' => 'يجب تنشيط حسابك للوصول إلى هذا المورد.',
    'validation_errors' => 'خطأ في التحقق من صحة البيانات.',
    
    // Auth responses
    'code_sent_successfully' => 'تم إرسال كود التفعيل بنجاح',
    'logged_in_successfully' => 'تم تسجيل الدخول بنجاح',
    'logged_out_successfully' => 'تم تسجيل الخروج بنجاح',
    'user_profile_retrieved' => 'تم جلب بيانات المستخدم بنجاح',
    'invalid_credentials' => 'بيانات الدخول غير صحيحة',
    'profile_incomplete' => 'معلومات الملف الشخصي غير مكتملة. يرجى إكمال معلوماتك.',
    'code_expired' => 'كود التفعيل منتهي الصلاحية',
    'code_invalid' => 'كود التفعيل غير صحيح',
    'code_not_found' => 'لم يتم العثور على كود تفعيل صحيح',
    'too_many_attempts' => 'عدد المحاولات تجاوز الحد المسموح، يرجى طلب كود جديد',
    'wait_for_new_code' => 'يرجى الانتظار :seconds ثانية قبل طلب كود جديد',
    
    // General responses
    'not_found' => 'المورد غير موجود',
    'validation_failed' => 'فشل في التحقق من البيانات',
    'success' => 'نجح',

    // Export
    'unsupported_export_format' => 'صيغة التصدير غير مدعومة: :format',
];
