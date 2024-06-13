<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata;

interface StructuredMetadata
{

	public function getId(): string;

	/**
	 * @return mixed[]
	 */
	public function toArray(): array;

}
