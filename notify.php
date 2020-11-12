<?php

$url          = 'https://fcm.googleapis.com/fcm/send';
$YOUR_API_KEY = 'AAAA9U9ovrM:APA91bHUKF3vF1axlPeF-slhqGpRPNuEfRJJvpAOBWiMu19C7PyCiByYDr0Mz75kTV4MZx2M-Ac-zNUakEjMBWrHG0Yr8F7FsUQPnbA8W1p350Bf8kiC7XMDUulVToygji2s09LzOqlw'; // Server key
$YOUR_TOKEN_ID = 'cPzofyAkzZ0:APA91bFBM8_V8l-XdgIv60N7h5mQh6C_Mf8UyGbwPMCOOlQrPR1q2QJTWn_72tVNGKYObfDKius5bdNyrxe_1Pt2Sf-KLvHWJ0DXfxfSgxsuJL4_8ZvBMU_Pdg6Gb4cqllU9pV4WxjRH'; // Client token id

$request_body = [
	'to' => $YOUR_TOKEN_ID,
	'notification' => [
		'title' => 'Web PUSH с sergdudko . tk',
		'body' => 'Body PUSH',
		'icon' => 'push - button . png',
		'click_action' => 'https://sergdudko.tk',
	],
];
$fields = json_encode($request_body);

$request_headers = [
    'Content-Type: application/json',
    'Authorization: key=' . $YOUR_API_KEY,
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
