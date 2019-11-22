<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Protocols\Sources;

use finfo;
use LogicException;
use WebChemistry\HtmlMetadata\Protocols\Sources\Exceptions\InvalidImageException;

final class Image {

	private const GZIP = ['application/gzip' => true, 'application/x-gzip' => true];

	/** @var string|null */
	private $url;

	public function __construct(?string $url) {
		$this->url = $url;
	}

	public function has(): bool {
		return (bool) $this->url;
	}

	public function getUrl(): ?string {
		return $this->url;
	}

	/**
	 * @throws InvalidImageException
	 */
	public function getImage(): string {
		if (!$this->url) {
			throw new LogicException('url is null');
		}

		$contents = @file_get_contents($this->url);
		if (!$contents) {
			throw new InvalidImageException("Cannot get image from $this->url");
		}

		$mimeType = (new finfo(FILEINFO_MIME_TYPE))->buffer($contents);
		if (isset(self::GZIP[$mimeType])) {
			$contents = gzdecode($contents);
		}

		return $contents;
	}

}
