<?php
namespace App\Http\Controllers;

use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use App\Traits\HttpResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;

#[Group('Valyutalar')]
class CurrencyController extends Controller
{
    use HttpResponse;

    /**
     * Valyutani olish
     *
     * @unauthenticated
     */
    public function get(Request $request)
    {
        return $this->success(new CurrencyResource(Currency::first()));
    }
}
