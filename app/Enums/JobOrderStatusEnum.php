<?php

namespace App\Enums;

enum JobOrderStatusEnum: string
{
    case RECEIVED = 'received';
    case ONGOING = 'ongoing';
    case RELEASING = 'releasing';
    case COMPLETED = 'completed';
}
