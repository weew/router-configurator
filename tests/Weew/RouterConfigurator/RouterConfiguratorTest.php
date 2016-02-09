<?php

namespace Tests\Weew\RouterConfigurator;

use PHPUnit_Framework_TestCase;
use Tests\Weew\RouterConfigurator\Mocks\Router;
use Tests\Weew\RouterConfigurator\Mocks\RouterConfigurator;
use Weew\Config\Config;
use Weew\RouterConfigurator\Exception\InvalidConfigurationException;

class RouterConfiguratorTest extends PHPUnit_Framework_TestCase {
    private function createConfigurator() {
        return new RouterConfigurator();
    }

    private function createRouter() {
        return new Router();
    }

    public function test_process_filters() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = [
            'filters' => [
                ['name' => 'foo', 'filter' => [$this, 'test_process_filters']],
            ]
        ];

        $filters = $router->getRoutesMatcher()->getFiltersMatcher()->getFilters();
        $this->assertEquals(0, count($filters));
        $configurator->processFilters($router, $config);

        $filters = $router->getRoutesMatcher()->getFiltersMatcher()->getFilters();
        $this->assertEquals(1, count($filters));
    }

    public function test_process_filters_without_filter_array() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['filters' => 'foo'];

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processFilters($router, $config);
    }

    public function test_process_filters_without_name() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['filters' => [
            []
        ]];

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processFilters($router, $config);
    }

    public function test_process_filters_without_handler() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['filters' => [
            ['name' => 'foo']
        ]];

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processFilters($router, $config);
    }

    public function test_process_filters_with_invalid_handler() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['filters' => [
            ['name' => 'foo', 'filter' => 'foo']
        ]];

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processFilters($router, $config);
    }

    public function test_process_resolvers() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = [
            'resolvers' => [
                ['name' => 'foo', 'resolver' => [$this, 'test_process_resolvers']],
            ]
        ];

        $resolvers = $router->getRoutesMatcher()->getParameterResolver()->getResolvers();
        $this->assertEquals(0, count($resolvers));
        $configurator->processResolvers($router, $config);

        $resolvers = $router->getRoutesMatcher()->getParameterResolver()->getResolvers();
        $this->assertEquals(1, count($resolvers));
    }

    public function test_process_resolvers_without_filter_array() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['resolvers' => 'foo'];

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processResolvers($router, $config);
    }

    public function test_process_resolvers_without_name() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['resolvers' => [
            []
        ]];

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processResolvers($router, $config);
    }

    public function test_process_resolvers_without_handler() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['resolvers' => [
            ['name' => 'foo']
        ]];

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processResolvers($router, $config);
    }

    public function test_process_resolvers_with_invalid_handler() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['resolvers' => [
            ['name' => 'foo', 'resolver' => 'foo']
        ]];

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processResolvers($router, $config);
    }

    public function test_process_prefix() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['prefix' => 'foo'];
        $configurator->processPrefix($router, $config);
        $this->assertEquals('foo', $router->getPrefix());
    }

    public function test_process_protocol() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['protocol' => 'foo'];
        $configurator->processProtocol($router, $config);
        $this->assertEquals(
            ['foo'],
            $router->getRoutesMatcher()->getRestrictionsMatcher()->getProtocols()
        );
    }

    public function test_process_tld() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['tld' => 'foo'];
        $configurator->processTLD($router, $config);
        $this->assertEquals(
            ['foo'],
            $router->getRoutesMatcher()->getRestrictionsMatcher()->getTLDs()
        );
    }

    public function test_process_domain() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['domain' => 'foo'];
        $configurator->processDomain($router, $config);
        $this->assertEquals(
            ['foo'],
            $router->getRoutesMatcher()->getRestrictionsMatcher()->getDomains()
        );
    }

    public function test_process_subdomain() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['subdomain' => 'foo'];
        $configurator->processSubdomain($router, $config);
        $this->assertEquals(
            ['foo'],
            $router->getRoutesMatcher()->getRestrictionsMatcher()->getSubdomains()
        );
    }

    public function test_process_host() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['host' => 'foo'];
        $configurator->processHost($router, $config);
        $this->assertEquals(
            ['foo'], $router->getRoutesMatcher()->getRestrictionsMatcher()->getHosts()
        );
    }

    public function test_process_controller() {
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $config = ['controller' => 'foo'];
        $configurator->processController($router, $config);
        $this->assertEquals(
            'foo', $router->getController()
        );
    }

    public function test_gather_route_facts_from_route_definition() {
        $definition = [
            'route' => 'GET   POST    /foo/bar',
            'action' => 'yolo',
        ];
        $configurator = $this->createConfigurator();

        list($method, $path, $action) = $configurator->gatherRouteFacts($definition);
        $this->assertEquals(['GET', 'POST'], $method);
        $this->assertEquals('/foo/bar', $path);
        $this->assertEquals('yolo', $action);
    }

    public function test_gather_route_facts_from_incomplete_route_definition() {
        $definition = [
            'route' => '/foo/bar',
            'action' => 'yolo',
        ];
        $configurator = $this->createConfigurator();

        list($method, $path, $action) = $configurator->gatherRouteFacts($definition);
        $this->assertEquals(null, $method);
        $this->assertEquals(null, $path);
        $this->assertEquals('yolo', $action);
    }

    public function test_gather_route_facts_from_regular_definition() {
        $definition = [
            'method' => ['GET', 'POST'],
            'path' => '/foo/bar',
            'action' => 'yolo',
        ];
        $configurator = $this->createConfigurator();

        list($method, $path, $action) = $configurator->gatherRouteFacts($definition);
        $this->assertEquals(['GET', 'POST'], $method);
        $this->assertEquals('/foo/bar', $path);
        $this->assertEquals('yolo', $action);
    }

    public function test_process_groups() {
        $config = [
            'groups' => [
                ['protocol' => 'https'],
            ],
        ];
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();

        $configurator->processGroups($router, $config);
        $router = $router->getNesterRouters()[0];

        $this->assertEquals(
            ['https'], $router->getRoutesMatcher()->getRestrictionsMatcher()->getProtocols()
        );
    }
    
    public function test_process_invalid_groups() {
        $config = ['groups' => 'group'];
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processGroups($router, $config);
    }

    public function test_test_process_invalid_group() {
        $config = ['groups' => ['group']];
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processGroups($router, $config);
    }

    public function test_process_routes() {
        $config = [
            'routes' => [
                ['method' => 'GET', 'path' => '/foo', 'action' => 'bar']
            ]
        ];
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();

        $configurator->processConfig($router, $config);
        $routes = $router->getRoutes();

        $this->assertEquals(1, count($routes));
        $route = $routes[0];

        $this->assertEquals(['GET'], $route->getMethods());
        $this->assertEquals('/foo', $route->getPath());
        $this->assertEquals('bar', $route->getAction());
    }

    public function test_process_invalid_routes() {
        $config = ['routes' => 'route'];
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processConfig($router, $config);
    }

    public function test_route_without_method() {
        $config = ['routes' => [
            ['path' => '/foo', 'action' => 'foo']
        ]];
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processConfig($router, $config);
    }

    public function test_route_without_path() {
        $config = ['routes' => [
            ['method' => 'POST', 'action' => 'foo']
        ]];
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processConfig($router, $config);
    }

    public function test_route_without_action() {
        $config = ['routes' => [
            ['method' => 'POST', 'path' => '/foo']
        ]];
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();

        $this->setExpectedException(InvalidConfigurationException::class);
        $configurator->processConfig($router, $config);
    }

    public function test_process_config() {
        $config = [
            'filters' => [
                ['name' => 'filter', 'filter' => [$this, 'test_process_config']]
            ],
            'resolvers' => [
                ['name' => 'resolver', 'resolver' => [$this, 'test_process_config']]
            ],
            'routes' => [
                ['method' => ['GET', 'POST'], 'path' => '/foo', 'action' => 'foo']
            ],
            'controller' => 'SomeController',
            'prefix' => 'v1',
            'protocol' => 'https',
            'tld' => ['foo', 'bar'],
            'host' => 'foo.bar',
            'domain' => 'foo',
            'subdomain' => 'bar',
            'groups' => [
                [
                    'controller' => 'AnotherController',
                    'routes' => [
                        ['route' => 'POST PUT /bar', 'action' => 'bar']
                    ]
                ]
            ]
        ];
        $router = $this->createRouter();
        $configurator = $this->createConfigurator();
        $configurator->processConfig($router, $config);

        $filters = $router->getRoutesMatcher()->getFiltersMatcher()->getFilters();
        $this->assertEquals(1, count($filters));
        $filter = $filters['filter'];
        $this->assertEquals('filter', $filter->getName());
        $this->assertEquals($filter->getFilter(), [$this, 'test_process_config']);

        $resolvers = $router->getRoutesMatcher()->getParameterResolver()->getResolvers();
        $this->assertEquals(1, count($resolvers));
        $resolver = $resolvers['resolver'];
        $this->assertEquals('resolver', $resolver->getName());
        $this->assertEquals([$this, 'test_process_config'], $resolver->getResolver());

        $routes = $router->getRoutes();
        $this->assertEquals(1, count($routes));
        $route = $routes[0];
        $this->assertEquals(['GET', 'POST'], $route->getMethods());
        $this->assertEquals('/v1/foo', $route->getPath());
        $this->assertEquals(['SomeController', 'foo'], $route->getAction());

        $this->assertEquals('SomeController', $router->getController());
        $this->assertEquals('v1', $router->getPrefix());
        $this->assertEquals(['https'], $router->getRoutesMatcher()->getRestrictionsMatcher()->getProtocols());
        $this->assertEquals(['foo', 'bar'], $router->getRoutesMatcher()->getRestrictionsMatcher()->getTLDs());
        $this->assertEquals(['foo.bar'], $router->getRoutesMatcher()->getRestrictionsMatcher()->getHosts());
        $this->assertEquals(['foo'], $router->getRoutesMatcher()->getRestrictionsMatcher()->getDomains());
        $this->assertEquals(['bar'], $router->getRoutesMatcher()->getRestrictionsMatcher()->getSubdomains());

        /** @var \Weew\Router\Router $router */
        $router = $router->getNesterRouters()[0];
        $this->assertEquals('AnotherController', $router->getController());
        $routes = $router->getRoutes();
        $this->assertEquals(1, count($routes));
        $route = $routes[0];
        $this->assertEquals(['POST', 'PUT'], $route->getMethods());
        $this->assertEquals('/v1/bar', $route->getPath());
        $this->assertEquals(['AnotherController', 'bar'], $route->getAction());
    }
}
