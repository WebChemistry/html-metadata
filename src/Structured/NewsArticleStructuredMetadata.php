<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Structured;

use DateTimeInterface;
use WebChemistry\HtmlMetadata\StructuredMetadata;

final class NewsArticleStructuredMetadata extends ArticleStructuredMetadata
{

	protected string $type = 'NewsArticle';

	public function getId(): string
	{
		return 'news-article';
	}

}
