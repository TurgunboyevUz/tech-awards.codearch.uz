<?php
namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use TurgunboyevUz\Mirpay\Traits\HasMirpayTransactions;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasApiTokens, HasMirpayTransactions;

    protected $fillable = [
        'name',
        'email',
        'password',
        'balance'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password'          => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return str_contains($this->email, '@example.com');
    }

    public function convertations()
    {
        return $this->hasMany(Convertation::class);
    }
}
