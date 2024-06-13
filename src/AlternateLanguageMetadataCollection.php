<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata;

final class AlternateLanguageMetadataCollection
{

	/** @var array{ lang: string, href: string }[] */
	private array $items;

	public function add(string $lang, string $href): self
	{
		$this->items[] = [
			'lang' => $lang,
			'href' => $href,
		];

		return $this;
	}

	/**
	 * @return array{ lang: string, href: string }[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}

}
