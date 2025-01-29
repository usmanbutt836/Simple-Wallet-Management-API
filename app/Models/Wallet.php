<?php

namespace App\Models;

use App\Exceptions\InsufficientFundsException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balance'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposit(float $amount): self {
        DB::transaction(function () use ($amount) {
            $this->increment('balance', $amount);
        });

        return $this;
    }

    public function withdraw(float $amount): self {
        return DB::transaction(function () use ($amount) {
            if ($this->balance < $amount) {
                throw new InsufficientFundsException();
            }

            $this->decrement('balance', $amount);

            return $this;
        });
    }
}
