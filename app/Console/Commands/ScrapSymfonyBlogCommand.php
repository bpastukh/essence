<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Service\GetSummaryService;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class ScrapSymfonyBlogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrap-symfony-blog-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(GetSummaryService $getSummaryService)
    {
        $host = 'https://symfony.com';
        $urlToParse = $host . '/blog/category/a-week-of-symfony';

        $content = file_get_contents($urlToParse);
        $digestUrls = $this->extractDigests($content);

        foreach ($digestUrls as $digestUrl) {
            $digestFullUrl =  $host . $digestUrl;
            $digest = file_get_contents($digestFullUrl);

            $articleLinks = $this->extractArticles($digest);
            $summary = $getSummaryService->getSummary($articleLinks);

            foreach ($summary as $article) {
                Article::create($article);
            }
        }
    }

    private function extractDigests(string $html): array
    {
        $crawler = new Crawler($html);
        $links = [];

        $crawler->filter('.a-week-of-symfony')->each(function (Crawler $node) use (&$links) {
            $links[] = $node->filter('h2 a')->attr('href');
        });

        return $links;
    }

    private function extractArticles(string $html): array
    {
        $crawler = new Crawler($html);
        $links = [];

        $articlesSection = $crawler->filter('h2')->reduce(function (Crawler $node) {
            return $node->text() === 'They talked about us';
        })->nextAll()->filter('ul')->first();

        if ($articlesSection->count() > 0) {
            $articlesSection->filter('li > a')->each(function (Crawler $node) use (&$links) {
                $links[] = $node->attr('href');
            });
        }

        return $links;
    }
}
