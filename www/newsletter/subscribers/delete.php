<?
require_once('Core.php');

$uuid = HttpInput::Str(GET, 'uuid');

try{
	$subscriber = NewsletterSubscriber::Get($uuid);

	$subscriber->Delete();
}
catch(Exceptions\InvalidNewsletterSubscriberException $ex){
	http_response_code(404);
	include(WEB_ROOT . '/404.php');
	exit();
}

?><?= Template::Header(['title' => 'You’ve unsubscribed from the Standard Ebooks newsletter', 'highlight' => 'newsletter', 'description' => 'You’ve unsubscribed from the Standard Ebooks newsletter.']) ?>
<main>
	<article>
		You've been deleted!
	</article>
</main>
<?= Template::Footer() ?>

