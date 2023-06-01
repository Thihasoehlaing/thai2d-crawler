[![PHP Composer](https://github.com/Thihasoehlaing/thai2d-crawler/actions/workflows/php.yml/badge.svg)](https://github.com/Thihasoehlaing/thai2d-crawler/actions/workflows/php.yml)

# Thai 2D Crawler

Thai 2D crawler from [set.or.th](https://www.set.or.th/en/market/product/stock/overview)

## Installation

```bash
composer require thihasoehlaing/thai2d-crawler
```

## Usage

```php
use Thihasoehlaing\TwoDCrawler\TwoDCrawler;

$crawler = new TwoDCrawler();

$crawler->getSet(); // "1,685.75"
$crawler->getValue(); // "63,797.02"
$crawler->getNumber(); // "57"
$crawler->getTime(); // "13:49:07"
```

## Testing

```bash
composer test
```