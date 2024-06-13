<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Bridge\Nette;

use Nette\Application\UI\Control;
use WebChemistry\HtmlMetadata\HtmlMetadata;

final class HtmlMetadataComponent extends Control
{

	public function __construct(
		private HtmlMetadata $metadata,
	)
	{
	}

	public function render(int $indent = 0): void
	{
		$this->metadata->render($indent);
	}

}
