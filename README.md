# SRO CMS v2

SRO CMS v2 is a free, open-source CMS built for Silkroad Online (iSRO & vSRO) private server files. It provides a fast, cached frontend, an admin panel, multi-language support, themes, ranking pages and more.

Highlights
- Supports iSRO and vSRO server files
- Fast and heavily cached (no direct DB queries on every request)
- New admin panel rebuilt from scratch
- Dark mode, multi-language, and custom themes support
- Extra features: rankings, job info, blue stats fixes, wheel items fixes (iSRO)

Official links
- Documentation: (coming soon)
- Themes Store: https://mix-shop.tech/
- iSRO Development Discord: https://discord.gg/HuJPdPSKA5
- Mix-Store Discord: https://discord.gg/4MqzAHGU4e
- YouTube Channel: https://www.youtube.com/@m1xawy
- Author / Contact (Discord): m1xawy — https://discord.com/users/462695018751328268

---

Table of contents
- Requirements
- Quick overview (recommended)
- Windows (Laragon) — manual setup
- Ubuntu — automated `deploy.sh`
- WSL (Ubuntu on Windows) — automated `setup.sh`
- Docker / WSL2 (recommended for Linux development)
- Configuration & common commands
- Admin access
- Troubleshooting
- Contributing
- License

---

Requirements
- PHP 8.2 or later
- Composer
- Node.js (16+ recommended) & npm
- SQL Server database with Silkroad game DBs (iSRO/vSRO) accessible from your host or container
- PHP SQL Server extensions (sqlsrv, pdo_sqlsrv)
- (Windows) Laragon recommended
- (Linux) Docker + docker-compose recommended if you don't want to install SQL Server locally
- Optional: ODBC Driver 17+ (for some environments)

Note: This project uses SQL Server (sqlsrv) connections. On Linux you can use Docker-based SQL Server or install Microsoft packages for SQL Server PHP drivers.

---

Quick overview (recommended)
If you're not tied to a native Windows install, the recommended route is to use WSL2 + Docker (or the project's scripts) to get a reproducible environment.

High-level steps:
1. Clone the repo
2. Copy `.env.example` → `.env` and fill DB credentials
3. Install PHP & Composer dependencies
4. Run migrations & seeders
5. Build frontend assets (npm)
6. Serve the app (via container, local webserver, or `php artisan serve`)

---

Installation — Windows (Laragon) (manual)
This is a concise step-by-step for Windows users who prefer Laragon.

1. Prerequisites
   - Install Laragon (Full): https://laragon.org
   - Install PHP 8.2+ (add to Laragon’s PHP versions)
   - Install Composer: https://getcomposer.org
   - Install Node.js & npm
   - Install the Microsoft PHP SQL Server drivers:
     - https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server
   - (Sometimes) Install ODBC Driver 17: https://learn.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server

2. Clone repository
```bash
git clone https://github.com/m1xawy/sro-cms.git
cd sro-cms
```

3. Install PHP dependencies
```bash
composer install
```

4. Prepare environment
- Rename `.env.example` to `.env`
- Edit `.env` and fill your Silkroad SQL Server connection details, for example:
```ini
# Silkroad Server Files Type, iSRO or vSRO
SRO_VERSION=iSRO

DB_CONNECTION=sqlsrv
DB_HOST=192.168.1.101
DB_PORT=1433
DB_DATABASE=SRO_CMS
DB_DATABASE_PORTAL=GB_JoymaxPortal
DB_DATABASE_ACCOUNT=SILKROAD_R_ACCOUNT
DB_DATABASE_SHARD=SILKROAD_R_SHARD
DB_DATABASE_LOG=SILKROAD_R_SHARD_LOG
DB_USERNAME=sa
DB_PASSWORD=yourStrong(!)Password
```

5. Create databases (if required)
- Create `SRO_CMS` database (and any other referenced DBs if not already present). You can use SQL Server Management Studio or SQL scripts.

6. Run Laravel commands
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```

7. Install frontend packages and build
```bash
npm install
npm run build
```

8. Point Laragon document root to project `public` folder (e.g., sro-cms/public) and start your server.

Admin access
- To grant a user admin rights manually:
```sql
INSERT INTO SRO_CMS..user_roles (user_id, is_admin) VALUES (1, 1);
```
- Or change role value in users table from `user` to `admin`.

---

Installation — Ubuntu (deploy.sh)
This repository includes a `deploy.sh` (or similar) script to automate Ubuntu server installs. Use this when you have a native Ubuntu server or VM.

1. Prerequisites
- Ubuntu 20.04+ (or distribution supported by script)
- Git
- (Recommended) Docker & docker-compose if the script builds containers
- If using a remote SQL Server, ensure network access from the Ubuntu host

2. Clone and run
```bash
git clone https://github.com/m1xawy/sro-cms.git
cd sro-cms
# make sure deploy script is executable
chmod +x deploy.sh
# run the deployment. Check options: the script may accept "setup" or similar subcommands
bash deploy.sh setup
```

What the script typically does
- Checks for required tools (git, docker, docker-compose, php, composer)
- Prompts for and writes `.env`
- Builds/starts containers or installs services
- Installs Composer & npm packages
- Runs migrations, seeds, and builds assets
- Configures system service (nginx/php-fpm) if needed

If your `deploy.sh` exposes additional commands, run:
```bash
bash deploy.sh --help
```

If you prefer a manual Ubuntu setup, follow the same manual steps as for Windows but install system packages on Ubuntu (php, php-dev, php-xml, php-mbstring, php-sqlsrv drivers via Microsoft packages, composer, nodejs, npm).

---

Installation — WSL (Windows Subsystem for Linux) `setup.sh`
If you want Linux tooling while staying on Windows, use WSL2 + the repository `setup.sh` script for a guided setup.

1. Prerequisites
- Windows 10/11 with WSL2 enabled
- A Linux distro installed (Ubuntu recommended)
- Docker Desktop (with WSL integration) OR local Linux tooling
- Git

2. From Windows PowerShell or WSL shell:
```bash
# Access WSL shell (if not already)
wsl

# In WSL
git clone https://github.com/m1xawy/sro-cms.git
cd sro-cms
chmod +x setup.sh
bash setup.sh setup
```

Typical `setup.sh` behavior
- Checks for Git, Docker, docker-compose
- Interactively builds `.env` or copies `.env.example`
- Builds containers or installs PHP/Composer in WSL
- Installs Composer & npm packages inside WSL or containers
- Prepares database and runs migrations/seeds
- Compiles frontend assets

Common commands provided by `setup.sh` (examples)
```bash
# Start containers
bash setup.sh up

# Stop containers
bash setup.sh down

# Install/update dependencies
bash setup.sh install

# Open a shell into the app container
bash setup.sh ssh

# Run artisan easily
bash setup.sh artisan migrate --force

# Clear caches
bash setup.sh clear

# Clean (remove containers & volumes)
bash setup.sh clean
```

Notes:
- If using Docker for the SQL Server database, the script may provision a SQL Server container for you. Ensure ports and data persistence are configured to your needs.
- The script may ask for database names/credentials — use the same variables expected in the `.env` file.

---

Docker / WSL2 (recommended for Linux development)
Using Docker avoids dealing with binary compatibility (php-sqlsrv on Linux can be tricky). Options:
- Run SQL Server in Docker container (mcr.microsoft.com/mssql/server) and connect your app to it.
- Run the app in a PHP container (php-fpm + nginx) with Composer and Node build steps executed in a build container.

Example: run artisan serve (non-production)
```bash
php artisan serve --host=0.0.0.0 --port=8000
# Visit http://localhost:8000
```

---

Configuration & common commands

.env basics
- Copy `.env.example` → `.env` and fill values.
- Important DB keys: DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD.
- SRO_VERSION must be set to either `iSRO` or `vSRO`.

Important artisan tasks
```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link

# Clear caches
php artisan optimize:clear
```

Frontend
```bash
npm install
npm run build       # production build
npm run dev         # development watch (if available)
```

Updating the project
```bash
git pull
composer update
php artisan migrate
php artisan db:seed
php artisan optimize:clear
```

---

Admin panel & settings
- Admin panel is accessible under your app base URL (the exact path depends on routes configured; check `routes/web.php`).
- To change site-wide settings, use the admin panel > settings or edit `config/global.php` directly.
- To grant admin role manually:
```sql
INSERT INTO SRO_CMS..user_roles (user_id, is_admin) VALUES (1, 1);
```

---

Troubleshooting (common issues)
- "SQL Server connection failed":
  - Verify DB_HOST, DB_PORT and SQL Server firewall rules.
  - Ensure PHP has `sqlsrv` and `pdo_sqlsrv` extensions installed.
  - On Windows, install Microsoft PHP drivers; on Linux, use the Microsoft packages or connect to a SQL Server Docker container.
- Permissions errors for storage:
  - Run `php artisan storage:link` and ensure correct ownership for `storage` and `bootstrap/cache`.
- NPM build failures:
  - Remove `node_modules` and retry `npm install`.
- If migrations fail, check DB user permissions and that the target DB exists.

---

Contributing
- Message the repo owner first (Discord: m1xawy).
- Create issues for bugs or features.
- Fork, make changes, and submit PR (follow project coding standards).
- Keep changes small and test migrations/seeds before PR.

---

License
This project uses the Laravel framework (MIT). See the LICENSE file for details.
