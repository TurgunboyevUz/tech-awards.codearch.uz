<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // valyuta id raqami
            'id' => $this->id,

            // valyuta nomi
            'name' => $this->name,

            // valyuta kodi
            'code' => $this->code,

            // foydalanuvchi sotib olish kursi
            'buy_price' => $this->buy_price,

            // foydalanuvchi sotish kursi
            'sell_price' => $this->sell_price
        ];
    }
}
