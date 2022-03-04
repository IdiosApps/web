<?
require_once('Core.php');

try{
	$subscriber = NewsletterSubscriber::Get(HttpInput::Str(GET, 'uuid') ?? '');
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
		<h1>You’ve been unsubscribed</h1>
		<p>You’ll no longer receive Standard Ebooks email newsletters. Sorry to see you go!</p>
	</article>
</main>
<?= Template::Footer() ?>

