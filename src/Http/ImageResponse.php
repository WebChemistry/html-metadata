<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Http;

use Nette\SmartObject;

final class ImageResponse {

	use SmartObject;

	/** @var string */
	private $content;

	/** @var string */
	private $mimeType;

	public function __construct(string $content, string $mimeType) {
		$this->content = $content;
		$this->mimeType = $mimeType;
	}

	public function getContent(): string {
		return $this->content;
	}

	public function getMimeType(): string {
		return $this->mimeType;
	}

}
