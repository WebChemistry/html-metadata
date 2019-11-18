<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Protocols;

use Nette\Utils\Strings;
use WebChemistry\HtmlMetadata\HtmlMetadata;
use WebChemistry\HtmlMetadata\Normalizers\UrlNormalizer;

final class OpenGraphProtocol {

	/** @var HtmlMetadata */
	private $metadata;

	public function __construct(HtmlMetadata $metadata) {
		$this->metadata = $metadata;
	}

	public function getType(): ?string {
		return $this->metadata->getMeta('og:type');
	}

	public function getUrl(): string {
		return $this->metadata->getMeta('og:url', $this->metadata->getUrlWithoutQueryAndHash());
	}

	public function getHost(): string {
		return $this->metadata->getHost();
	}

	public function getDescription(): ?string {
		return $this->metadata->getMetaVariants(['og:description', 'description', 'twitter:description']);
	}

	public function getTitle(): ?string {
		$title = $this->metadata->getMeta('og:title');
		if ($title) {
			return $title;
		}
		if ($this->metadata->getTitle()) {
			return $this->metadata->getTitle();
		}

		return $this->metadata->getMeta('twitter:title');
	}

	public function getImageUrl(): ?string {
		return $this->toAbsoluteUrl($this->metadata->getMetaVariants(['og:image', 'twitter:image:src', 'twitter:image']));
	}

	protected function toAbsoluteUrl(?string $url): ?string {
		if (!$url) {
			return $url;
		}

		if (Strings::startsWith($url, '//')) {
			$url = $this->host . substr($url, 1);
		} else if (Strings::startsWith($url, '/')) {
			$url = $this->host = $url;
		}
		$url = UrlNormalizer::normalize($url);
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			return null;
		}

		return $url;
	}

}
