<?
use function Safe\preg_match;
use function Safe\session_unset;

require_once('Core.php');

session_start();

$requestType = preg_match('/\btext\/html\b/ius', $_SERVER['HTTP_ACCEPT']) ? FORM : REST;

$subscriber = new NewsletterSubscriber();

try{
	$subscriber->FirstName = HttpInput::Str(POST, 'firstname', false);
	$subscriber->LastName = HttpInput::Str(POST, 'lastname', false);
	$subscriber->Email = HttpInput::Str(POST, 'email', false);
	$subscriber->IsSubscribedToNewsletter = HttpInput::Bool(POST, 'newsletter');
	$subscriber->IsSubscribedToSummary = HttpInput::Bool(POST, 'monthlysummary');

	$subscriber->Create();

	session_unset();

	if($requestType == FORM){
		http_response_code(303);
		header('Location: /newsletter/subscribers/success');
	}
	else{
		// Access via REST api; 201 CREATED with location
		http_response_code(201);
		header('Location: ' . $subscriber->Url);
	}
}
catch(Exceptions\SeException $ex){
	// Validation failed
	if($requestType == FORM){
		$_SESSION['subscriber'] = $subscriber;
		$_SESSION['exception'] = $ex;

		// Access via form; 303 redirect to the form, which will emit a 400 BAD REQUEST
		http_response_code(303);
		header('Location: /newsletter/subscribers/new');
	}
	else{
		// Access via REST api; 400 BAD REQUEST
		http_response_code(400);
	}
}
