<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verification extends Model
{
    use HasFactory;

    const TOKEN = 'token';
    const VALIDITY = 'validity';
    const TYPE = 'type';
    const USER_ID = 'user_id';
    const VALID = 'valid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::TOKEN,
        self::VALIDITY,
        self::TYPE,
        self::USER_ID,
        self::VALID
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
