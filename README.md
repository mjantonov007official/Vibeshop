# Vibe App

Vibe App is a clean Laravel 13 foundation for a future vibe-code style web app. It uses local SQLite development, production MySQL placeholders, a CSRF-protected form, validation, rate limiting, Eloquent database writes, and basic security headers.

This app is built with Laravel security best practices, but no web app is unhackable. Keep dependencies updated, protect credentials, keep production debug mode off, and review new features before deploying them.

## Requirements

- PHP 8.3 or newer with common Laravel extensions enabled, including `pdo`, `pdo_sqlite`, `openssl`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, and `fileinfo`.
- Composer 2.x.
- SQLite extension for local development.
- MySQL database on Hostinger for production.
- Node.js, npm, Vite, and Tailwind CDN are not required for production in this project.

## Install Laravel

To create this project from scratch with Composer:

```bash
composer create-project laravel/laravel vibe-app
cd vibe-app
```

This repository already contains the customized `vibe-app` project files. After cloning or opening it, run:

```bash
composer install
copy .env.example .env
php artisan key:generate
```

On macOS or Linux, use:

```bash
cp .env.example .env
php artisan key:generate
```

## Run Locally

Create the SQLite database file if it does not exist:

```bash
type nul > database\database.sqlite
```

On macOS or Linux:

```bash
touch database/database.sqlite
```

Use this local database setup in `.env`:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Then run:

```bash
php artisan config:clear
php artisan migrate
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Important Files

- `routes/web.php`: Defines the homepage route and the rate-limited `/generate` POST route.
- `app/Http/Controllers/HomeController.php`: Loads the homepage, validates form input, saves a generation record, and handles database errors gracefully.
- `app/Models/Generation.php`: Eloquent model with a `$fillable` allow-list to protect against mass assignment.
- `database/migrations/create_generations_table.php`: Creates the `generations` table.
- `resources/views/home.blade.php`: Blade homepage with escaped output, validation errors, flash messages, and a CSRF-protected form.
- `public/css/app.css`: Custom production-safe CSS loaded with Laravel's `asset()` helper.
- `app/Http/Middleware/SecurityHeaders.php`: Adds basic browser security headers.
- `bootstrap/app.php`: Registers the security headers middleware for web routes.
- `.env.example`: Safe placeholder environment values only.
- `.gitignore`: Keeps `.env`, SQLite databases, `vendor`, logs, and generated files out of Git.

## Folder Structure

```text
vibe-app/
  app/
    Http/
      Controllers/
        Controller.php
        HomeController.php
      Middleware/
        SecurityHeaders.php
    Models/
      Generation.php
      User.php
    Providers/
      AppServiceProvider.php
  bootstrap/
    app.php
    providers.php
  config/
  database/
    database.sqlite
    factories/
    migrations/
      0001_01_01_000000_create_users_table.php
      0001_01_01_000001_create_cache_table.php
      0001_01_01_000002_create_jobs_table.php
      create_generations_table.php
    seeders/
  public/
    css/
      app.css
    favicon.ico
    index.php
    robots.txt
  resources/
    views/
      home.blade.php
  routes/
    console.php
    web.php
  storage/
  tests/
  .editorconfig
  .env.example
  .gitattributes
  .gitignore
  artisan
  composer.json
  phpunit.xml
  README.md
```

## How the Form Works

The homepage form submits `business_type` to the named `generate` route at `POST /generate`. The form includes `@csrf`, so Laravel verifies the session CSRF token before the controller runs.

The controller validates `business_type` as required text between 2 and 100 characters. Only validated data is saved. The app uses Eloquent, not raw SQL.

Each successful submission creates a row in `generations` with:

- `business_type`
- dummy `result` value of `Generated successfully`
- request IP address
- request user agent, trimmed to 255 characters
- timestamps

If the database is unavailable, the controller logs the technical error and shows a safe message to the user instead of exposing raw database exceptions.

## Rate Limiting

The `/generate` route uses Laravel throttle middleware:

```php
->middleware('throttle:10,1')
```

That allows 10 submissions per minute per client. Future AI endpoints should usually use stricter limits and may need authentication, billing controls, abuse monitoring, and per-user quotas.

## Production MySQL on Hostinger

Create a MySQL database in Hostinger hPanel. Then set real credentials only in the production `.env` file on the server:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your_hostinger_database_host
DB_PORT=3306
DB_DATABASE=your_hostinger_database_name
DB_USERNAME=your_hostinger_database_user
DB_PASSWORD=your_hostinger_database_password
```

Do not commit production credentials to GitHub. `.env.example` should keep placeholders only.

## Shared Hosting Deployment

Preferred setup: upload the project so only Laravel's `public` directory is web-accessible, then point the domain document root to `vibe-app/public` if Hostinger allows it.

If Hostinger cannot point the domain directly to `/public`, use Hostinger's safest supported Laravel deployment flow. Keep sensitive folders such as `app`, `bootstrap`, `config`, `database`, `resources`, `routes`, `storage`, `vendor`, and `.env` outside the publicly reachable directory whenever possible. Do not expose `.env`, `vendor`, or application source files through the browser.

On the server, run:

```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Clear caches when needed:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

Before production migrations, confirm the production `.env` points to the correct MySQL database. Back up the database before running new migrations on a live site. Use `php artisan migrate --force` only when intentionally deploying production database changes.

Make `storage` and `bootstrap/cache` writable by the web server user. Use least-privilege permissions supported by your host, such as directories around `755` or `775` and files around `644` where appropriate. Do not use `chmod 777`.

## GitHub Setup

Initialize Git:

```bash
git init
git add .
git commit -m "Initial secure Laravel setup"
```

Keep the repository private while the app is early. Enable GitHub secret scanning or push protection. Confirm `.env` is ignored before pushing:

```bash
git status --ignored
```

## Security Checklist

- `.env` is not committed.
- `.env.example` contains placeholders only.
- `APP_KEY` is generated with `php artisan key:generate`.
- `APP_DEBUG=false` in production.
- CSRF protection is enabled and `@csrf` is used.
- Form input is validated before use.
- Blade output uses escaped `{{ }}` output.
- `/generate` is rate limited.
- No raw SQL is used.
- No secrets are hardcoded.
- Local development uses SQLite without XAMPP.
- Production MySQL credentials live only in production `.env`.
- Public web root points to `/public` when possible.
- `storage` and `bootstrap/cache` are writable without `chmod 777`.
- Database backups are made before production migrations.

## Self-Check Commands

Run these before calling the setup complete:

```bash
php -v
composer --version
php -m
php artisan config:clear
php artisan migrate
php artisan serve
```

Confirm `pdo_sqlite` appears in `php -m`, `database/database.sqlite` exists, the homepage loads, and a test business type submits successfully.
