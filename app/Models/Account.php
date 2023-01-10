<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Account extends Model
{
    use HasFactory;

    public static function create(array $array)
    {
        $account = new Account();
        $account->uuid = Str::uuid();
        $account->name = $array['name'];
        $account->email = $array['email'];
        $account->password = Hash::make($array['password']);
        $account->save();

        return $account->uuid;

    }

    public static function validate(array $credentials)
    {
        $account = Account::where('email', $credentials['email'])->first();
        if ($account) {
            return Hash::check($credentials['password'], $account->password);
        }
        return false;
    }

    public function note()
    {
        return $this->hasMany(Notes::class, 'account_uuid', 'uuid');
    }
}
