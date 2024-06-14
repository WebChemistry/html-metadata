<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Structured;

use WebChemistry\HtmlMetadata\Structured\Part\StructuredMetadataPart;

trait PartableStructuredMetadata
{

	/** @var mixed[] */
	private array $parts = [];

	public function addPart(StructuredMetadataPart $part): static
	{
		$this->parts[] = $part->toArray();

		return $this;
	}

	/**
	 * @param StructuredMetadataPart[] $parts
	 */
	public function addParts(array $parts): static
	{
		foreach ($parts as $part) {
			$this->addPart($part);
		}

		return $this;
	}

	/**
	 * @param mixed[] $data
	 * @return mixed[]
	 */
	private function injectParts(array $data): array
	{
		if ($this->parts) {
			$data['hasPart'] = $this->parts;
		}

		return $data;
	}

}
