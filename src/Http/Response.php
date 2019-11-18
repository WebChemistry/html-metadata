<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Http;

use Nette\SmartObject;

final class Response {

	use SmartObject;

	/** @var string */
	public $content;

	/** @var array */
	public $headers;

	public function __construct(string $content, array $headers) {
		$this->content = $content;
		$this->headers = $headers;
	}

}
