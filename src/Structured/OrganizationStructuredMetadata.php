<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata\Structured;

use DateTimeInterface;

final class OrganizationStructuredMetadata
{

	/** @var mixed[] */
	private array $data;

	public function __construct(
		string $name, 
		?string $description = null,
		?string $email = null,
		?string $telephone = null,
		?string $url = null,
		?string $logo = null,
		?string $image = null,
	)
	{
		$this->data = [
			'@context' => 'https://schema.org',
			'@type' => 'Organization',
			'name' => $name,
			'description' => $description,
			'email' => $email,
			'telephone' => $telephone,
			'url' => $url,
			'logo' => $logo,
			'image' => $image,
		];
	}

	public function getId(): string
	{
		return 'organization';
	}
	
	public function setAddress(PostalAddressStructuredMetadata $adress): self
	{
		$this->data['address'] = $adress->toArray();

		return $this;
	}

	/**
	 * @return mixed[]
	 */
	public function toArray(): array
	{
		return array_filter(
			$data,
			fn (mixed $value): bool => $value !== null,
		);
	}

}
