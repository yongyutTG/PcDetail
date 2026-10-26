# PHP 8.4 migration

The application now requires PHP **8.4.x**, CodeIgniter 4.7, and PHPUnit 11.5. Install the versions pinned in `composer.lock` with `composer install`; do not run `composer update` during deployment.

## Local Windows development

A separate PHP CLI runtime is available at `C:\Users\A\.codex\runtimes\php84\php.exe`. XAMPP's PHP and Apache have not been replaced. The upgraded application will reject PHP 7.4.

From the project directory in PowerShell:

```powershell
$php84 = 'C:\Users\A\.codex\runtimes\php84\php.exe'
& $php84 'C:\ProgramData\ComposerSetup\bin\composer.phar' install
& $php84 'C:\ProgramData\ComposerSetup\bin\composer.phar' check-platform-reqs
& $php84 vendor/bin/phpunit --no-coverage --no-logging --do-not-cache-result
& $php84 spark serve --host localhost --port 8080
```

Stop the existing development server before reusing port 8080. This CLI runtime is NTS; it is not an Apache module replacement. For XAMPP Apache, install and configure a compatible PHP 8.4 web runtime separately.

This local runtime has SQLSRV **5.13.3** installed as `php_sqlsrv.dll` and enabled in `php.ini`; the existing x64 ODBC Driver 17 is available. On another machine, install the [official SQLSRV driver for PHP 8.4](https://pecl.php.net/package/sqlsrv/5.13.3/windows) matching x64 and NTS, enable its DLL, and install a supported Microsoft ODBC driver. Add PDO_SQLSRV if your environment uses PDO. SQLite is only for isolated automated tests.

## Linux production server

Before deploying this branch:

1. Back up the application, deployment configuration, and database. Keep `.env` outside Git.
2. Install PHP 8.4 CLI and FPM, Composer 2, and extensions `intl`, `mbstring`, `gd`, `zip`, `dom`, `fileinfo`, `xml`, `xmlreader`, `xmlwriter`, plus `sqlsrv` and its supported Microsoft ODBC driver. PHP extensions must match the 8.4 runtime.
3. Change the Nginx site's `fastcgi_pass` to the actual PHP 8.4 FPM socket (commonly `unix:/run/php/php8.4-fpm.sock`). Keep the document root at `/var/www/html/PcDetail/public`.
4. Give the FPM service account write access to `writable/`; retain the server's `.env` and verify database connectivity in staging.
5. Run `php8.4 /usr/local/bin/composer install --no-dev --prefer-dist --optimize-autoloader`, then `php8.4 /usr/local/bin/composer check-platform-reqs --no-dev`. Adjust `COMPOSER_BIN` if Composer is elsewhere.
6. Test login, token refresh, admin access, PC history, and PDF/Excel export using staging data before allowing production traffic.

`build/deploy.sh` validates PHP version and SQLSRV availability before updating the checkout, installs locked production dependencies, and restarts `php8.4-fpm`. CI passes `GITHUB_SHA` so deployment uses the revision that passed tests. The runner needs the existing deployment permissions for Git, Composer, and service management. Ensure Nginx points to the new socket before the first deploy; the script does not edit Nginx configuration. Deploy updates the checkout in place, so schedule the first migration in a maintenance window.

Rollback requires restoring the previous application **and its matching vendor dependencies and PHP/FPM configuration together**. Merely switching the PHP executable back to 7.4 will not run the upgraded dependencies.

## Automated checks

CI tests pull requests targeting `main` and pushes to `main` with PHP 8.4. Deployment waits for tests and only runs for a push to `main`. Unit tests use a separate SQLite in-memory database or mocked models; they do not validate the production SQL Server driver or live database behavior.

## Validation on 2026-10-26

- PHP 8.4.27, CodeIgniter 4.7.4, PHPUnit 11.5.56; Composer validation and platform checks passed.
- PHP syntax checked across 131 application, test, and entry-point files.
- PHPUnit: 18 tests, 32 assertions passed, including PDF generation, Excel round-trip, admin access, and JWT refresh.
- JavaScript rendering/XSS and token refresh tests passed.
- HTTP smoke checks: login 200 with SQLSRV database initialization, both JavaScript assets 200, unauthenticated API 401, guest admin redirect 302.
- Production deployment, authenticated end-to-end workflows, and database writes were not performed.
