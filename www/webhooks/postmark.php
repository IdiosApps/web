<?
require_once('Core.php');

use function Safe\curl_exec;
use function Safe\curl_init;
use function Safe\curl_setopt;
use function Safe\file_get_contents;
use function Safe\json_decode;

$webhook = json_decode(file_get_contents('php://input') ?: '');

if(!$webhook || !property_exists($webhook, 'RecordType')){
	http_response_code(400);
	exit();
}

if($webhook->RecordType == 'SpamComplaint'){
	Db::Query('delete from NewsletterSubscribers where Email = ?', [$webhook->Email]);
}

// Received when a user clicks Postmark's "Unsubscribe" link in a newsletter email
if($webhook->RecordType == 'SubscriptionChange' && $webhook->SuppressSending){
	// Get the message this subscriber change refers to, so that we can see their email address
	$handle = curl_init();
	curl_setopt($handle, CURLOPT_URL, 'https://api.postmarkapp.com/messages/outbound/' . $webhook->MessageID . '/details');
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($handle, CURLOPT_HTTPHEADER, ['Accept: application/json', 'X-Postmark-Server-Token: ' . EMAIL_SMTP_USERNAME]);
	$result = curl_exec($handle);
	curl_close($handle);

	$message = is_bool($result) ? '' : json_decode($result);

	if(!$message || !property_exists($message, 'To') || sizeof($message->To) == 0){
		http_response_code(400);
		exit();
	}

	// Remove the email from our newsletter list
	Db::Query('delete from NewsletterSubscribers where Email = ?', [$message->To[0]->Email]);

	// Remove the suppression from Postmark, since we deleted it from our own list we will never email them again anyway
	$handle = curl_init();
	curl_setopt($handle, CURLOPT_URL, 'https://api.postmarkapp.com/message-streams/' . $webhook->MessageStream . '/suppressions/delete');
	curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json', 'X-Postmark-Server-Token: ' . EMAIL_SMTP_USERNAME]);
	curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($handle, CURLOPT_POSTFIELDS, '{"Suppressions": [{"EmailAddress": "' . $message->To[0]->Email . '"}]}');
	curl_exec($handle);
	curl_close($handle);
}
