<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function deposit(Request $request, $userId)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ], [
            'amount.required' => 'The deposit amount is required.',
            'amount.numeric' => 'The deposit amount must be a number.',
            'amount.min' => 'The deposit amount must be at least 0.01.',
        ]);

        $user = User::findOrFail($userId);
        $wallet = $user->wallet;

        // Deposit into wallet
        $wallet->deposit($validated['amount']);

        return response()->json([
            'message' => 'Deposit successful.',
            'new_balance' => $wallet->balance
        ], 200);

    }

    public function withdraw(Request $request, $userId)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ], [
            'amount.required' => 'The withdrawal amount is required.',
            'amount.numeric' => 'The withdrawal amount must be a number.',
            'amount.min' => 'The withdrawal amount must be at least 0.01.',
        ]);

        $user = User::findOrFail($userId);
        $wallet = $user->wallet;

        try {
            // Withdraw from wallet
            $wallet->withdraw($validated['amount']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Insufficient funds or error processing your request.',
                'message' => $e->getMessage()
            ], 400);
        }

        return response()->json([
            'message' => 'Withdrawal successful.',
            'new_balance' => $wallet->balance
        ], 200);
    }
}
