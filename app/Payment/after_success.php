<?php
/** @var TurgunboyevUz\Mirpay\Models\MirpayTransaction $transaction */

use App\Models\Currency;

$currency        = Currency::first();
$convertedAmount = $transaction->amount / $currency->buy_price;

$model->increment('balance', $convertedAmount);
$model->convertations->create([
    'type'             => 'buy',
    'amount'           => $transaction->amount,
    'converted_amount' => $convertedAmount,
    'status'           => 1,
]);
