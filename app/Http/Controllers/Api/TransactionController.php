<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Balance;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class TransactionController extends Controller
{
    public function storeTransaction(Request $request)
    {
        $inputAmount = (float)$request->input('amount');

        if ($inputAmount == 0.00000001) {
            return response()->json(['message' => 'Transaksi ditolak'], 400);
        }

        $user = auth('api')->user();

        $userId = $user->id;
        $balance = Balance::where('user_id', $userId)->first();

        if ($balance->amount_available < $inputAmount) {
            return response()->json(['message' => 'Saldo tidak mencukupi'], 400);
        }

        return DB::transaction(function () use ($inputAmount, $userId, $request) {
            $transaction = new Transaction([
                'amount' => $inputAmount,
                'user_id' => $userId,
                'trx_id' => $request->input('trx_id'),
            ]);

            if (Transaction::where('user_id', $userId)->where('amount', $inputAmount)->count() > 1) {
                return response()->json(['message' => 'Transaksi ditolak'], 400);
            }

            $transaction->save();
            $response = [
                'trx_id' => $transaction->trx_id,
                'amount' => $transaction->amount,
            ];

            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        });
    }

    public function getData(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10; // Ubah sesuai dengan jumlah data yang ingin Anda tampilkan per halaman.

        $users = User::with('balance', 'transactions')
            // ->orderBy('user_id')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $data = [];

        foreach ($users as $user) {
            $userData = [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'balance' => $user->balance ? $user->balance->amount_available : 0,
                'transactions' => $user->transactions,
            ];

            $data[] = $userData;
        }

        return response()->json(['data' => $data]);
    }
}
