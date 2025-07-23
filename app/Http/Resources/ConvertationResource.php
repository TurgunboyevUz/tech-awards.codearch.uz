<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConvertationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            // Konvertatsiya turi (buy - tilla sotib olish, sell - tilla sotish)
            'type' => $this->type,

            // Konvertatsiya qilingan summa
            'amount' => $this->amount,

            // Qabul qilingan summa
            'converted_amount' => $this->converted_amount,

            // Karta raqami
            'card_number' => $this->card_number,

            // Konvertatsiya holati (0 - jarayonda, 1 - amalga oshirildi, 2 - bekor qilindi)
            'status' => match ($this->status) {
                0 => 'Jarayonda',
                1 => 'Amalga oshirildi',
                2 => 'Bekor qilindi'
            }
        ];
    }
}
