<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata;

final class LiteralValue
{

	public function __construct(
		public readonly string $value,
	)
	{
	}

}
