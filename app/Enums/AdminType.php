<?php
namespace App\Enums;

use App\Traits\GeneralEnumTrait;

enum AdminType: string {

    use GeneralEnumTrait;

    public const PATH = 'admin/main';

    case ADMIN       = 'admin';
    case SUPER_ADMIN = 'super_admin';

}
