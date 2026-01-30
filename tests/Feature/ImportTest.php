<?php

use Illuminate\Support\Facades\File;
use Shaunthegeek\LaravelLangDb\Models\Language;

it('can import language lines from json files', function () {
    // 1. Prepare files
    $enPath = lang_path('en.json');
    $zhPath = lang_path('zh_CN.json');

    // Ensure directory exists
    if (! File::exists(dirname($enPath))) {
        File::makeDirectory(dirname($enPath), 0755, true);
    }

    File::put($enPath, json_encode(['messages.hello' => 'Hello World']));
    File::put($zhPath, json_encode(['messages.hello' => '你好世界']));

    // 2. Run command
    $this->artisan('lang:import')
        ->assertExitCode(0);

    // 3. Assert database content
    expect(Language::where('locale', 'en')->where('key', 'messages.hello')->first()->value)->toBe('Hello World');
    expect(Language::where('locale', 'zh_CN')->where('key', 'messages.hello')->first()->value)->toBe('你好世界');

    // Cleanup
    if (File::exists($enPath)) {
        File::delete($enPath);
    }
    if (File::exists($zhPath)) {
        File::delete($zhPath);
    }
});

it('can overwrite existing language lines during import', function () {
    // 1. Prepare data and files
    Language::create(['locale' => 'en', 'key' => 'messages.hello', 'value' => 'Old Value']);

    $enPath = lang_path('en.json');
    if (! File::exists(dirname($enPath))) {
        File::makeDirectory(dirname($enPath), 0755, true);
    }
    File::put($enPath, json_encode(['messages.hello' => 'New Value']));

    // 2. Run command with overwrite option
    $this->artisan('lang:import', ['--force' => true])
        ->assertExitCode(0);

    // 3. Assert database content updated
    expect(Language::where('locale', 'en')->where('key', 'messages.hello')->first()->value)->toBe('New Value');

    // Cleanup
    if (File::exists($enPath)) {
        File::delete($enPath);
    }
});

it('does not overwrite existing language lines without overwrite option', function () {
    // 1. Prepare data and files
    Language::create(['locale' => 'en', 'key' => 'messages.hello', 'value' => 'Original Value']);

    $enPath = lang_path('en.json');
    if (! File::exists(dirname($enPath))) {
        File::makeDirectory(dirname($enPath), 0755, true);
    }
    File::put($enPath, json_encode(['messages.hello' => 'New Value']));

    // 2. Run command without overwrite option
    $this->artisan('lang:import')
        ->assertExitCode(0);

    // 3. Assert database content NOT updated
    expect(Language::where('locale', 'en')->where('key', 'messages.hello')->first()->value)->toBe('Original Value');

    // Cleanup
    if (File::exists($enPath)) {
        File::delete($enPath);
    }
});
