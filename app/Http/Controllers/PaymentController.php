<?php
namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Traits\HttpResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group("To'lovlar")]
class PaymentController extends Controller
{
    use HttpResponse;

    /**
     * To'lov yaratish
     */
    public function create(Request $request)
    {
        $data = $request->validate([
            // to'lov miqdori (so'm)
            'amount' => 'required|numeric',
        ]);

        $user        = $request->user();
        $transaction = $user->createMirpayTransaction($data['amount'], '#' . $request->user()->id . ' hisobini to\'ldirish uchun to\'lov');

        return $this->success([
            // to'lov miqdori (so'm)
            'amount' => $data['amount'],

            // Ushbu linkka foydalanuvchi redirect qilinadi
            'url'    => $transaction->redirect_url,
        ]);
    }

    /**
     * Tranzaksiyalar ro'yxati
     */
    public function transactions(Request $request)
    {
        return $this->success(TransactionResource::collection($request->user()->mirpayTransactions()->get()));
    }
}
