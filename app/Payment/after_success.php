<?php
/** @var TurgunboyevUz\Mirpay\Models\MirpayTransaction $transaction */

$model->balance += $transaction->amount;
$model->save();