<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata;

use WebChemistry\HtmlMetadata\Normalizers\ContentNormalizer;
use WebChemistry\HtmlMetadata\Protocols\OpenGraphProtocol;

final class HtmlMetadata {

	/** @var string */
	private $charset;

	/** @var string[] */
	private $metadata;

	/** @var mixed[] - string or null */
	private $normalizedMetadata = [];

	/** @var string|null */
	private $title;

	/** @var string|null */
	private $host;

	/** @var string */
	private $url;

	public function __construct(string $charset, array $metadata, string $host, string $url, ?string $title) {
		$this->charset = $charset;
		$this->metadata = $metadata;
		$this->host = $host;
		$this->url = $url;
		$this->title = $title;
	}

	public function getUrlWithoutQueryAndHash(): string {
		return strtok(strtok($this->url, '?'), '#');
	}

	public function getUrl(): string {
		return $this->url;
	}

	public function getCharset(): string {
		return $this->charset;
	}

	public function getTitle(): ?string {
		return $this->title;
	}

	public function getHost(): string {
		return $this->host;
	}

	public function getMeta(string $name, ?string $default = null): ?string {
		if (!array_key_exists($name, $this->normalizedMetadata)) {
			$this->normalizedMetadata[$name] = ContentNormalizer::normalize($this->metadata[$name] ?? null, $this->charset);
		}

		return $this->normalizedMetadata[$name] ?? $default;
	}

	/**
	 * @param string[] $variants
	 */
	public function getMetaVariants(array $variants, ?string $default = null): ?string {
		foreach ($variants as $variant) {
			$value = $this->getMeta($variant);
			if ($value !== null) {
				return $value;
			}
		}

		return $default;
	}

	public function toOpenGraph(): OpenGraphProtocol {
		return new OpenGraphProtocol($this);
	}

}
