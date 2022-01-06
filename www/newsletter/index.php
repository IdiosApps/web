<?
require_once('Core.php');
?><?= Template::Header(['title' => 'Subscribe to the Standard Ebooks newsletter', 'highlight' => 'newsletter', 'description' => 'Subscribe to the Standard Ebooks newsletter to receive occasional updates about the project.']) ?>
<main>
	<article class="has-hero">
		<hgroup>
			<h1>Subscribe to the Newsletter</h1>
			<h2>to receive missives from the vanguard of digital literature</h2>
		</hgroup>
		<picture>
			<source srcset="/images/the-newsletter@2x.avif 2x, /images/the-newsletter.avif 1x" type="image/avif"/>
			<source srcset="/images/the-newsletter@2x.jpg 2x, /images/the-newsletter.jpg 1x" type="image/jpg"/>
			<img src="/images/the-newsletter@2x.jpg" alt="An old man in Renaissance-era costume reading a sheet of paper."/>
		</picture>
		<p>Subscribe to receive news, updates, and more from Standard Ebooks. Your information will never be shared, and you can unsubscribe at any time.</p>
		<form action="/newsletter/subscriptions" method="post">
			<label class="email">Email
				<input type="email" name="email" value="" required="required"/>
			</label>
			<label class="text">First name
				<input type="text" name="firstname" autocomplete="given-name" value=""/>
			</label>
			<label class="text">Last name
				<input type="text" name="lastname" autocomplete="family-name" value=""/>
			</label>
			<fieldset>
				<p>What kind of email would you like to receive?</p>
				<ul>
					<li>
						<label class="checkbox"><input type="checkbox" value="1" name="newsletter" checked="checked"/>The occasional Standard Ebooks newsletter</label>
					</li>
					<li>
						<label class="checkbox"><input type="checkbox" value="2" name="monthlysummary" checked="checked"/>A monthly summary of new ebook releases</label>
					</li>
				</ul>
			</fieldset>
			<button>Subscribe</button>
		</form>
	</article>
</main>
<?= Template::Footer() ?>
