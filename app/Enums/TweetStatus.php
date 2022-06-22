<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static active()
 * @method static static passive()
 */
final class TweetStatus extends Enum
{
    const active = 'active';
    const passive = 'passive';
}
