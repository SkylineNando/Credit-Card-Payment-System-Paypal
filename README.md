# Credit-Card-Payment-System-Paypal
Credit Card Payment System

Para implementar um sistema de pagamento com cartão de crédito usando o PayPal e PHP, você pode seguir os passos abaixo:

### Passo 1: Criar uma conta no PayPal

1. Acesse o site do [PayPal](https://www.paypal.com) e crie uma conta ou faça login se já tiver uma.

2. No painel de controle do PayPal, obtenha as credenciais necessárias (Client ID e Secret).

### Passo 2: Configurar o ambiente PHP

1. Certifique-se de que você tem o PHP instalado no seu servidor.

2. Se você não tiver o Composer instalado, faça o download e instale a partir do site oficial: [Composer](https://getcomposer.org/).

3. Crie um novo diretório para o seu projeto e dentro dele, crie um arquivo `composer.json` com o seguinte conteúdo:

```json
{
    "require": {
        "paypal/rest-api-sdk-php": "*"
    }
}
```

4. No terminal, dentro do diretório do seu projeto, execute o comando `composer install` para instalar a biblioteca do PayPal.

### Passo 3: Criar a página de pagamento

1. Crie um arquivo HTML para o formulário de pagamento (por exemplo, `checkout.html`):

```html
<!DOCTYPE html>
<html>
<head>
    <title>Pagamento com PayPal</title>
</head>
<body>
    <h1>Formulário de Pagamento</h1>
    <form action="processar_pagamento.php" method="post">
        <label for="amount">Valor:</label>
        <input type="text" name="amount" required><br>

        <label for="currency">Moeda:</label>
        <input type="text" name="currency" required><br>

        <input type="submit" value="Efetuar Pagamento">
    </form>
</body>
</html>
```

### Passo 4: Criar o script PHP para processar o pagamento

Crie um arquivo PHP para processar o pagamento (por exemplo, `processar_pagamento.php`):

```php
<?php
require 'vendor/autoload.php';

use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

// Configurar as credenciais do PayPal
$clientId = 'SEU_CLIENT_ID';
$clientSecret = 'SEU_CLIENT_SECRET';

$apiContext = new ApiContext(
    new OAuthTokenCredential($clientId, $clientSecret)
);

$apiContext->setConfig([
    'mode' => 'sandbox', // Altere para 'live' em produção
    'log.LogEnabled' => true,
    'log.FileName' => 'PayPal.log',
    'log.LogLevel' => 'DEBUG',
    'cache.enabled' => true,
]);

// Dados do pagamento
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

    // Redirecionar para a página de aprovação do PayPal
    header("Location: " . $payment->getApprovalLink());
} catch (Exception $e) {
    echo 'Ocorreu um erro: ' . $e->getMessage();
}
?>
```

### Passo 5: Criar as páginas de retorno (sucesso e cancelamento)

Crie as páginas `sucesso.php` e `cancelado.php` para lidar com os retornos bem-sucedidos e cancelados do PayPal.

### Passo 6: Testar o sistema

Agora, você deve ser capaz de testar o sistema de pagamento com cartão de crédito usando o PayPal. Lembre-se de que isso é apenas um exemplo básico e que existem muitos aspectos de segurança e validação que você deve considerar em uma implementação real.

Além disso, é importante ler a documentação do PayPal e entender completamente como lidar com pagamentos de forma segura e em conformidade com as regulamentações locais.
