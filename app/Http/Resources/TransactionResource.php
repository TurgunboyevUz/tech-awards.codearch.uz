<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // to'lov id raqami
            'id' => $this->id,

            // to'lov miqdori
            'amount' => $this->amount,

            // 0 - jarayonda, 1 - to'langan, 2 - bekor qilingan
            'state' => $this->state,

            // to'lov qilgan foydalanuvchi
            'user' => new UserResource($this->payable)
        ];
    }
}
