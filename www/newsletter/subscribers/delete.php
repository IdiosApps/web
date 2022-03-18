<?
require_once('Core.php');

use function Safe\preg_match;

try{
	// We may use GET if we're called from an unsubscribe link in an email
	if(!in_array($_SERVER['REQUEST_METHOD'], ['DELETE', 'GET'])){
		throw new Exceptions\InvalidRequestException();
	}

	$requestType = preg_match('/\btext\/html\b/ius', $_SERVER['HTTP_ACCEPT']) ? WEB : REST;

	$subscriber = NewsletterSubscriber::Get(HttpInput::Str(GET, 'uuid') ?? '');
	$subscriber->Delete();

	if($requestType == REST){
		exit();
	}
}
catch(Exceptions\InvalidRequestException $ex){
	http_response_code(405);
	exit();
}
catch(Exceptions\InvalidNewsletterSubscriberException $ex){
	http_response_code(404);
	if($requestType == WEB){
		include(WEB_ROOT . '/404.php');
	}
	exit();
}

?><?= Template::Header(['title' => 'You’ve unsubscribed from the Standard Ebooks newsletter', 'highlight' => 'newsletter', 'description' => 'You’ve unsubscribed from the Standard Ebooks newsletter.']) ?>
<main>
	<article>
		<h1>You’ve been unsubscribed</h1>
		<p>You’ll no longer receive Standard Ebooks email newsletters. Sorry to see you go!</p>
	</article>
</main>
<?= Template::Footer() ?>

