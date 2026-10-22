# Sun Son Solar

The CodeIgniter 4 application is in the `sunson` directory. Each teammate runs
their own local copy; cloning the repository does not publish the UI as a
hosted website.

## Run locally with XAMPP

Requirements: PHP 8.2 or newer with `intl`, `mbstring`, and `mysqli` enabled,
Composer, and MySQL/MariaDB.

1. Clone the repository, then open a terminal in the application directory:

   ```powershell
   cd sunson
   ```

2. Install PHP dependencies and create your private local configuration:

   ```powershell
   composer install
   Copy-Item .env.example .env
   ```

   The app detects its local web URL automatically. Edit `.env` if your local
   MySQL username, password, or port differs from the XAMPP defaults. Do not
   commit `.env`.

3. Start MySQL from the XAMPP Control Panel, then create the local database
   (or create it in phpMyAdmin):

   ```powershell
   C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE sunson CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
   ```

4. Create the tables and seed the initial accounts:

   ```powershell
   php spark migrate
   php spark db:seed UserSeeder
   php spark db:seed ProductSeeder
   ```

   The product seeder adds sample catalog items with online image URLs. Update
   the product records in your database to match the client's actual inventory.

5. Start the web server:

   ```powershell
   php spark serve
   ```

   Open **http://localhost:8080/**. Do not open the PHP files directly or serve
   the repository root; the web root is `sunson/public`.

The admin dashboard loads Chart.js from jsDelivr, so an internet connection is
needed to render its charts. The underlying chart data remains available in the
dashboard if the chart library cannot load.

If `php` is not recognized, add your PHP installation directory (for XAMPP,
usually `C:\xampp\php`) to the Windows `Path`, reopen the terminal, and retry.
If using Apache instead of `php spark serve`, configure its document root to
`sunson/public`. If you explicitly set `app.baseURL` in `.env`, make sure it
matches the URL and project path where Apache serves the app.
