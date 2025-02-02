
# ZBF-Core

ZBF-Core is a minimalistic PHP MVC framework designed as a Composer package. It provides a simple and lightweight structure for building web applications.

## Installation

Since this package is not published on Packagist, you need to manually add the repository to your `composer.json` file before installing it.

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/zborovo-sk/zbf-core.git"
        }
    ],
    "require": {
        "zborovo-sk/zbf-core": "dev-main"
    }
}
```

Run Composer to install dependencies:

```sh
composer install
```

## Usage

Once installed, you can use ZBF-Core to create and run your application.

### Basic Example

Create an entry point (e.g., `public/index.php`) and initialize the framework:

```php
<?php

// Load Composer's autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

use ZborovoSK\ZBFCore\ZBFApp;

$app = new ZBFApp(
    __DIR__ . '/',       // Webroot/public directory
    __DIR__ . '/../App/' // App directory
);

$app->registerRoute(
    'GET',      // HTTP method (use "ALL" or "*" for all methods)
    '/',        // Path (can contain URL parameters like /article/{article_id})
    App\Controllers\DashboardController::class,  // Controller class
    'index'     // Action method
);

$app->run();
```

### Routing

You can register routes using `$app->registerRoute()`. It takes the following parameters:

- **HTTP Method**: e.g., `GET`, `POST`, `PUT`, `DELETE`, `ALL`.
- **Path**: The route URL, which can contain parameters (e.g., `/user/{id}`).
- **Controller Class**: The fully qualified name of the controller handling the request.
- **Action Method**: The method within the controller that will be executed.

## Directory Structure

```
project-root/
│── App/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   ├── Models/
│   ├── Templates/
│   │   ├── Layouts/
│   │   ├── Elements/
│   │   ├── Dashboard/
│   │   │   ├── index.php
│── public/
│   ├── index.php
│── vendor/
│── composer.json
```

## Contributing

Feel free to contribute by submitting pull requests or reporting issues.

## License

This project is licensed under the GPL-3.0 License.

