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

            // valyuta symboli (qisqartma, masalan: dollar - $)
            'symbol' => $this->symbol,

            // valyuta kursi (so'mda)(masalan: 1 dollar = 12500 so'm)
            'exchange_rate' => $this->exchange_rate
        ];
    }
}
