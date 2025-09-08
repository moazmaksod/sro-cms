## About SRO CMS v2

SRO CMS v2 is A free and open-source project for the MMORPG Silkroad Online (iSRO & vSRO) Server files

Whats new?-:
- Supporting iSRO and vSRO files.
- very clean & 300% faster
- everything cached, no direct requests with your game dbs
- more rankings added
- fixed blue stats & iSRO wheeld items
- job info added
- new admin panel from scratch
- supporting dark mode
- supporting all Languages
- supporting custom themes

## Documentation Link

-Updating ..., but you can discover it yourself : )

### Official Links

- **[Documentation Link](#)**
- **[Themes Store](https://mix-shop.tech/)**
- **[iSRO Development Discord](https://discord.gg/HuJPdPSKA5)**
- **[Mix-Store Discord](https://discord.gg/4MqzAHGU4e)**
- **[Youtube Channel](https://www.youtube.com/@m1xawy)**

## Installation Video

[![IMAGE ALT TEXT HERE](https://img.youtube.com/vi/jinAoKs_WB4/0.jpg)](https://www.youtube.com/watch?v=jinAoKs_WB4)

## Quick Installation

-First be sure you have already installed iSRO-R | vSRO Databases
- Install Laragon Full [https://laragon.org](https://laragon.org)
- Add PHP ^8.2 or higher [https://php.net](https://windows.php.net/download)
- Add PHP Sql Server Drivers [https://microsoft.com](https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server)
- And Sometimes you should install ODBC Driver 17 [https://microsoft.com](https://learn.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server)

_Lets begin:
1. Clone the repo for SRO-CMS
```sh
git clone https://github.com/m1xawy/sro-cms.git
```

2. Install Laravel dependencies
```sh
composer install
```
3. Rename `.env.example` to `.env` and fill it with Silkroad database info
   ```ini
    # Silkroad Server Files Type, iSRO, vSRO
    SRO_VERSION=iSRO
    
    # Silkroad Server Information
    DB_CONNECTION=sqlsrv
    DB_HOST=192.168.1.101
    DB_PORT=1433
    DB_DATABASE=SRO_CMS
    DB_DATABASE_PORTAL=GB_JoymaxPortal
    DB_DATABASE_ACCOUNT=SILKROAD_R_ACCOUNT
    DB_DATABASE_SHARD=SILKROAD_R_SHARD
    DB_DATABASE_LOG=SILKROAD_R_SHARD_LOG
    DB_USERNAME=sa
    DB_PASSWORD=123456
   ```
4. Create new database `SRO_CMS` and run Laravel commands for migrate website tables
```sh
php artisan migrate
php artisan db:seed
php artisan key:generate
php artisan storage:link
```
5. Install NPM packages & Run
```sh
npm install
npm run build
```

6. Change document root of laragon to public folder `sro-cms/public`

Finally, Congratulation!

for changing main settings, go to admin panel > settings
and go `config/global.php` to customize everything.
to access admin panel change role `user` to `admin` from users table or execute this query
   ```sql
   INSERT INTO SRO_CMS..user_roles (user_id ,is_admin) VALUES (1, 1)
   ```

Get new updates:
```sh
git pull
composer update
php artisan migrate
php artisan db:seed
php artisan optimize:clear
```

## Setup on WSL & Docker (Recommended for Linux/WSL2 Users)

This project provides a fully automated setup for development using WSL2 and Docker. The included `setup.sh` script will handle environment configuration, dependency installation, and database preparation for you.

### Prerequisites
- **WSL 2** with a Linux distribution (e.g., Ubuntu)
- **Docker Desktop** installed and integrated with WSL 2
- **Git** command-line tool

### 1. Clone the Repository
```sh
git clone https://github.com/m1xawy/sro-cms.git
cd sro-cms
```

### 2. Run the Setup Script
The `setup.sh` script will:
- Check for required tools (Git, Docker, Docker Compose)
- Interactively create and configure your `.env` file
- Build and start Docker containers
- Install Composer and NPM dependencies
- Prepare the database and run migrations/seeds
- Compile frontend assets

Making the internal environment script executable...
```sh
chmod +x setup.sh
```

Run the following command:
```sh
bash setup.sh setup
```
Follow the interactive prompts to configure your environment and database connection.

### 3. Daily Development Commands
- **Start containers:**
  ```sh
  bash setup.sh up
  ```
- **Stop containers:**
  ```sh
  bash setup.sh down
  ```
- **Install/update dependencies:**
  ```sh
  bash setup.sh install
  ```
- **Open a shell in the app container:**
  ```sh
  bash setup.sh ssh
  ```
- **Run Laravel Artisan commands:**
  ```sh
  bash setup.sh artisan <command>
  ```
- **Clear Laravel caches:**
  ```sh
  bash setup.sh clear
  ```
- **Clean (remove containers & dependency volumes):**
  ```sh
  bash setup.sh clean
  ```

### 4. Access the Application
Once setup is complete, visit: [http://localhost:8000](http://localhost:8000)

### Troubleshooting
- Ensure Docker Desktop is running and WSL integration is enabled.
- If you encounter permission issues, rerun the setup or use `bash setup.sh install`.
- For database connection issues, double-check your `.env` values and SQL Server accessibility from WSL.

## Contributing

Message me first.
-Discord **[m1xawy](https://discord.com/users/462695018751328268)**

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
