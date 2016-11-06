<?php

namespace Weew\RouterConfigurator;

use Weew\Http\HttpRequestMethod;
use Weew\Router\IRouter;
use Weew\RouterConfigurator\Exception\InvalidConfigurationException;

class RouterConfigurator implements IRouterConfigurator {
    /**
     * @param IRouter $router
     * @param array $config
     */
    public function processConfig(IRouter $router, array $config) {
        $this->processFilters($router, $config);
        $this->processEnabledFilters($router, $config);
        $this->processResolvers($router, $config);
        $this->processPrefix($router, $config);
        $this->processProtocol($router, $config);
        $this->processTLD($router, $config);
        $this->processDomain($router, $config);
        $this->processSubdomain($router, $config);
        $this->processHost($router, $config);
        $this->processController($router, $config);
        $this->processRoutes($router, $config);
        $this->processGroups($router, $config);
    }

    /**
     * @param IRouter $router
     * @param array $config
     *
     * @throws InvalidConfigurationException
     */
    protected function processFilters(IRouter $router, array $config) {
        $filters = array_get($config, 'filters', []);

        if ( ! is_array($filters)) {
            throw new InvalidConfigurationException(
                'Routing config "filters" must be an array.'
            );
        }

        foreach ($filters as $filter) {
            $name = array_get($filter, 'name');
            $handler = array_get($filter, 'filter');

            if (empty($name)) {
                throw new InvalidConfigurationException(
                    'Routing filters must have a "name".'
                );
            }

            if ( ! is_callable($handler)) {
                throw new InvalidConfigurationException(
                    'Routing filters must have a callable "filter".'
                );
            }

            $router->addFilter($name, $handler);
        }
    }

    /**
     * @param IRouter $router
     * @param array $config
     */
    protected function processEnabledFilters(IRouter $router, array $config) {
        $filters = array_get($config, 'filter', []);

        if ( ! is_array($filters)) {
            $filters = [$filters];
        }

        foreach ($filters as $filter) {
            $router->enableFilter($filter);
        }
    }

    /**
     * @param IRouter $router
     * @param array $config
     *
     * @throws InvalidConfigurationException
     */
    protected function processResolvers(IRouter $router, array $config) {
        $resolvers = array_get($config, 'resolvers', []);

        if ( ! is_array($resolvers)) {
            throw new InvalidConfigurationException(
                'Routing resolvers "resolvers" must be an array.'
            );
        }

        foreach ($resolvers as $resolver) {
            $name = array_get($resolver, 'name');
            $handler = array_get($resolver, 'resolver');

            if (empty($name)) {
                throw new InvalidConfigurationException(
                    'Routing resolver must have a "name".'
                );
            }

            if ( ! is_callable($handler)) {
                throw new InvalidConfigurationException(
                    'Routing resolver must have a callable "resolver".'
                );
            }

            $router->addResolver($name, $handler);
        }
    }

    /**
     * @param IRouter $router
     * @param array $config
     */
    protected function processPrefix(IRouter $router, array $config) {
        $prefix = array_get($config, 'prefix');

        if ( ! empty($prefix)) {
            $router->addPrefix($prefix);
        }
    }

    /**
     * @param IRouter $router
     * @param array $config
     */
    protected function processProtocol(IRouter $router, array $config) {
        $protocol = array_get($config, 'protocol');

        if ( ! empty($protocol)) {
            $router->restrictProtocol($protocol);
        }
    }

    /**
     * @param IRouter $router
     * @param array $config
     */
    protected function processTLD(IRouter $router, array $config) {
        $tld = array_get($config, 'tld');

        if ( ! empty($tld)) {
            $router->restrictTLD($tld);
        }
    }

    /**
     * @param IRouter $router
     * @param array $config
     */
    protected function processDomain(IRouter $router, array $config) {
        $domain = array_get($config, 'domain');

        if ( ! empty($domain)) {
            $router->restrictDomain($domain);
        }
    }

    /**
     * @param IRouter $router
     * @param array $config
     */
    protected function processSubdomain(IRouter $router, array $config) {
        $subdomain = array_get($config, 'subdomain');

        if ( ! empty($subdomain)) {
            $router->restrictSubdomain($subdomain);
        }
    }

    /**
     * @param IRouter $router
     * @param array $config
     */
    protected function processHost(IRouter $router, array $config) {
        $host = array_get($config, 'host');

        if ( ! empty($host)) {
            $router->restrictHost($host);
        }
    }

    /**
     * @param IRouter $router
     * @param array $config
     */
    protected function processController(IRouter $router, array $config) {
        $controller = array_get($config, 'controller');

        if ( ! empty($controller)) {
            $router->setController($controller, false);
        }
    }

    /**
     * @param IRouter $router
     * @param array $config
     *
     * @throws InvalidConfigurationException
     */
    protected function processRoutes(IRouter $router, array $config) {
        $routes = array_get($config, 'routes', []);

        if ( ! is_array($routes)) {
            throw new InvalidConfigurationException(
                'Routing config "routes" must be an array.'
            );
        }

        foreach ($routes as $route) {
            list($method, $path, $action) = $this->gatherRouteFacts($route);

            if (empty($method) || empty($path) || empty($action)) {
                $message = s(
                    'Route must provide a "method", "path" and an "action". ' .
                    'Received method: "%s", path: "%s", action: "%s".',
                    is_scalar($method) ? $method : get_type($method),
                    is_scalar($path) ? $path : get_type($path),
                    is_scalar($action) ? $action : get_type($action)
                );

                throw new InvalidConfigurationException($message);
            }

            $router->route($method, $path, $action);
        }
    }

    /**
     * @param IRouter $router
     * @param array $config
     *
     * @throws InvalidConfigurationException
     */
    protected function processGroups(IRouter $router, array $config) {
        $groups = array_get($config, 'groups', []);

        if ( ! is_array($groups)) {
            throw new InvalidConfigurationException(
                'Routing config "groups" must be an array.'
            );
        }

        foreach ($groups as $group) {
            if ( ! is_array($group)) {
                throw new InvalidConfigurationException(s(
                    'Routing "groups" must consist of arrays, received "%s".',
                    get_type($group)
                ));
            }

            $this->processConfig($router->group(), $group);
        }
    }

    /**
     * @param array $definition
     *
     * @return array
     */
    protected function gatherRouteFacts(array $definition) {
        $route = array_get($definition, 'route');
        $method = null;
        $path = null;
        $action = null;

        // routes is expected to be in format like:
        // GET POST /some/path someAction
        if (is_string($route)) {
            $parts = preg_split('/\s+/', $route);
            $parts = array_map('trim', $parts);

            foreach ($parts as $part) {
                if (HttpRequestMethod::isValid($part) || $part === 'ANY') {
                    if ($method === null) {
                        $method = $part;
                    } else if ( ! is_array($method)) {
                        $method = [$method, $part];
                    } else {
                        $method[] = $part;
                    }
                } else if ($path === null) {
                    $path = $part;
                } else if ($action === null) {
                    $action = $part;
                }
            }
        }

        if ($method === null) {
            $method = array_get($definition, 'method');
        }

        if ($method === 'ANY' || (is_array($method) && array_contains($method, 'ANY'))) {
            $method = HttpRequestMethod::getMethods();
        }

        if ($path === null) {
            $path = array_get($definition, 'path');
        }

        if ($action === null) {
            $action = array_get($definition, 'action');
        }

        return [$method, $path, $action];
    }
}
