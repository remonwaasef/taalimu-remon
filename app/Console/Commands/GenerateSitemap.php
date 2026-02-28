<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = SitemapGenerator::create(config('app.url'))
            ->hasCrawled(function (Url $url) {
                if (str_contains($url->path(), 'admin') || str_contains($url->path(), 'center') || str_contains($url->path(), 'auth')) {
                    return;
                }
                return $url;
            })
            ->getSitemap();

        // Manually add localized versions of the homepage
        $sitemap->add(Url::create('/?hl=ar')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
                ->add(Url::create('/?hl=en')->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
                ->add(Url::create('/?hl=fr')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        $sitemap->writeToFile(public_path('sitemap.xml'));
            
        $this->info('Sitemap generated successfully.');
    }
}
