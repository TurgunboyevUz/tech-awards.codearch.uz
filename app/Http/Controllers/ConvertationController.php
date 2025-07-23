<?php
namespace App\Http\Controllers;

use App\Http\Resources\ConvertationResource;
use App\Models\Currency;
use App\Traits\HttpResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('Konvertatsiyalar')]
class ConvertationController extends Controller
{
    use HttpResponse;

    /**
     * Tilla sotib olish
     */
    public function buy(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
        ]);

        $currency        = Currency::first();
        $convertedAmount = $request->amount / $currency->buy_price;

        $transaction = $request->user()->createMirpayTransaction(
            $request->amount,
            "#{$request->user()->id} foydalanuvchi {$convertedAmount} gramm tilla sotib olishi uchun to'lov"
        );

        return $this->success([
            'converted_amount' => round($convertedAmount, 6),
            'redirect_url'     => $transaction->redirect_url,
        ], "To'lov uchun tranzaksiya yaratildi");
    }

    /**
     * Tilla sotish
     */
    public function sell(Request $request)
    {
        $request->validate([
            'amount'      => 'required|numeric',
            'card_number' => 'required|string|size:16',
        ]);

        if ($request->amount > $request->user()->balance) {
            return $this->error([], 'Sizning balansingiz yetarli emas', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $currency        = Currency::first();
        $convertedAmount = $request->amount * $currency->sell_price;

        $request->user()->convertations()->create([
            'type'             => 'sell',
            'amount'           => $request->amount,
            'converted_amount' => $convertedAmount,
            'card_number'      => $request->card_number,
        ]);

        $request->user()->decrement('balance', $request->amount);

        return $this->success([
            'converted_amount' => round($convertedAmount, 6),
        ], "Konvertatsiyani amalga oshirish uchun so'rov yuborildi");
    }

    /**
     * Konvertatsiyalar ro'yxati
     */
    public function get(Request $request)
    {
        $convertations = $request->user()->convertations()->with('fromCurrency', 'toCurrency')->get();

        return $this->success(ConvertationResource::collection($convertations));
    }

    /**
     * Konvertatsiyani hisoblash
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'type'   => 'required|in:buy,sell',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $currency = Currency::first();

        if ($request->type == 'buy') {
            $convertedAmount = $request->amount / $currency->buy_price;
        } else {
            $convertedAmount = $request->amount * $currency->sell_price;
        }

        return $this->success([
            'amount'           => $request->amount,
            'converted_amount' => round($convertedAmount, 6),
        ]);
    }
}
