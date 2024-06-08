<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Source;
use App\Service\GetSummaryService;
use DateTimeImmutable;
use Illuminate\Console\Command;
use Illuminate\Log\Logger;
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
    public function handle(GetSummaryService $getSummaryService, Logger $logger)
    {
        $logger->info('Scraping Symfony Blog');
        $source = Source::where('name', 'Symfony Blog')->first();
        if ($source === null) {
            $logger->error('Source not found');
            return;
        }

        $this->updateLastParsedAt($source);

        $content = $this->fetchContent($source, $logger);
        if (null === $content) {
            return;
        }

        $digests = array_reverse($this->extractDigests($content));
        $logger->info('Found ' . count($digests) . ' digests');

        foreach ($digests as $digest) {
            $logger->info('Parsing digest', [$digest['link'], $digest['dateTime']]);
            if ($this->checkShouldSkipDigest($source, $digest, $logger)) {
                $logger->info('Skipping');
                continue;
            }

            $this->processDigest($source, $digest, $logger, $getSummaryService);
            $this->updateSourceLastParsedInfo($source, $digest);

            $logger->info('Parsed digest', [$digest['link']]);
            break;
        }
    }

    private function extractDigests(string $html): array
    {
        $crawler = new Crawler($html);
        $links = [];

        $crawler->filter('.a-week-of-symfony')->each(function (Crawler $node) use (&$links) {
            $link = $node->filter('h2 a')->attr('href');
            $dateTime = trim($node->filter('.post-metadata-item')->first()->text());
            $links[] = [
                'link' => $link,
                'dateTime' => new DateTimeImmutable($dateTime),
            ];
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

    private function updateLastParsedAt($source)
    {
        $source->last_parse_at = now();
        $source->save();
    }

    private function fetchContent($source, Logger $logger): string
    {
        $content = file_get_contents($source->url_to_parse);
        if ($content === false) {
            $logger->error('Error while fetching content');

            return null;
        }

        return $content;
    }

    // Skip digest if dateTime is before Source last_parsed_digest_time
    private function checkShouldSkipDigest($source, $digest): bool
    {
        return $source->last_parsed_digest_time && $digest['dateTime'] <= $source->last_parsed_digest_time;
    }

    private function processDigest($source, $digest, Logger $logger, GetSummaryService $getSummaryService)
    {
        $digestFullUrl = $source->host . $digest['link'];
        $digestContent = file_get_contents($digestFullUrl);

        if ($digestContent === false) {
            $logger->error('Error while fetching digest content');

            return;
        }

        $articleLinks = $this->extractArticles($digestContent);
        $summary = $getSummaryService->getSummary($articleLinks);

        foreach ($summary as $article) {
            if (empty($article['url'])) {
                continue;
            }

            Article::create($article);
        }
    }

    private function updateSourceLastParsedInfo($source, $digest)
    {
        $source->last_parsed_digest_time = $digest['dateTime'];
        $source->last_parsed_digest_url = $digest['link'];
        $source->save();
    }
}
