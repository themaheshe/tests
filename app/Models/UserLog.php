<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $fillable = [
        'action',
        'user_id',
        'date_created',
    ];
    public $timestamps = false;

    public function user() {
        return $this->belongsTo(User::class);
    }
}
