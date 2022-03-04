<?
require_once('Core.php');

$uuid = HttpInput::Str(GET, 'uuid', false);

try{
	if(!$uuid){
		throw new Exceptions\InvalidNewsletterSubscriberException();
	}

	$subscriber = NewsletterSubscriber::Get($uuid);

	$subscriber->Confirm();
}
catch(Exceptions\InvalidNewsletterSubscriberException $ex){
	http_response_code(404);
	include(WEB_ROOT . '/404.php');
	exit();
}
?><?= Template::Header(['title' => 'Your subscription to the Standard Ebooks newsletter has been confirmed', 'highlight' => 'newsletter', 'description' => 'Your subscription to the Standard Ebooks newsletter has been confirmed.']) ?>
<main>
	<article>
		You've been confirmed!
	</article>
</main>
<?= Template::Footer() ?>
