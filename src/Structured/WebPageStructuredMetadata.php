<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Structured;

use WebChemistry\HtmlMetadata\StructuredMetadata;

final class WebPageStructuredMetadata implements StructuredMetadata
{

	use PartableStructuredMetadata;

	/** @var mixed[] */
	private array $data = [
		'@context' => 'https://schema.org',
		'@type' => 'WebPage',
	];

	public function getId(): string
	{
		return 'web-page';
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return $this->injectParts($this->data);
	}

}
