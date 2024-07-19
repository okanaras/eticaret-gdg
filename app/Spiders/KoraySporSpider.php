<?php

namespace App\Spiders;

use Generator;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;
use App\Services\CategoryService;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;

class KoraySporSpider extends BasicSpider
{
    public array $startUrls = [
        "https://www.korayspor.com", // * biz ekledik
    ];

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
        SaveToDatabaseItemProcessor::class // * biz ekledik, olusturdk
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 1;

    public function __construct(public CategoryService $categoryService)
    {
        parent::__construct(); // * biz ekledik
    }

    /**
     * @return Generator<ParseResult>
     */
    public function parse(Response $response): Generator // * biz ekledik
    {
        $arr = [];

        $response->filter('.featured-item-1 .lvl-3')->each(function ($item) use (&$arr) {
            $item->filter('li')->each(function ($it) use (&$arr) {
                $arr[] = $it->filter('a')->text();
            });
        });

        $arr = array_unique($arr);

        return yield $this->item([
            'categories' => $arr,
        ]);
    }
}