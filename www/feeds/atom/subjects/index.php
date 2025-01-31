<?
require_once('Core.php');

?><?= Template::Header(['title' => 'Atom Ebook Feeds by Subject', 'description' => 'A list of available Atom 1.0 feeds of Standard Ebooks ebooks by subject.']) ?>
<main>
	<article>
		<h1>Atom 1.0 Feeds by Subject</h1>
		<ul class="feed">
			<? foreach(SE_SUBJECTS as $subject){ ?>
			<li>
				<p><a href="/feeds/atom/subjects/<?= Formatter::MakeUrlSafe($subject) ?>"><?= Formatter::ToPlainText($subject) ?></a></p>
				<p class="url"><?= SITE_URL ?>/feeds/atom/subjects/<?= Formatter::MakeUrlSafe($subject) ?></p>
			</li>
			<? } ?>
		</ul>
	</article>
</main>
<?= Template::Footer() ?>
