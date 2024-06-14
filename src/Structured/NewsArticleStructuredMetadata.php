<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Structured;

use DateTimeInterface;
use WebChemistry\HtmlMetadata\StructuredMetadata;

final class NewsArticleStructuredMetadata implements StructuredMetadata
{

	use PartableStructuredMetadata;

	/** @var mixed[] */
	private array $data;

	public function __construct(
		string $headline,
		DateTimeInterface $published,
	)
	{
		$this->data = [
			'@context' => 'https://schema.org',
			'@type' => 'NewsArticle',
			'headline' => $headline,
			'datePublished' => $published->format('c'),
			'dateModified' => $published->format('c'),
		];
	}

	public function getId(): string
	{
		return 'news-article';
	}

	public function addImage(string $image): self
	{
		$this->data['image'] ??= [];
		$this->data['image'][] = $image; // @phpstan-ignore-line

		return $this;
	}

	public function setDescription(?string $description): self
	{
		$this->data['description'] = $description;

		return $this;
	}

	public function setAuthor(?PersonStructuredMetadata $author): self
	{
		if ($author) {
			$this->data['author'] = $author->toArray();
		} else {
			unset($this->data['author']);
		}

		return $this;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		$data = $this->injectParts($this->data);
		
		return array_filter(
			$data,
			fn (mixed $value): bool => $value !== null,
		);
	}

}
