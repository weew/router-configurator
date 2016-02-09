<?php

namespace Weew\RouterConfigurator;

use Weew\Router\IRouter;

interface IRouterConfigurator {
    /**
     * @param IRouter $router
     * @param array $config
     */
    function processConfig(IRouter $router, array $config);
}
