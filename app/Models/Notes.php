<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    use HasFactory;

    protected $guarded = ['user_uuid', 'id'];


    public function account()
    {
        return $this->belongsTo(Account::class, 'account_uuid', 'uuid');
    }
}
