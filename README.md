
## About iSRO Portal

iSRO Portal is A free and open-source project for the MMORPG SilkroadR Online (iSRO) Server files

- More Dynamic.
- Edit anything whatever from admin panel.
- Everything cached.
- supporting theme mode and all Languages.
- Less Database requests.

## Documentation Link

-Updating ..., but you can discover it yourself : )

### Official Links

- **[Documentation Link](#)**
- **[Themes Store](https://mix-shop.tech/)**
- **[iSRO Development Discord](https://discord.gg/HuJPdPSKA5)**
- **[iSRO Portal Discord](#)**
- **[Youtube Channel](https://www.youtube.com/@m1xawy)**

## Installation Video

[![IMAGE ALT TEXT HERE](https://img.youtube.com/vi/jinAoKs_WB4/0.jpg)](https://www.youtube.com/watch?v=jinAoKs_WB4)

## Quick Installation

-First be sure you have already installed iSRO-R Databases
- Install Laragon Full [https://laragon.org](https://laragon.org)
- Add PHP ^8.2 or higher [https://php.net](https://windows.php.net/download)
- Add PHP Sql Server Drivers [https://microsoft.com](https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server)
- And Sometimes you should install ODBC Driver 17 [https://microsoft.com](https://learn.microsoft.com/en-us/sql/connect/odbc/download-odbc-driver-for-sql-server)

_Lets begin:
1. Clone the repo
```sh
git clone https://github.com/m1xawy/isro-cms.git
```
2. Install Laravel dependencies
```sh
composer install
```
3. Rename `.env.example` to `.env` and fill it with your website URL and go in `config/global.php` and fill it with Silkroad database info
   ```ini
        'connection' => [
            'host' => '192.168.1.101',
            'port' => '1433',
            'user' => 'sa',
            'password' => '123456',
            'db_website' => 'ISRO_CMS',
            'db_portal' => 'GB_JoymaxPortal',
            'db_account' => 'SILKROAD_R_ACCOUNT',
            'db_shard' => 'SILKROAD_R_SHARD',
            'db_log' => 'SILKROAD_R_SHARD_LOG',
        ],
   ```
4. Create new database `ISRO_CMS` and run Laravel commands for migrate website tables
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

6. Change document root of laragon to public folder `isro-cms/public`

Finally, Congratulation!

to access admin panel change role `user` to `admin` from users table or execute this query
   ```sql
   INSERT INTO ISRO_CMS..user_roles (user_id ,is_admin) VALUES (1, 1)
   ```

Get new updates:
```sh
git pull
composer update
php artisan migrate
php artisan db:seed
php artisan optimize:clear
```

## Contributing

Message me first.
-Discord **[m1xawy](https://discord.com/users/462695018751328268)**

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
