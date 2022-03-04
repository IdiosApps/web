<?= Template::EmailHeader() ?>
<p>Thank you for subscribing to the Standard Ebooks newsletter!</p>
<p>Before we activate your subscription, please confirm that you intended to subscribe:</p>
<p class="button-row">
	<a href="<?= $subscriber->Url ?>/confirm" class="button">Confirm your subscription</a>
</p>
<p>This helps us prevent spam. Thank you!</p>
<?= Template::EmailFooter() ?>
