<?php

namespace Shaunthegeek\LaravelLangDb\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Shaunthegeek\LaravelLangDb\Models\Language;

class ImportLanguages extends Command
{
    protected $signature = 'lang:import {--force : Overwrite existing translations}';

    protected $description = 'Import languages from JSON files in /lang directory to database';

    public function handle()
    {
        $this->info('Starting import...');

        $files = File::glob(lang_path('*.json'));

        foreach ($files as $file) {
            $locale = pathinfo($file, PATHINFO_FILENAME);
            $this->info("Processing $locale.json...");

            $content = json_decode(File::get($file), true);

            if (! is_array($content)) {
                $this->error("Invalid JSON in $locale.json");

                continue;
            }

            foreach ($content as $key => $value) {
                if ($this->option('force')) {
                    Language::updateOrCreate(
                        ['locale' => $locale, 'key' => $key],
                        ['value' => $value]
                    );
                } else {
                    Language::firstOrCreate(
                        ['locale' => $locale, 'key' => $key],
                        ['value' => $value]
                    );
                }
            }
        }

        $this->info('Import complete.');
    }
}
