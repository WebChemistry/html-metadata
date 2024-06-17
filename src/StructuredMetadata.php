<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata;

use WebChemistry\HtmlMetadata\Structured\Part\StructuredMetadataPart;

interface StructuredMetadata extends StructuredMetadataPart
{

	public function getId(): string;

}
