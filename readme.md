A content management system for [Laravel 5](http://laravel.com/).

https://github.com/diyphpdeveloper/cms-canvas/wiki

[![Latest Stable Version](https://poser.pugx.org/diyphpdeveloper/cmscanvas/v/stable)](https://packagist.org/packages/diyphpdeveloper/cmscanvas)
[![Total Downloads](https://poser.pugx.org/diyphpdeveloper/cmscanvas/downloads)](https://packagist.org/packages/diyphpdeveloper/cmscanvas)
[![Latest Unstable Version](https://poser.pugx.org/diyphpdeveloper/cmscanvas/v/unstable)](https://packagist.org/packages/diyphpdeveloper/cmscanvas)
[![License](https://poser.pugx.org/diyphpdeveloper/cmscanvas/license)](https://packagist.org/packages/diyphpdeveloper/cmscanvas)

# Requirements

CMS Canvas requires Laravel 5.2, 5.3, 5.4, 5.5, 5.6, 5.7, or 5.8 and a MySQL server.

# Installation

Require this package with Composer

```bash
composer require diyphpdeveloper/cmscanvas:2.1.*
```

Create the database

```bash
mysql -uroot -p -e "create database cmscanvas"
```

Update your .env file or config/database.php to use the cmscanvas database

```bash
...
DB_HOST=localhost
DB_DATABASE=cmscanvas
DB_USERNAME=root
DB_PASSWORD=root
...
```

# Quick Start

1. Once Composer has installed or updated you will need to register CMS Canvas's user model with Laravel. To do this update the providers array in config/auth.php to:

    ```php
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => CmsCanvas\Models\User::class,
        ],
    ],
    ```

2. If running **Larevel 5.2 to 5.4** you will need to manually register providers and aliases. If you are running Laravel 5.5 or greater this step can be skipped. Open up config/app.php and find the providers key, towards the end of the file, and add the following just prior to the application service providers:

    ```php
    'providers' => [
        ...
            CmsCanvas\Providers\CmsCanvasServiceProvider::class,
            CmsCanvas\Providers\RouteServiceProvider::class,
            CmsCanvas\Providers\EventServiceProvider::class,
            CmsCanvas\Providers\TwigBridgeServiceProvider::class,
            Collective\Html\HtmlServiceProvider::class,
            Intervention\Image\ImageServiceProvider::class,

            /*
             * Application Service Providers...
             */
        ...
    ],
    ```

    Now find the alliases key, again towards the end of the file, and add the following to the end:

    ```php
    'aliases' => [
        ...
            'Admin'     => CmsCanvas\Support\Facades\Admin::class,
            'Content'   => CmsCanvas\Support\Facades\Content::class,
            'Theme'     => CmsCanvas\Support\Facades\Theme::class,
            'StringView' => CmsCanvas\Support\Facades\StringView::class,
            'Form'      => Collective\Html\FormFacade::class,
            'HTML'      => Collective\Html\HtmlFacade::class,
            'Twig'      => TwigBridge\Facade\Twig::class,
    ],
    ```

3. Now that config/app.php and config/auth.php is configured, use Artisan to add CMS Canvas's templates and configs:

    ```php
    php artisan vendor:publish
    ```

4. Next use Artisan to create CMS Canvas's database tables:

    ```php
    php artisan migrate
    ```

5. Populate the database tables with the default data required for CMS Canvas to run:

    ```php
    php artisan db:seed --class="CmsCanvas\Database\Seeds\DatabaseSeeder"
    ```

6. Make the following directories writable:

    ```bash
    chmod 777 public/diyphpdeveloper/cmscanvas/thumbnails
    chmod 777 public/diyphpdeveloper/cmscanvas/uploads
    ```

7. Finally, remove any root (home page) routes from app/Http/routes.php (Laravel 5.2) or routes/web.php (Laravel 5.3)

    ```php
    // app/Http/routes.php (Laravel 5.2) or routes/web.php (Laravel 5.3)
    // The following is an example of what to remove:
    - Route::get('/', function () {
    -     return view('welcome');
    - });
    ```

8. To access the admin panel go to your web browser and visit:

    ```
    http://yourdomain.com/sitemin
    Email: admin@domain.com
    Password: password
    ```

Once you are logged in the first thing you should do is change your email and password!!!

For full documentation see https://github.com/diyphpdeveloper/cms-canvas/wiki
