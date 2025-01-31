<?
require_once('Core.php');

?><?= Template::Header(['title' => 'RSS Ebook Feeds', 'description' => 'A list of available RSS 2.0 feeds of Standard Ebooks ebooks.']) ?>
<main>
	<article>
		<h1>RSS 2.0 feeds</h1>
		<p>RSS feeds are an alternative to Atom feeds. They contain less information than Atom feeds, but might be better supported by some RSS readers.</p>
		<ul class="feed">
			<li>
				<p><a href="/feeds/rss/new-releases">New releases</a></p>
				<p class="url"><?= SITE_URL ?>/feeds/rss/new-releases</p>
				<p>The thirty latest Standard Ebooks, most-recently-released first.</p>
			</li>
			<li>
				<p><a href="/feeds/rss/all">All ebooks</a></p>
				<p class="url"><?= SITE_URL ?>/feeds/rss/all</p>
				<p>All Standard Ebooks, most-recently-released first.</p>
			</li>
		</ul>
		<section id="rss-ebooks-by-subject">
			<h3>Ebooks by subject</h3>
			<ul class="feed">
				<? foreach(SE_SUBJECTS as $subject){ ?>
				<li>
					<p><a href="/feeds/rss/subjects/<?= Formatter::MakeUrlSafe($subject) ?>"><?= Formatter::ToPlainText($subject) ?></a></p>
					<p class="url"><?= SITE_URL ?>/feeds/rss/subjects/<?= Formatter::MakeUrlSafe($subject) ?></p>
				</li>
				<? } ?>
			</ul>
		</section>
	</article>
</main>
<?= Template::Footer() ?>
