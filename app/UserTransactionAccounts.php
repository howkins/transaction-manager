<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTransactionAccounts extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'balance', 'user_id'
    ];
    public function user() {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function transaction() {
        return $this->belongsTo(Transactions::class, 'id', 'account_id');
    }

    public function getTransactions(){
        return $this->all()->map(function($userTransactionAccounts){
            $user = $userTransactionAccounts->user;
            $transaction = $userTransactionAccounts->transaction;

            $userTransactionAccounts->name = $user->name;
            $userTransactionAccounts->email = $user->email;
            $userTransactionAccounts->type = $transaction->type;
            $userTransactionAccounts->amount = $transaction->amount;
            unset($userTransactionAccounts->user, $userTransactionAccounts->transaction);
            return $userTransactionAccounts;
        });
    }
}
