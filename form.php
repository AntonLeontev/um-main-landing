<?php

if ($_SERVER ["REQUEST_METHOD"] !== 'POST') {
	header('Location: index.html');
	exit();
}

if (empty($_REQUEST['token'])) {
	header('Location: index.html');
	exit();
}

$secret = include 'secret.php';

$message = "
Заявка c главной {$_SERVER["SERVER_NAME"]}:

Имя: {$_REQUEST['name']}
Почта: {$_REQUEST['email']}
Telegram: {$_REQUEST['telegram']}
";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://api.telegram.org/bot{$secret['telegram']['prod']['bot_token']}/sendMessage");
// curl_setopt($ch, CURLOPT_URL,"https://api.telegram.org/bot{$secret['telegram']['debug']['bot_token']}/sendMessage");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt(
	$ch, 
	CURLOPT_POSTFIELDS,
	http_build_query([
		'chat_id' => $secret['telegram']['prod']['chat_id'],
		// 'chat_id' => $secret['telegram']['debug']['chat_id'],
		'text' => $message,
		'disable_web_page_preview' => true,
	])
);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);

if (!json_decode($output)->ok) {
	die('Response is bad');
} 
