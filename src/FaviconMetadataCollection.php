<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata;

final class FaviconMetadataCollection
{

	/** @var array{ link: string, type: ?string, sizes: ?string, rel: ?string }[] */
	private array $items;

	public function add(string $link, ?string $type = null, ?string $sizes = null, ?string $rel = null): self
	{
		$this->items[] = [
			'link' => $link,
			'type' => $type,
			'sizes' => $sizes,
			'rel' => $rel,
		];

		return $this;
	}

	/**
	 * @return array{ link: string, type: ?string, sizes: ?string, rel: ?string }[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}

}
