<?= Template::EmailHeader() ?>
<h1>Activate your newsletter subscription</h1>
<p>Thank you for subscribing to the Standard Ebooks newsletter!</p>
<p>Please use the button below to activate your newsletter subscription. This helps us prevent spam.</p>
<p>If you didn’t subscribe to our newsletter, or you’re not sure why you received this email, you can safely delete it and you won’t receive further email from us.</p>
<p class="button-row">
	<a href="<?= $subscriber->Url ?>/confirm" class="button">Activate your subscription</a>
</p>
<?= Template::EmailFooter() ?>
