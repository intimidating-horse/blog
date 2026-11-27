# Bid Bazaar

Bid Bazaar is a small auction listings app. Visitors can browse items, register, list an item for sale, and bid on open auctions.

## Run it locally

You’ll need PHP 8.3 or later and Composer. From the project directory, install dependencies and create your local environment file:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

The default database is SQLite. Create the database file, then run the migrations and seed the sample data:

```bash
touch database/database.sqlite
php artisan migrate:fresh --seed
```

The seeder adds 50 sample listings and generated seller accounts. It does not create a fixed admin account. To use the app, register on the welcome page; registration signs you in. The sign-in form asks for your account name and password.

Start the local server:

```bash
php artisan serve
```

Open the address printed by Artisan, usually <http://127.0.0.1:8000>. The welcome page shows the sample listings. Open one to view its auction and place a bid after signing in. Bids must be at least €1 or 5% above the current price, whichever amount is higher.

If you want to use a database other than SQLite, set its connection details in `.env` before running the migrations.

## Frontend assets

The current marketplace stylesheet is in `public/css/style.css`; no frontend build is needed to run the app. If you change the Vite-managed assets, install the JavaScript dependencies and build them with:

```bash
npm ci
npm run build
```

## Tests

Run the test suite with:

```bash
php artisan test
```

Tests use an in-memory SQLite database, as configured in `phpunit.xml`.
