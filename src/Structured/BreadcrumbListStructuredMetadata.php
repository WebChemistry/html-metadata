<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Structured;

use Nette\Utils\Arrays;
use WebChemistry\HtmlMetadata\StructuredMetadata;

final class BreadcrumbListStructuredMetadata implements StructuredMetadata
{


	/** @var array{string, string}[] */
	private array $items = [];

	public function addItem(string $name, string $link): self
	{
		$this->items[] = [$name, $link];

		return $this;
	}

	public function getId(): string
	{
		return 'breadcrumb-list';
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return [
			'@context' => 'https://schema.org',
			'@type' => 'BreadcrumbList',
			'itemListElement' => Arrays::map(
				$this->items,
				fn (array $item, int $key) => [
					'@type' => 'ListItem',
					'position' => $key + 1,
					'name' => $item[0],
					'item' => $item[1],
				],
			),
		];
	}

}
