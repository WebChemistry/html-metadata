<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Http;

use WebChemistry\HtmlMetadata\Exceptions\InvalidUrlException;

interface IHttpClient {

	/**
	 * @throws InvalidUrlException
	 */
	public function request(string $url): ?string;

	/**
	 * @throws InvalidUrlException
	 */
	public function requestImage(string $url): ?ImageResponse;

}