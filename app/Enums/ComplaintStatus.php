<?php

namespace App\Enums;

enum ComplaintStatus: string
{
    case Pending = 'pending';

    case Processing = 'processing';

    case Completed = 'completed';

    case Rejected = 'rejected';
}
