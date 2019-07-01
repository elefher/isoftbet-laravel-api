<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TransactionController extends Controller {

    /**
     * Shows a specific transaction
     * @param Request $request
     * @param $transactionId
     * @param $customerId
     * @return \Illuminate\Http\JsonResponse
     */
    public function view(Request $request, $transactionId, $customerId): JsonResponse {
        $transaction = $this->getTransaction($transactionId, $customerId, ['id', 'user_id', 'amount', 'created_at']);

        // Check if user has access to data
        if (Gate::allows('viewTransaction', $transaction ?? Transaction::class)) {
            return response()->json([
                'success' => true,
                'data' => $transaction->makeHidden('customerId')->toArray()
            ]);
        }

        // Unauthorized user
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized User.'
        ], 403);
    }

    /**
     * Returns all transactions for a given customer id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request): JsonResponse {
        $transactions = Transaction::where(['user_id' => auth()->user()->id])
            ->take(isset($request->limit) ? $request->limit : PHP_INT_MAX)
            ->offset($request->offset)->get();

        // If empty of transactions, error message
        if(empty($transactions)){
            return response()->json([
                'success' => false,
                'message' => 'User doesn\'t have any transaction'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    /**
     * Create a new transaction
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request): JsonResponse {
        $transaction = Transaction::create(['user_id' => auth()->user()->id, 'amount' => $request->amount]);
        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    /**
     * Update the amount of a specific transaction
     * @param Request $request
     * @param $transactionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $transactionId): JsonResponse {
        $transaction = $this->getTransaction($transactionId, auth()->user()->id, ['id', 'user_id', 'amount', 'created_at']);

        // If there is not the transaction
        if(empty($transaction)){
            return response()->json([
                'success' => false,
                'message' => 'Transaction cannot be found'
            ], 400);
        }

        // Update the transaction
        $transaction->amount = $request->input('amount');
        $transaction->save();

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    /**
     * Deletes a transaction for the given transaction id and customer id
     * @param $transactionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($transactionId): JsonResponse {
        $transaction = $this->getTransaction($transactionId, auth()->user()->id, ['id']);

        // If transaction doesn't exist error
        if (empty($transaction)) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction with id ' . $transactionId . ' not found'
            ], 400);
        }

        // Delete transaction
        if ($transaction->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Transaction could not be deleted'
            ], 500);
        }
    }

    /**
     * Returns a specific transaction for the given transaction and customer Ids
     * @param $transactionId
     * @param $customerId
     * @param $columnsToReturn
     * @return mixed
     */
    private function getTransaction(int $transactionId, int $customerId, array $columnsToReturn) {
        return Transaction::where(['id' => $transactionId, 'user_id' => $customerId])->first($columnsToReturn);
    }
}
