<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Structured;

use WebChemistry\HtmlMetadata\StructuredMetadata;

final class PersonStructuredMetadata implements StructuredMetadata
{

	/** @var mixed[] */
	private array $data;

	public function __construct(string $name, ?string $url = null)
	{
		$this->data = array_filter([
			'@type' => 'Person',
			'name' => $name,
			'url' => $url,
		]);
	}

	public function getId(): string
	{
		return 'person';
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return array_filter(
			$this->data,
			fn (mixed $value): bool => $value !== null,
		);
	}

}
