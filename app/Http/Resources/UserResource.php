<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // foydalanuvchi id raqami
            'id' => $this->id,

            // foydalanuvchi nomi
            'name' => $this->name,

            // foydalanuvchi emaili
            'email' => $this->email,

            // foydalanuvchi balansi
            'balance' => round($this->balance, 6),
        ];
    }
}
