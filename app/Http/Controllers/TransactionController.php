<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function transfer(TransferRequest $request)
    {
        $validated = $request->validated();
        $sender = User::findOrFail($validated['sender_id']);
        $receiver = User::findOrFail($validated['receiver_id']);
        $senderWallet = $sender->wallet;
        $receiverWallet = $receiver->wallet;
        $amount = $validated['amount'];

        // Check if sender has sufficient balance before starting transaction
        if ($senderWallet->balance < $amount) {
            return response()->json([
                'error' => 'Insufficient funds',
                'message' => 'The sender does not have enough balance to complete this transaction.'
            ], 400);
        }

        try {
            // Begin database transaction
            DB::beginTransaction();

            // Transfer funds
            $senderWallet->withdraw($amount);
            $receiverWallet->deposit($amount);

            // Create transaction record
            $transaction = Transaction::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'amount' => $amount,
            ]);

            // Commit the transaction
            DB::commit();

            return response()->json([
                'message' => 'Transaction successful.',
                'transaction' => $transaction
            ], 200);
        } catch (\Exception $e) {
            // Rollback on failure
            DB::rollBack();

            // Log the error for debugging
            Log::error('Transaction failed', [
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Transaction failed.',
                'message' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }

    /**
     * Get all transactions for a user.
     */
    public function getTransactions(int $userId): \Illuminate\Http\JsonResponse
    {
        try {
            // Attempt to fetch the user
            $user = User::findOrFail($userId);

            // Fetch transactions where the user is either sender or receiver
            $transactions = Transaction::where('sender_id', $userId)
                ->orWhere('receiver_id', $userId)
                ->with(['sender:id,name,email', 'receiver:id,name,email']) // Eager load sender/receiver details
                ->orderByDesc('created_at') // Order by latest transactions first
                ->get();

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'transactions' => $transactions
            ], 200);
        }  catch (\Exception $e){
            return response()->json([
                'error' => 'User not found',
                'message' => 'The requested user does not exist in our records.'
            ], 404);
        }
    }
}
