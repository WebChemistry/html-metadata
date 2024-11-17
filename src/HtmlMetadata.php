<?php declare(strict_types = 1);

namespace WebChemistry\HtmlMetadata;

use DateTimeInterface;
use InvalidArgumentException;
use LogicException;
use Nette\Utils\FileSystem;
use Nette\Utils\Html;
use Nette\Utils\Strings;

class HtmlMetadata
{

	/** @var array<Html|null> */
	private array $items = [
		'title' => null,
	];

	public function __construct(
		protected readonly ?string $titleTemplate = null,
	)
	{
		$this->setCharset('utf-8');
		$this->items['http-equiv'] = Html::el('meta', [
			'http-equiv' => 'X-UA-Compatible',
			'content' => 'IE=edge,chrome=1',
		]);
		$this->items['viewport'] = $this->createMetaName('viewport', 'width=device-width, initial-scale=1');
		$this->items['twitter:card'] = $this->createMetaName('twitter:card', 'summary_large_image');
	}

	public function setCharset(string $charset): self
	{
		$this->items['charset'] = Html::el('meta', [
			'charset' => $charset,
		]);

		return $this;
	}

	public function setTitle(?string $title): self
	{
		$title = $this->normalize($title);

		$this->items['title'] = $title ? Html::el('title')->setText($title) : null;
		$this->items['og:title'] = $this->tryToCreateMetaProperty('og:title', $title);
		$this->items['twitter:title'] = $this->tryToCreateMetaName('twitter:title', $title);

		return $this;
	}

	public function addToTitle(?string $title): self
	{
		if (!$this->titleTemplate) {
			return $this->setTitle($title);
		}

		if (!$title) {
			return $this;
		}

		$this->items['title'] = Html::el('title')->setText(sprintf($this->titleTemplate, $title));
		$this->items['og:title'] = $this->tryToCreateMetaProperty('og:title', $title);
		$this->items['twitter:title'] = $this->tryToCreateMetaName('twitter:title', $title);

		return $this;
	}

	public function setDescription(?string $description): self
	{
		$description = $this->truncate($this->normalize($description), 200);

		$this->items['description'] = $this->tryToCreateMetaName('description', $description);
		$this->items['og:description'] = $this->tryToCreateMetaProperty('og:description', $description);
		$this->items['twitter:description'] = $this->tryToCreateMetaName('twitter:description', $description);

		return $this;
	}

	public function setAuthor(?string $author): self
	{
		$author = $this->normalize($author);

		$this->items['author'] = $this->tryToCreateMetaName('author', $author);

		return $this;
	}

	public function setLocale(?string $locale): self
	{
		$this->items['og:locale'] = $this->tryToCreateMetaProperty('locale', $locale);

		return $this;
	}

	public function setArticleType(DateTimeInterface $published, ?DateTimeInterface $modified = null): self
	{
		$this->items['og:type'] = $this->tryToCreateMetaProperty('og:type', 'article');
		$this->items['article:published_time'] = $this->tryToCreateMetaProperty('article:published_time', $published->format('c'));

		if ($modified) {
			$this->items['article:modified_time'] = $this->tryToCreateMetaProperty(
				'article:modified_time',
				$modified->format('c')
			);
		}

		return $this;
	}

	/**
	 * @param string[] $values
	 * @example ['follow', 'index']
	 * @example ['noindex', 'nofollow']
	 * @example ['max-image-preview' => 'large']
	 */
	public function setRobots(array $values): self
	{
		$robots = '';

		foreach ($values as $key => $value) {
			if (!is_numeric($key)) {
				$robots .= $key . ':' . $value . ', ';
			} else {
				$robots .= $value . ', ';
			}
		}

		$robots = substr($robots, 0, -2);

		$this->items['robots'] = $robots ? Html::el('meta', [
			'name' => 'robots',
			'content' => $robots,
		]) : null;

		return $this;
	}

	public function setFacebookVerification(?string $verification): self
	{
		$this->items['facebook-verification'] = $this->tryToCreateMetaName('facebook-domain-verification', $verification);

		return $this;
	}

	public function setFacebookPixel(?string $pixel): self
	{
		$this->items['facebook-pixel'] = $pixel ? $this->createFromTemplate(__DIR__ . '/templates/facebook-pixel.html', [
			'pixel' => $pixel,
		]) : null;

		return $this;
	}

	public function setFavicons(?FaviconMetadataCollection $favicons): self
	{
		$favicon = null;

		if ($items = $favicons?->getItems()) {
			$favicon = Html::el();

			foreach ($items as $item) {
				$favicon->insert(null, Html::el('link', [
					'rel' => $item['rel'],
					'href' => $item['link'],
					'type' => $item['type'],
					'sizes' => $item['sizes'],
				]));
			}
		}

		$this->items['favicon'] = $favicon;

		return $this;
	}

	public function setColor(?string $color): self
	{
		if (!$color) {
			$this->items['color'] = null;

			return $this;
		}

		$wrapper = Html::el();
		$wrapper->insert(null, $this->createMetaName('theme-color', $color));
		$wrapper->insert(null, $this->createMetaName('msapplication-navbutton-color', $color));
		$wrapper->insert(null, $this->createMetaName('apple-mobile-web-app-capable', $color));
		$wrapper->insert(null, $this->createMetaName('apple-mobile-web-app-status-bar-style', $color));

		$this->items['color'] = $wrapper;

		return $this;
	}

	public function setGoogleAnalytics(?string $analytics): self
	{
		if (!$analytics) {
			$this->items['google-analytics'] = null;

			return $this;
		}

		$this->items['google-analytics'] = $this->createFromTemplate(__DIR__ . '/templates/google-analytics.html', [
			'id' => $analytics,
		]);

		return $this;
	}

	public function setGoogleVerification(?string $verification): self
	{
		$this->items['google-verification'] = $this->tryToCreateMetaName('google-site-verification', $verification);

		return $this;
	}

	public function setPinterestDomainVerification(?string $verification): self
	{
		$this->items['pinterest-verification'] = $this->tryToCreateMetaName('p:domain_verify', $verification);

		return $this;
	}

	public function setAlternateLanguages(?AlternateLanguageMetadataCollection $collection): self
	{
		$wrapper = null;

		if ($items = $collection?->getItems()) {
			$wrapper = Html::el();

			foreach ($items as $item) {
				$wrapper->insert(null, Html::el('link', [
					'rel' => 'alternate',
					'hreflang' => $item['lang'],
					'href' => $item['href'],
				]));
			}
		}

		$this->items['alternate-languages'] = $wrapper;

		return $this;
	}

	public function setImage(?string $image): self
	{
		$this->items['image'] = $this->tryToCreateMetaName('image', $image);
		$this->items['og:image'] = $this->tryToCreateMetaProperty('og:image', $image);
		$this->items['og:image:url'] = $this->tryToCreateMetaProperty('og:image:url', $image);
		$this->items['og:image:secure_url'] = $this->tryToCreateMetaProperty('og:image:secure_url', $image);
		$this->items['twitter:image'] = $this->tryToCreateMetaName('twitter:image', $image);

		return $this;
	}

	public function setSiteName(?string $siteName): self
	{
		$this->items['og:site_name'] = $this->tryToCreateMetaProperty('og:site_name', $siteName);

		return $this;
	}

	public function setTwitterSite(?string $site): self
	{
		$this->items['twitter:site'] = $this->tryToCreateMetaName('twitter:site', $site);

		return $this;
	}

	public function setUrl(?string $url): self
	{
		$this->items['og:url'] = $this->tryToCreateMetaProperty('og:url', $url);

		return $this;
	}

	public function setRss(?string $link): self
	{
		$this->items['rss'] = $link ? Html::el('link', [
			'rel' => 'alternate',
			'type' => 'application/rss+xml',
			'href' => $link,
		]) : null;

		return $this;
	}

	public function setNextLink(?string $link): self
	{
		$this->items['next'] = $link ? Html::el('link', [
			'rel' => 'next',
			'href' => $link,
		]) : null;

		return $this;
	}

	public function setPrevLink(?string $prev): self
	{
		$this->items['prev'] = $prev ? Html::el('link', [
			'rel' => 'prev',
			'href' => $prev,
		]) : null;

		return $this;
	}

	public function addStructuredMetadata(StructuredMetadata $metadata, bool $prettyPrint = false): self
	{
		$this->items[$metadata->getId()] = Html::el('script', [
			'type' => 'application/ld+json',
		])->setHtml($this->escapeJs($metadata->toArray(), $prettyPrint));

		return $this;
	}

	public function render(int $indent = 0): void
	{
		$wrapper = Html::el();

		foreach ($this->items as $item) {
			if ($item === null) {
				continue;
			}

			$wrapper->insert(null, $item);
		}

		echo $wrapper->render($indent);
	}

	protected function tryToCreateMetaName(string $name, ?string $content): ?Html
	{
		return $content ? Html::el('meta', ['name' => $name, 'content' => $content]) : null;
	}

	protected function createMetaName(string $name, string $content): Html
	{
		return Html::el('meta', ['name' => $name, 'content' => $content]);
	}

	protected function tryToCreateMetaProperty(string $name, ?string $content): ?Html
	{
		return $content ? Html::el('meta', ['property' => $name, 'content' => $content]) : null;
	}

	/**
	 * @param array<string, scalar|null> $parameters
	 */
	protected function createFromTemplate(string $file, array $parameters): Html
	{
		$contents = FileSystem::read($file);
		$contents = Strings::replace($contents, '#\{\{\s*(\w+)\s*}}#', function (array $matches) use ($parameters): string {
			if (!isset($parameters[$matches[1]])) {
				throw new LogicException(sprintf('Template variable %s not exists.', $matches[1]));
			}

			return htmlspecialchars((string) $parameters[$matches[1]], ENT_QUOTES);
		});

		return Html::fromHtml($contents);
	}

	protected function escapeJs(mixed $s, bool $prettyPrint = false): string
	{
		$flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
		
		if ($prettyPrint) {
			$flags |= JSON_PRETTY_PRINT;
		}
		
		$json = json_encode($s, $flags);

		if ($json === false || ($error = json_last_error())) {
			throw new InvalidArgumentException(json_last_error_msg(), $error ?? 0);
		}

		return str_replace([']]>', '<!', '</'], [']]\u003E', '\u003C!', '<\/'], $json);
	}

	protected function normalize(?string $str): ?string
	{
		return $str === null ? null : trim(strtr(strip_tags(html_entity_decode($str)), ["\xc2\xa0" => '']));
	}

	protected function truncate(?string $str, int $length): ?string
	{
		if ($str === null) {
			return null;
		}

		return Strings::truncate($str, $length);
	}

}
