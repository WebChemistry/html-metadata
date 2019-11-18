<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Normalizers;

use Nette\StaticClass;

final class UrlNormalizer {

	use StaticClass;

	public static function normalize(string $url): string {
		if (!preg_match('/^https?:\/\//', $url)) {
			return 'http://' . $url;
		}

		return $url;
	}

}
