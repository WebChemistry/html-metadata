<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Normalizers;

use Nette\StaticClass;

final class ContentNormalizer {

	use StaticClass;

	public static function normalize(?string $value, string $charset): ?string {
		if (!$value) {
			return $value;
		}

		if ($charset && $charset !== 'utf-8') {
			$str = @iconv($charset, 'utf-8//TRANSLIT', $value);
			if ($str !== false) {
				$value = $str;
			}
		}
		$value = html_entity_decode($value, ENT_QUOTES);

		return trim($value);
	}

}
