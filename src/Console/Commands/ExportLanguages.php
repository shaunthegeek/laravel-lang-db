<?php

namespace Shaunthegeek\LaravelLangDb\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Shaunthegeek\LaravelLangDb\Models\Language;

class ExportLanguages extends Command
{
    protected $signature = 'lang:export';

    protected $description = 'Export languages from database to JSON files in /lang directory';

    public function handle()
    {
        $this->info('Starting export...');

        $lines = Language::all();

        $grouped = $lines->groupBy('locale');

        foreach ($grouped as $locale => $items) {
            $translations = $items->pluck('value', 'key')->toArray();

            $json = json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $path = lang_path("$locale.json");

            // Ensure the directory exists (though lang_path usually exists, create if not)
            // lang_path() returns path/to/lang/en.json.
            // We should make sure the directory of that path exists if we were writing to subdirs,
            // but for lang root it should be fine.
            // Actually, in fresh Laravel installations, lang directory might not exist or might be published.
            if (! File::exists(dirname($path))) {
                File::makeDirectory(dirname($path), 0755, true);
            }

            File::put($path, $json.PHP_EOL);

            $this->info("Exported $locale.json");
        }

        $this->info('Export complete.');
    }
}
