<?php

use Shaunthegeek\LaravelLangDb\Models\Language;
use Illuminate\Support\Facades\File;

it('can export language lines to json files', function () {
    // 1. Create data
    Language::create(['locale' => 'en', 'key' => 'messages.welcome', 'value' => 'Welcome']);
    Language::create(['locale' => 'en', 'key' => 'messages.goodbye', 'value' => 'Goodbye']);
    Language::create(['locale' => 'zh_CN', 'key' => 'messages.welcome', 'value' => '欢迎']);

    // 2. Run command
    $this->artisan('lang:export')
        ->assertExitCode(0);

    // 3. Assert files exist
    $enPath = lang_path('en.json');
    $zhPath = lang_path('zh_CN.json');

    expect(File::exists($enPath))->toBeTrue();
    expect(File::exists($zhPath))->toBeTrue();

    // 4. Assert content
    $enContent = json_decode(File::get($enPath), true);
    expect($enContent)->toMatchArray([
        'messages.welcome' => 'Welcome',
        'messages.goodbye' => 'Goodbye',
    ]);

    $zhContent = json_decode(File::get($zhPath), true);
    expect($zhContent)->toMatchArray([
        'messages.welcome' => '欢迎',
    ]);
    
    // Cleanup
    if (File::exists($enPath)) File::delete($enPath);
    if (File::exists($zhPath)) File::delete($zhPath);
});
