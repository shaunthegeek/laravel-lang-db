# Laravel Lang DB

Manage Laravel translations in the database and export them to JSON files.

## Installation

```bash
composer require shaunthegeek/laravel-lang-db
```

## Usage

1. Run migrations to create the table:

```bash
php artisan vendor:publish --tag=laravel-lang-db-migrations
php artisan migrate
```

2. Add translations to the `languages` table, either by directly operating the database or via the admin interface (e.g., FilamentPHP).

| locale | key | value |
|---|---|---|
| en | messages.welcome | Welcome |
| zh_CN | messages.welcome | 欢迎 |

3. Export translations to `/lang` directory:

```bash
php artisan lang:export
```

This will create/update:
- `/lang/en.json`
- `/lang/zh_CN.json`

## Testing

```bash
composer test
```
