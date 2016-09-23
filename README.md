# phalcon-application

[![Author](http://img.shields.io/badge/author-@mamuz_de-blue.svg?style=flat-square)](https://twitter.com/mamuz_de)
[![Build Status](https://img.shields.io/travis/mamuz/phalcon-application.svg?style=flat-square)](https://travis-ci.org/mamuz/phalcon-application)
[![Latest Stable Version](https://img.shields.io/packagist/v/mamuz/phalcon-application.svg?style=flat-square)](https://packagist.org/packages/mamuz/phalcon-application)
[![Total Downloads](https://img.shields.io/packagist/dt/mamuz/phalcon-application.svg?style=flat-square)](https://packagist.org/packages/mamuz/phalcon-application)
[![License](https://img.shields.io/packagist/l/mamuz/phalcon-application.svg?style=flat-square)](https://packagist.org/packages/mamuz/phalcon-application)

Phalcon Application is built on top of Phalcon3 Framework and provides
simple and customizable application bootstrapping.

## Requirements

- Phalcon3 is needed, follow install steps at https://github.com/phalcon/cphalcon

## Features

- Autoloading with Composer
- Simple mvc configuration
- Service registration
- XHR friendly view renderer

## Usage

### Bootstrapping an application without view support, e.g. a REST application

```php
$config = [
    'dispatcher' => [
        // define beginning class namespace for your controllers
        'controllerDefaultNamespace' => 'Rest\Controller',
    ],
    'routes' => [
        // see Router::add in https://docs.phalconphp.com/en/latest/reference/routing.html
        'default' => [
            'pattern'     => '/:controller/:action',
            'paths'       => ['controller' => 1, 'action' => 2],
            'httpMethods' => ['GET'],
        ],
    ],
    // register custom service factories implementing the InjectableInterface
    // see: https://github.com/mamuz/phalcon-application/blob/master/src/Application/Service/InjectableInterface.php
    // Key is the name to refer to Phalcon's DI, value is the FQCN of the service factory
    'services' => [
        'user'   => 'User\Service\Factory',
        'logger' => 'Logger\Service\Factory',
    ],
];

// make everything relative to the application root
chdir(dirname(__DIR__));

// Composer Autoloader (see: https://getcomposer.org/doc/01-basic-usage.md#autoloading)
include './vendor/autoload.php';

// bootstrap and run your application
Phapp\Application\Bootstrap::init($config)->runApplicationOn($_SERVER);
```

For more details have a look to the functional tests at https://github.com/mamuz/phalcon-application/blob/master/tests/functional/ActionDomainResponseCest.php
based on that [example project](https://github.com/mamuz/phalcon-application/tree/master/tests/_data/StubProject).

### Bootstrapping an application with view support (mostly for output rendered HTML)

Check https://docs.phalconphp.com/en/latest/reference/views.html for using views in Phalcon.

Phalcon's view engine supports the three-step view template pattern.
That means you can have a main-layout (outerframe), which includes a controller based layout (frame),
which in turn includes an action based layout (innerframe).

Like this:
```html
<outerframe>
    I am the main layout.
    <frame>
        I am the controller based layout.
        <innerframe>
            I am the action based layout.
        </innerframe>
    </frame>
</outerframe>
```

So each controller action can have an own template for rendering.

For instance you have a controller with two actions like:
- `User::loginAction`
- `User::logoutAction`

This leads to two view templates located at:
- `{viewbasepath}\user\login.phtml`
- `{viewbasepath}\user\logout.phtml`

Regarding the three-step view template pattern you can place these ones at:
- `{viewbasepath}\index.phtml` (outerframe must be named as index and needs to be placed at the root level)
- `{viewbasepath}\layouts\user.phtml` (frame must be named like the controller and needs to be placed inside layouts folder)

In case of ajax requests (XHR) outerframe and frame rendering is disabled, which means only the innerframe is rendered.

```php
$config = [
    'dispatcher' => [
        // define beginning class namespace for your controllers
        'controllerDefaultNamespace' => 'Mvc\Controller',
    ],
    'routes' => [
        // see Router::add in https://docs.phalconphp.com/en/latest/reference/routing.html
        'default' => [
            'pattern'     => '/:controller/:action',
            'paths'       => ['controller' => 1, 'action' => 2],
            'httpMethods' => ['GET'],
        ],
    ],
    // register custom service factories implementing the InjectableInterface
    // see: https://github.com/mamuz/phalcon-application/blob/master/src/Application/Service/InjectableInterface.php
    // Key is the name to refer to Phalcon's DI, value is the FQCN of the service factory
    'services' => [
        'user'   => 'User\Service\Factory',
        'logger' => 'Logger\Service\Factory',
    ],
    // declare the basepath for the view templates, which enables Phalcon's view engine
    'view' => [
        'templatePath' => './view',
    ],
];

// make everything relative to the application root
chdir(dirname(__DIR__));

// Composer Autoloader (see: https://getcomposer.org/doc/01-basic-usage.md#autoloading)
include './vendor/autoload.php';

// bootstrap and run your application
Phapp\Application\Bootstrap::init($config)->runApplicationOn($_SERVER);
```

For more details have a look to the functional tests at https://github.com/mamuz/phalcon-application/blob/master/tests/functional/ViewCest.php
based on that [example project](https://github.com/mamuz/phalcon-application/tree/master/tests/_data/StubViewProject).

### Bootstrapping an application as a command line tool

Check https://docs.phalconphp.com/en/latest/reference/cli.html#tasks for creating tasks.

```php
$config = [
    'dispatcher' => [
        // define beginning class namespace for your tasks
        'taskDefaultNamespace' => 'Command\Task',
    ],
    // register custom service factories implementing the InjectableInterface
    // see: https://github.com/mamuz/phalcon-application/blob/master/src/Application/Service/InjectableInterface.php
    // Key is the name to refer to Phalcon's DI, value is the FQCN of the service factory
    'services' => [
        'user'   => 'User\Service\Factory',
        'logger' => 'Logger\Service\Factory',
    ],
];

// make everything relative to the application root
chdir(dirname(__DIR__));

// Composer Autoloader (see: https://getcomposer.org/doc/01-basic-usage.md#autoloading)
include './vendor/autoload.php';

// bootstrap and run your application
Phapp\Application\Bootstrap::init($config)->runApplicationOn($_SERVER);
```

### Run a command with arguments

Let's imagine that the application is bootstrapped inside `index.php`

```sh
> php index.php mailing send reminder
```

That will call the `send` action from the `mailing` task with invoking `reminder` as an argument.

For more details have a look to the functional tests at https://github.com/mamuz/phalcon-application/blob/master/tests/functional/CommandLineCest.php
based on that [example project](https://github.com/mamuz/phalcon-application/tree/master/tests/_data/StubProject).

