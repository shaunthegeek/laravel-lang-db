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

2. Import translations from `/lang` directory to database:

```bash
php artisan lang:import
```

This will read all `.json` files in `/lang` and populate the database. By default, it skips existing keys. Use `--force` to update existing records:

```bash
php artisan lang:import --force
```

3. Add translations to the `languages` table, either by directly operating the database or via the admin interface (e.g., FilamentPHP).

| locale | key | value |
|---|---|---|
| en | messages.welcome | Welcome |
| zh_CN | messages.welcome | 欢迎 |

4. Export translations to `/lang` directory:

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
