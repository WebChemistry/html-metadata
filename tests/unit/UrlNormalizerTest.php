<?php

use Codeception\Test\Unit;
use WebChemistry\HtmlMetadata\Normalizers\UrlNormalizer;

class UrlNormalizerTest extends Unit {

	// tests
	public function testSomeFeature() {
		$this->assertSame('http://example.com', UrlNormalizer::normalize('http://example.com'));
		$this->assertSame('https://example.com', UrlNormalizer::normalize('https://example.com'));
		$this->assertSame('http://example.com', UrlNormalizer::normalize('example.com'));
		$this->assertSame('http://www.example.com', UrlNormalizer::normalize('www.example.com'));
		$this->assertSame('http://test', UrlNormalizer::normalize('test'));
	}

}