<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Structured\Part;

final class PaywallPart implements StructuredMetadataPart
{

	public function __construct(
		private string $cssSelector,
		private bool $isForFree = false,
	)
	{
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return [
			'@type' => 'WebPageElement',
			'isAccessibleForFree' => $this->isForFree,
			'cssSelector' => $this->cssSelector,
		];
	}

}
