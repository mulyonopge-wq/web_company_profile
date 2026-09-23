# Project Rules: Database Protection & Running Application

## 1. Running the Application
- **NEVER CREATE OR RESET DATABASE**:
  Whenever instructed to run or start the application:
  - Do NOT create a new database.
  - Do NOT re-run `database/install.php` or any installer/seeder script.
  - Do NOT drop, truncate, or overwrite existing tables/data/settings.
  - Do NOT overwrite `.env` with blank or default settings.
- Only start the web server using:
  `php -S localhost:8000 -t public public/index.php` or `run_server.bat`.

## 2. Data & Settings Persistence
- All configurations, articles, products, galleries, and team members stored in MySQL are production/live state and must be preserved at all times.
