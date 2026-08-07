<?php

namespace App\Enums;

enum JobOrderProcessEnum: string
{
    case WARRANTY = 'warranty';
    case OUTRIGHT_REPLACEMENT = 'outright replacement';
    case REPAIR = 'repair';
    case CHANGE_ITEM = 'change item';
    case REFUND = 'refund';
}
