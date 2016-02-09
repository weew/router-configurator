<?php

namespace Tests\Weew\RouterConfigurator\Mocks;

use Weew\Router\IRouter;
use Weew\RouterConfigurator\RouterConfigurator as BaseRouterConfigurator;

class RouterConfigurator extends BaseRouterConfigurator {
    public function processFilters(IRouter $router, array $config) {
        parent::processFilters($router, $config);
    }

    public function processResolvers(IRouter $router, array $config) {
        parent::processResolvers($router, $config);
    }

    public function processConfig(IRouter $router, array $config) {
        parent::processConfig($router, $config);
    }

    public function processPrefix(IRouter $router, array $config) {
        parent::processPrefix($router, $config);
    }

    public function processProtocol(IRouter $router, array $config) {
        parent::processProtocol($router, $config);
    }

    public function processTLD(IRouter $router, array $config) {
        parent::processTLD($router, $config);
    }

    public function processDomain(IRouter $router, array $config) {
        parent::processDomain($router, $config);
    }

    public function processSubdomain(IRouter $router, array $config) {
        parent::processSubdomain($router, $config);
    }

    public function processHost(IRouter $router, array $config) {
        parent::processHost($router, $config);
    }

    public function processController(IRouter $router, array $config) {
        parent::processController($router, $config);
    }

    public function processRoutes(IRouter $router, array $config) {
        parent::processRoutes($router, $config);
    }

    public function processGroups(IRouter $router, array $config) {
        parent::processGroups($router, $config);
    }

    public function gatherRouteFacts(array $definition) {
        return parent::gatherRouteFacts($definition);
    }

}
