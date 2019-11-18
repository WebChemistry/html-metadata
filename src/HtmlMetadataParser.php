<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata;

use Contributte\Utils\Http;
use Nette\SmartObject;
use WebChemistry\HtmlMetadata\Exceptions\InvalidUrlException;
use WebChemistry\HtmlMetadata\Http\IHttpClient;
use WebChemistry\HtmlMetadata\Http\SimpleHttpClient;
use WebChemistry\HtmlMetadata\Normalizers\ContentNormalizer;
use WebChemistry\HtmlMetadata\Normalizers\UrlNormalizer;

final class HtmlMetadataParser {

	use SmartObject;

	/** @var string */
	private const CHARSET_REGEX = '
		~<\s*meta\s
		
		# capture content to $2
		[^>]*?\bcharset\s*=\s*
			(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|
			([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))
		[^>]*>
		
		~ix';

	/** @var IHttpClient */
	private $httpClient;

	public function __construct(?IHttpClient $httpClient = null) {
		$this->httpClient = $httpClient ?: new SimpleHttpClient();
	}

	/**
	 * @throws InvalidUrlException
	 */
	public function parseContent(string $content, string $url): HtmlMetadata {
		// charset
		$charset = 'utf-8';
		if (preg_match(self::CHARSET_REGEX, $content, $matches)) {
			$charset = strtolower(trim($matches[1]));
		}

		// url
		$url = UrlNormalizer::normalize($url);
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			throw new InvalidUrlException("Invalid url given $url");
		}

		// host
		$host = parse_url($url, PHP_URL_HOST);
		if ($host === false) {
			throw new InvalidUrlException("Cannot get host from $url");
		}

		// title
		$title = null;
		if (preg_match('#<title>(.*?)</title>#mi', $content, $matches)) {
			$title = ContentNormalizer::normalize($matches[1], $charset);
		}

		// metadata
		$metadata = Http::metadata($content);

		return new HtmlMetadata($charset, $metadata, $host, $url, $title);
	}

	/**
	 * @throws InvalidUrlException
	 */
	public function parseUrl(string $url): HtmlMetadata {
		return $this->parseContent($this->httpClient->request($url), $url);
	}

}
