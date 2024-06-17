<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Structured;

use DateTimeInterface;
use WebChemistry\HtmlMetadata\StructuredMetadata;

class ArticleStructuredMetadata implements StructuredMetadata
{

	use PartableStructuredMetadata;

	protected string $type = 'Article';

	/** @var mixed[] */
	private array $data;

	public function __construct(
		string $headline,
		DateTimeInterface $published,
	)
	{
		$this->data = [
			'@context' => 'https://schema.org',
			'@type' => $this->type,
			'headline' => $headline,
			'datePublished' => $published->format('c'),
			'dateModified' => $published->format('c'),
		];
	}

	public function getId(): string
	{
		return 'article';
	}

	public function isAccessibleForFree(bool $forFree): self
	{
		$this->data['isAccessibleForFree'] = $forFree ? null : false;

		return $this;
	}

	public function setModified(DateTimeInterface $modified): self
	{
		$this->data['dateModified'] = $modified->format('c');

		return $this;
	}

	public function addImage(string $image): self
	{
		$this->data['image'][] = $image;

		return $this;
	}

	public function setDescription(?string $description): self
	{
		$this->data['description'] = $description;

		return $this;
	}

	public function addAuthor(PersonStructuredMetadata $author): self
	{
		$this->data['author'][] = $author->toArray();

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
