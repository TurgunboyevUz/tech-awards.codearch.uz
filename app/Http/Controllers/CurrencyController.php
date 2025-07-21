<?php
namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Traits\HttpResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('Valyutalar')]
class CurrencyController extends Controller
{
    use HttpResponse;

    /**
     * Ro'yxatni olish
     */
    public function get(Request $request)
    {
        return $this->success(CurrencyResource::collection(Currency::all()), '', Response::HTTP_OK);
    }

    /**
     * Hisoblash (konvertatsiya)
     */
    public function calculate(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric',
            'currency' => 'required|exists:currencies,id',
        ]);

        $currency = Currency::find($data['currency']);

        return $this->success([
            'converted_amount' => $data['amount'] / $currency->exchange_rate
        ]);
    }
}
