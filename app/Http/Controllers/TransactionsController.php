<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateTransaction;
use App\UserTransactionAccounts;
use App\Users;
use App\Transactions;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = (new UserTransactionAccounts)->getTransactions();
        return response()->json($transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UpdateTransaction $request)
    {

        $user = Users::create($request->only(['name', 'email']));
        $userTransactionAccounts = UserTransactionAccounts::create([
            'user_id' => $user->id,
            'balance' => $request->balance
        ]);

        $transaction = Transactions::create(array_merge(
            $request->only(['amount', 'type']),
            ['account_id' => $userTransactionAccounts->id]
        ));

        $transactions = (new UserTransactionAccounts)->getTransactions();
        return response()->json($transactions);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTransaction $request, $id)
    {
        $validated = $request->validated();
        $userTransactionAccounts = UserTransactionAccounts::find($id);

        if($request->has('name')){
            $user = $userTransactionAccounts->user()->update(['name'=>$request->name]);
        }else if($request->has('type')){
            $transaction = $userTransactionAccounts->transaction()->update(['type'=>$request->type]);
        }else if($request->has('amount')){
            $transaction = $userTransactionAccounts->transaction()->update(['amount'=>$request->amount]);
        }

        $transactions = (new UserTransactionAccounts)->getTransactions();
        return response()->json($transactions);
    }

}
