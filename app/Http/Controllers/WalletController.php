<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Str;


class WalletController extends Controller
{
    //
    public function createCompte(Request $request){
        try {

            $existingCompte = Wallet::where('user_id', $request->user_id)
                                    ->where('type', $request->type)
                                    ->first();
            if ($existingCompte) {
                return response()->json([
                    'message' => 'utilisateur possed deja compte de ce type',
                ], Response::HTTP_CONFLICT);
            }

            $compte = new Wallet();
            $compte->Ncompte = rand(1000000000, 9999999999);
            $compte->solde = $request->solde;
            $compte->user_id = $request->user_id;
            $compte->type = $request->type;
            $compte->save();

            return response()->json([
                'message' => 'compte created successfully',
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error de creation de compte: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function searchCompte($firstname,$lastname){
        try {
            $user = User::where('firstname', $firstname)
                    ->where('lastname', $lastname)
                    ->firstOrFail();

            if (!$user) {
                return response()->json([
                    'message' => 'user not found',
               ], Response::HTTP_NOT_FOUND);
            }

             $compte = Wallet::where('user_id', $user->id)->first();

            if (!$compte) {
                return response()->json([
                  'message' => 'de user avez pas compte',
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'firstname'=> $user->firstname,
                'lastname'=> $user->lastname,
                'Ncompte' => $compte->Ncompte,

            ], Response::HTTP_OK);


        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error de recherche: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function compteHistorique($Ncompte){
        try {
            $compte = Wallet::where('Ncompte', $Ncompte)->first();

            $transactions = Transaction::where('numero_compte_sender', $Ncompte)
                                        ->orWhere('numero_compte_receiver', $Ncompte)
                                        ->get();


            if (!$compte) {
                return response()->json([
                    'message' => 'compte not found',
                ], Response::HTTP_NOT_FOUND);
            }


            return response()->json([
                'transactions' => $transactions,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'error de recherche: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
