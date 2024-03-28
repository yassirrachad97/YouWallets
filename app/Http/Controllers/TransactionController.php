<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Transaction;
use App\Models\Wallet;

class TransactionController extends Controller
{
    //

    public function createTransaction(Request $request){
        try {
            $compteSender =Wallet::where('Ncompte', $request->Sender_id)->first();
             $compteReceiver= Wallet::where('Ncompte', $request->resever_id)->first();
            if (!$compteSender || !$compteReceiver) {
                return response()->json([
                    'message' => 'compte not found',
                ], Response::HTTP_NOT_FOUND);
            }
            if ($compteSender->solde < $request->amount) {
                return response()->json([
                    'message' => 'solde insuffisant',
                ], Response::HTTP_BAD_REQUEST);
            }
            $compteSender->solde -= $request->montant;
            $compteSender->save();
            $compteReceiver->solde += $request->montant;
            $compteReceiver->save();
            $transaction = new Transaction();
            $transaction->Sender_id = $request->Sender_id;
            $transaction->resever_id = $request->resever_id;
            $transaction->amount = $request->amount;
            $transaction->save();
            return response()->json([
                'message' => 'transaction created successfully',
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error de transaction: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



}
