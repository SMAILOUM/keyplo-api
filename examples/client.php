<?php
// EN: Server-side account example. RU: Серверный пример API аккаунта.
$key = getenv('KEYPLO_API_KEY');
if (!$key) throw new RuntimeException('Set KEYPLO_API_KEY / Задайте KEYPLO_API_KEY');
foreach (['me', 'balance'] as $endpoint) {
    $ch = curl_init('https://keyplo.com/api/v1/'.$endpoint);
    curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_TIMEOUT=>20,
        CURLOPT_HTTPHEADER=>['Authorization: Bearer '.$key, 'Accept: application/json']]);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    if ($body === false) throw new RuntimeException('Connection error / Ошибка соединения');
    curl_close($ch);
    if ($status !== 200) {fwrite(STDERR, 'HTTP '.$status.' '.$body.PHP_EOL); exit(1);}
    echo $endpoint.' '.$body.PHP_EOL;
}
