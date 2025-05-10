<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormmater;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionController extends Controller
{
    public function checkout (Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'profile_id' => 'required|exists:profiles,id',
            'dokter_profiles' => 'required|exists:doctor_profiles,id',
            'reservation_id' => 'required|exists:reservations,id',
            'total_transaction' => 'required|integer',
            'status_transaction' => 'sometimes|string|max:255',
            'payment_type' => 'sometimes',
            'order_id' => 'sometimes',
            'token_payment' => 'sometimes|string',

        ]);


        if($validator->fails()) {
            return ResponseFormmater::error(
                null,
                $validator->errors(),
                500
            );
        }

        $transaction = Transaction::create([
            'profile_id' => $request->profile_id,
            'dokter_profiles' => $request->dokter_profiles,
            'reservation_id' => $request->reservation_id,
            'total_transaction' => $request->total_transaction,
            'status_transaction' => $request->status_transaction,
            'payment_type' => $request->payment_type,
            'order_id' => $request->order_id,
            'token_payment' => $request->token_payment
        ]);

        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        $transaction = Transaction::find($transaction->id);

        $timestamp = now()->format('YmdHis');
        $orderId = $transaction->id.$timestamp;

        $transaction->order_id = $orderId;
        $transaction->save();

        // Midtrans Snap
        $midtrans = array(
            'transaction_details' => array(
                'order_id' => $orderId,
                'gross_amount' => $transaction->total_transaction,
            ),
        );

        if ($transaction->users) {
            $midtrans['customer_details'] = array(
                'first_name' => $transaction->profilesPasienTransactions->users->name,
                'email' => $transaction->profilesPasienTransactions->users->email
            );
        }

        try {
            $snapToken = Snap::getSnapToken($midtrans);
            $transaction->token_payment = $snapToken;
            $transaction->save();

            return ResponseFormmater::success(
                $transaction,
                'Data Transaction Berhasil di tambahkan'
            );
        } catch (Exception $error) {
            return ResponseFormmater::error(
                $error->getMessage(),
                'Data Transaction tidak ada',
                404
            );
        }
    }

    public function CallBackTransaction(Request $request , $id)
    {
        $transaction = Transaction::findOrFail($id);
        $data = $request->all();

        $transaction->update($data);
        return ResponseFormmater::success(
            $transaction,
            'Data Transaction Berhasil di update'
        );

    }

    public function deleteCheckout(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->delete();
        return ResponseFormmater::success(
            $transaction,
            'Data Transaction Berhasil di Delete'
        );
    }

    public function transactionHistory (Request $request)
    {
        $id = $request->input('id');
        $profile_id = $request->input('profile_id');
        $dokter_profiles = $request->input('dokter_profiles');
        $status_transaction = $request->input('status_transaction');
        $limit = $request->input('limit', 10);

        if($id)
        {
            $transaction = Transaction::with(['profilesPasienTransactions.users', 'dokterProfileTransactions.users','reservations'])->find($id);

            if($transaction)
            {
                return ResponseFormmater::success(
                    $transaction,
                    'Transaction berhasil diambil'
                );
            }

            else {
                return ResponseFormmater::error(
                    null,
                    'Transaction tidak ditemukan',
                    404,
                );
            }
        }

        $transaction = Transaction::with(['profilesPasienTransactions.users', 'dokterProfileTransactions.users','reservations']);

        if($profile_id)
        {
            $transaction->where('profile_id', $profile_id);
        }

        if($dokter_profiles)
        {
            $transaction->where('dokter_profiles', $dokter_profiles);
        }

        if($status_transaction)
        {
            $transaction->where('status_transaction', $status_transaction);
        }

        return ResponseFormmater::success(
            $transaction->paginate($limit),
            'Transaction berhasil diambil'
        );

    }
}
