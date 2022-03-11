<?
require_once('Core.php');

use function Safe\file_get_contents;
use function Safe\json_decode;

$data = json_decode(file_get_contents('php://input') ?: '', true);

if(!$data || !property_exists($data, 'RecordType')){
	header(400);
	exit();
}

if($data->RecordType == 'SpamComplaint' && property_exists($data, 'Email')){
	Db::Query('delete from NewsletterSubscribers where Email = ?', [$data->Email]);
}

if($data->RecordType == 'SubscriptionChange' && property_exists($data, 'MessageId')){
	// Get the message, and thus the email, of the subscriber change
	$handle = curl_init();
	curl_setopt($handle, CURLOPT_URL, 'https://api.postmarkapp.com/messages/outbound/' . $data->MessageId . '/details');
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept' => 'application/json', 'X-Postmark-Server-Token' => EMAIL_SMTP_USERNAME]);
	$message = json_decode(curl_exec($handle), true);
	curl_close($handle);

	if(!$message || !property_exists($message, 'To')){
		header(400);
		exit();
	}

	Db::Query('delete from NewsletterSubscribers where Email = ?', [$message->To->Email]);
}
