<?
require_once('Core.php');

use function Safe\curl_exec;
use function Safe\curl_init;
use function Safe\curl_setopt;
use function Safe\file_get_contents;
use function Safe\json_decode;
use function Safe\substr;

// Get a semi-random ID to identify this request within the log.
$requestId = substr(sha1(time() . rand()), 0, 8);

try{
	Logger::WritePostmarkWebhookLogEntry($requestId, 'Received Postmark webhook.');

	if($_SERVER['REQUEST_METHOD'] != 'POST'){
		throw new Exceptions\WebhookException('Expected HTTP POST.');
	}

	$apiKey = trim(file_get_contents(SITE_ROOT . '/config/secrets/webhooks@postmarkapp.com')) ?: '';

	// Ensure this webhook actually came from Postmark
	if(!isset($_SERVER['HTTP_X_SE_KEY']) || $apiKey != $_SERVER['HTTP_X_SE_KEY']){
		throw new Exceptions\InvalidCredentialsException();
	}

	$post = json_decode(file_get_contents('php://input') ?: '');

	if(!$post || !property_exists($post, 'RecordType')){
		throw new Exceptions\WebhookException('Couldn\'t understand HTTP request.', $post);
	}

	if($post->RecordType == 'SpamComplaint'){
		Db::Query('delete from NewsletterSubscribers where Email = ?', [$post->Email]);
	}

	// Received when a user clicks Postmark's "Unsubscribe" link in a newsletter email
	if($post->RecordType == 'SubscriptionChange' && $post->SuppressSending){
		// Get the message this subscriber change refers to, so that we can see their email address
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, 'https://api.postmarkapp.com/messages/outbound/' . $post->MessageID . '/details');
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($handle, CURLOPT_HTTPHEADER, ['Accept: application/json', 'X-Postmark-Server-Token: ' . EMAIL_SMTP_USERNAME]);
		$result = curl_exec($handle);
		curl_close($handle);

		$message = is_bool($result) ? '' : json_decode($result);

		if(!$message || !property_exists($message, 'To') || sizeof($message->To) == 0){
			throw new Exceptions\WebhookException('Couldn\'t fetch message ID: ' . $post->MessageID, $post);
		}

		// Remove the email from our newsletter list
		Db::Query('delete from NewsletterSubscribers where Email = ?', [$message->To[0]->Email]);

		// Remove the suppression from Postmark, since we deleted it from our own list we will never email them again anyway
		$handle = curl_init();
		curl_setopt($handle, CURLOPT_URL, 'https://api.postmarkapp.com/message-streams/' . $post->MessageStream . '/suppressions/delete');
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($handle, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Accept: application/json', 'X-Postmark-Server-Token: ' . EMAIL_SMTP_USERNAME]);
		curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($handle, CURLOPT_POSTFIELDS, '{"Suppressions": [{"EmailAddress": "' . $message->To[0]->Email . '"}]}');
		curl_exec($handle);
		curl_close($handle);
	}

	// "Success, no content"
	http_response_code(204);
}
catch(Exceptions\InvalidCredentialsException $ex){
	// "Forbidden"
	http_response_code(403);
}
catch(Exceptions\WebhookException $ex){
	// Uh oh, something went wrong!
	// Log detailed error and debugging information locally.
	Logger::WritePostmarkWebhookLogEntry($requestId, 'Webhook failed! Error: ' . $ex->getMessage());
	Logger::WritePostmarkWebhookLogEntry($requestId, 'Webhook POST data: ' . $ex->PostData);

	// Print less details to the client.
	print($ex->getMessage());

	// "Client error"
	http_response_code(400);
}
