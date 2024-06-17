<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Structured;

use WebChemistry\HtmlMetadata\StructuredMetadata;

final class PostalAddressStructuredMetadata implements StructuredMetadata
{

	/** @var mixed[] */
	private array $data;
	
	public function __construct(
		?string $streetAddress, 
		?string $adressLocality,
		?string $addressCountry,
		?string $addressRegion,
		?string $postalCode,
	)
	{
		$this->data = [
			'@context' => 'https://schema.org',
			'@type' => 'PostalAddress',
			'streetAddress' => $streetAddress,
			'adressLocality' => $adressLocality,
			'addressCountry' => $addressCountry,
			'addressRegion' => $addressRegion,
			'postalCode' => $postalCode,
		];
	}

	public function getId(): string
	{
		return 'postal-address';
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
