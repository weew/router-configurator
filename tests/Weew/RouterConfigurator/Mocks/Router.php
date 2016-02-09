<?php

namespace Tests\Weew\RouterConfigurator\Mocks;

use Weew\Router\Router as BaseRouter;

class Router extends BaseRouter {
    public function getNesterRouters() {
        return $this->nestedRouters;
    }
}
