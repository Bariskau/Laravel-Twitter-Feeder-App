<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static sms()
 * @method static static email()
 */
final class VerificationType extends Enum
{
    const sms = 'sms';
    const email = 'email';
}
