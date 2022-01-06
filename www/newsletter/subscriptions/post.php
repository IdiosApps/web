<?
require_once('Core.php');

try{
	$subscriber = new NewsletterSubscriber();

	$subscriber->FirstName = HttpInput::PostString('firstname', false);
	$subscriber->LastName = HttpInput::PostString('lastname', false);
	$subscriber->Email = HttpInput::PostString('email', false);
	$subscriber->IsSubscribedToNewsletter = HttpInput::PostBool('newsletter');
	$subscriber->IsSubscribedToSummary = HttpInput::PostBool('monthlysummary');

	$subscriber->Create();
}
catch(Exception $ex){
	$_SESSION['subscriber'] = $subscriber;
	$_SESSION['exception'] = $ex;

	http_response_code(303);
	header('Location: /newsletter');
}

?><?= Template::Header(['description' => '']) ?>
<main class="front-page">
	<h1>Free and liberated ebooks,<br/> carefully produced for the true book lover.</h1>
</main>
<?= Template::Footer() ?>
