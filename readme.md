# Router configurator

[![Build Status](https://img.shields.io/travis/weew/php-router-configurator.svg)](https://travis-ci.org/weew/php-router-configurator)
[![Code Quality](https://img.shields.io/scrutinizer/g/weew/php-router-configurator.svg)](https://scrutinizer-ci.com/g/weew/php-router-configurator)
[![Test Coverage](https://img.shields.io/coveralls/weew/php-router-configurator.svg)](https://coveralls.io/github/weew/php-router-configurator)
[![Version](https://img.shields.io/packagist/v/weew/php-router-configurator.svg)](https://packagist.org/packages/weew/php-router-configurator)
[![Licence](https://img.shields.io/packagist/l/weew/php-router-configurator.svg)](https://packagist.org/packages/weew/php-router-configurator)

## Table of contents

## Installation

`composer require weew/php-router-configurator`

## Introduction

This package makes the [weew/php-router](https://github.com/weew/php-router) configurable trough config files or arrays. It is recommended to use the [weew/php-config](https://github.com/weew/php-config) package for the loading of config files.

## Usage

In this example I'll be using a configuration written in yaml:

```yaml
# same as $router->addFilter('auth', [AuthFilter::class, 'filter');
filters:
    - name: auth
      filter: [Foo\Bar\AuthFilter, filter]

# same as $router->addResolver('user', [UserResolver::class, 'resolve');
resolvers:
    - name: user
      resolver: [Foo\Bar\UserResolver, resolve]

# same as $router->restrictProtocol(['http', 'https'])
protocol: [http, https]
# same as $router->restrictTLD(['com', 'net'])
tld: [com, net]
# same as $router->restrictDomain(['foo', 'bar'])
domain: [foo, bar]
# same as $router->restrictSubdomain(['foo', 'bar'])
subdomain: [foo, bar]
# same as $router->restrictHost(['foo.com'])
host: foo.com

# same as $router->group()
groups:
    # name is for readability only
    - name: public
      # same as $router->setController(HomeController::class)
      controller: HomeController
      routes:
        # same as $router->get('/', 'homeAction')
        - method: GET,
          path: /,
          action: homeAction
        # same as $router->route(['GET', 'POST'], 'contactAction')
        - method: [GET, POST],
          path: contact,
          action: contactAction

    - name: api
      # same as $router->enableFilter('auth')
      filter: auth
      # same as $router->setPrefix('api/v1')
      prefix: api/v1

      groups:
       - name: users
         controller: Foo\Bar\UserController
         prefix: /users
         routes:
           - route: GET /,
             action: list
           - route: POST /,
             action: create,
           - rotue: GET {user},
             action: show
           - route: PUT {user},
             action: update
```
