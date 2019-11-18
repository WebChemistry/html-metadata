<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Http;

use Nette\SmartObject;
use WebChemistry\HtmlMetadata\Exceptions\InvalidUrlException;
use WebChemistry\HtmlMetadata\Normalizers\UrlNormalizer;

final class SimpleHttpClient implements IHttpClient {

	use SmartObject;

	/**
	 * @throws InvalidUrlException
	 */
	public function request(string $url): ?string {
		$response = $this->makeRequest($url);

		return $response ? $response->content : null;
	}

	public function requestImage(string $url): ?ImageResponse {
		$response = $this->makeRequest($url);
		if ($response === null) {
			return $response;
		}

		$type = null;
		foreach ($response->headers as $header) {
			if (preg_match('#^\s*content-type:\s*(image/.+?)(?:\s|$)#i', $header, $matches)) {
				$type = $matches[1];

				break;
			}
		}
		if (!$type) {
			return null;
		}

		return new ImageResponse($response->content, $type);
	}

	/**
	 * @throws InvalidUrlException
	 */
	protected function makeRequest(string $url): ?Response {
		$normalized = UrlNormalizer::normalize($url);
		if (!filter_var($normalized, FILTER_VALIDATE_URL)) {
			throw new InvalidUrlException("Given url $url is invalid");
		}

		$contents = @file_get_contents($url);
		if (!$contents) {
			return null;
		}

		return new Response($contents, $http_response_header);
	}

}
