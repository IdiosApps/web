<entry>
	<id><?= SITE_URL . $entry->Url ?></id>
	<dc:identifier><?= Formatter::ToPlainXmlText($entry->Identifier) ?></dc:identifier>
	<title><?= Formatter::ToPlainXmlText($entry->Title) ?></title>
	<? foreach($entry->Authors as $author){ ?>
		<author>
			<name><?= Formatter::ToPlainXmlText($author->Name) ?></name>
			<uri><?= SITE_URL . Formatter::ToPlainXmlText($entry->AuthorsUrl) ?></uri>
			<? if($author->FullName !== null){ ?><schema:alternateName><?= Formatter::ToPlainXmlText($author->FullName) ?></schema:alternateName><? } ?>
			<? if($author->WikipediaUrl !== null){ ?><schema:sameAs><?= Formatter::ToPlainXmlText($author->WikipediaUrl) ?></schema:sameAs><? } ?>
			<? if($author->NacoafUrl !== null){ ?><schema:sameAs><?= Formatter::ToPlainXmlText($author->NacoafUrl) ?></schema:sameAs><? } ?>
		</author>
	<? } ?>
	<published><?= $entry->Timestamp->format('Y-m-d\TH:i:s\Z') ?></published>
	<dc:issued><?= $entry->Timestamp->format('Y-m-d\TH:i:s\Z') ?></dc:issued>
	<updated><?= $entry->ModifiedTimestamp->format('Y-m-d\TH:i:s\Z') ?></updated>
	<dc:language><?= Formatter::ToPlainXmlText($entry->Language) ?></dc:language>
	<dc:publisher>Standard Ebooks</dc:publisher>
	<rights>Public domain in the United States. Users located outside of the United States must check their local laws before using this ebook. Original content released to the public domain via the Creative Commons CC0 1.0 Universal Public Domain Dedication.</rights>
	<summary type="text"><?= Formatter::ToPlainXmlText($entry->Description) ?></summary>
	<content type="html"><?= Formatter::ToPlainXmlText($entry->LongDescription) ?></content>
	<? foreach($entry->LocTags as $subject){ ?>
	<category scheme="http://purl.org/dc/terms/LCSH" term="<?= Formatter::ToPlainXmlText($subject) ?>"/>
	<? } ?>
	<? foreach($entry->Tags as $subject){ ?>
	<category scheme="https://standardebooks.org/vocab/subjects" term="<?= Formatter::ToPlainXmlText($subject->Name) ?>"/>
	<? } ?>
	<link href="<?= SITE_URL . $entry->Url ?>/downloads/cover.jpg" rel="http://opds-spec.org/image" type="image/jpeg"/>
	<link href="<?= SITE_URL . $entry->Url ?>/downloads/cover-thumbnail.jpg" rel="http://opds-spec.org/image/thumbnail" type="image/jpeg"/>
	<link href="<?= SITE_URL . $entry->Url ?>" rel="alternate" title="This ebook’s page at Standard Ebooks" type="application/xhtml+xml"/>
	<? if(file_exists(WEB_ROOT . $entry->EpubUrl)){ ?><link href="<?= SITE_URL . $entry->EpubUrl ?>" length="<?= filesize(WEB_ROOT . $entry->EpubUrl) ?>" rel="http://opds-spec.org/acquisition/open-access" title="Recommended compatible epub" type="application/epub+zip" /><? } ?>
	<? if(file_exists(WEB_ROOT . $entry->AdvancedEpubUrl)){ ?><link href="<?= SITE_URL . $entry->AdvancedEpubUrl ?>" length="<?= filesize(WEB_ROOT . $entry->AdvancedEpubUrl) ?>" rel="http://opds-spec.org/acquisition/open-access" title="Advanced epub" type="application/epub+zip" /><? } ?>
	<? if(file_exists(WEB_ROOT . $entry->KepubUrl)){ ?><link href="<?= SITE_URL . $entry->KepubUrl ?>" length="<?= filesize(WEB_ROOT . $entry->KepubUrl) ?>" rel="http://opds-spec.org/acquisition/open-access" title="Kobo Kepub epub" type="application/kepub+zip" /><? } ?>
	<? if(file_exists(WEB_ROOT . $entry->Azw3Url)){ ?><link href="<?= SITE_URL . $entry->Azw3Url ?>" length="<?= filesize(WEB_ROOT . $entry->Azw3Url) ?>" rel="http://opds-spec.org/acquisition/open-access" title="Amazon Kindle azw3" type="application/x-mobipocket-ebook" /><? } ?>
	<? if(file_exists(WEB_ROOT . $entry->TextSinglePageUrl)){ ?><link href="<?= SITE_URL . $entry->TextSinglePageUrl ?>" length="<?= filesize(WEB_ROOT . $entry->TextSinglePageUrl) ?>" rel="http://opds-spec.org/acquisition/open-access" title="XHTML" type="application/xhtml+xml" /><? } ?>
</entry>
