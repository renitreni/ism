<?php

namespace App\Enums;

enum JobOrderStatusEnum: string
{
    case RECEVED = 'recevied';
    case ONGOING = 'ongoing';
    case RELEASING = 'releasing';
    case COMPLETED = 'completed';
}
