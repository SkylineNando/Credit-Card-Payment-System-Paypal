<?php
require 'vendor/autoload.php';

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

$clientId = 'SEU_CLIENT_ID';
$clientSecret = 'SEU_CLIENT_SECRET';

$apiContext = new ApiContext(
    new OAuthTokenCredential($clientId, $clientSecret)
);

$apiContext->setConfig([
    'mode' => 'sandbox',
    'log.LogEnabled' => true,
    'log.FileName' => 'PayPal.log',
    'log.LogLevel' => 'DEBUG',
    'cache.enabled' => true,
]);

$amount = $_POST['amount'];
$currency = $_POST['currency'];

$payer = new Payer();
$payer->setPaymentMethod('paypal');

$amountObject = new Amount();
$amountObject->setTotal($amount);
$amountObject->setCurrency($currency);

$transaction = new Transaction();
$transaction->setAmount($amountObject);

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl('http://seu-site.com/sucesso.php')
    ->setCancelUrl('http://seu-site.com/cancelado.php');

$payment = new Payment();
$payment->setIntent('sale')
    ->setPayer($payer)
    ->setTransactions([$transaction])
    ->setRedirectUrls($redirectUrls);

try {
    $payment->create($apiContext);

    header("Location: " . $payment->getApprovalLink());
} catch (Exception $e) {
    echo 'Ocorreu um erro: ' . $e->getMessage();
}
?>
