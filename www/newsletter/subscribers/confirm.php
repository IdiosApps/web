<?
require_once('Core.php');

$uuid = HttpInput::Str(GET, 'uuid', false);

try{
	if($email){
		$subscribers = Db::Query('select * from NewsletterSubscribers where Uuid = ? and IsConfirmed = false', [], 'NewsletterSubscriber');

		if(sizeof($subscribers) == 0){
			throw new exceptions\InvalidNewsletterSubscriberException();
		}
		else{
			foreach($subscribers as $subscriber){
				$subscriber->Confirm();
			}
		}
	}
	else{
		throw new Exceptions\InvalidNewsletterSubscriberException();
	}
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
