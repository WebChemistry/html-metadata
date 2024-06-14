<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Structured\Part;

interface StructuredMetadataPart
{

	/**
	 * @return mixed[]
	 */
	public function toArray(): array;
	
}
