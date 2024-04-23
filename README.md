### Laravel Eticaret-GDG Quick Start

1.  To install `Composer` globally, download the installer from https://getcomposer.org/download/ Verify that Composer in successfully installed, and version of installed Composer will appear:

        composer --version

2.  Install `Composer` dependencies.

        composer install

3.  Install `NPM` dependencies.

        npm install

4.  The below command will compile all the assets(sass, js, media) to public folder:

        npm run dev

5.  Copy `.env.example` file and create duplicate. Use `cp` command for Linux or Max user.

        cp .env.example .env

6.  Create a table in MySQL database and fill the database details `DB_DATABASE` in `.env` file.

7.  The below command will create tables into database using Laravel migration and seeder.

        php artisan migrate:fresh --seed

8.  Generate your application encryption key:

        php artisan key:generate

9.  Start the localhost server:

        php artisan serve
