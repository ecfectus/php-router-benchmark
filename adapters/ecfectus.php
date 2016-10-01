<?php
use Lead\Box\Box;

$box = box('ecfectus', new Box());

$box->service('build-routes', function() {

    return function($generator, $options = []) {
        $placeholderTemplate = function($name, $pattern) {
            return "{" . $name . ":alphanumdash}";
        };

        return $generator->generate($placeholderTemplate, null, $options);
    };
});

$box->service('add-routes', function() {

    return function($routes) {
        $router = \Ecfectus\Router\CachedRouter::create('routes.php');

        if(!$router->isCached()){

            foreach ($routes as $route) {

                $r = (new \Ecfectus\Router\Route())->setPath($route['pattern'])->setMethods($route['methods']);

                if ($route['host'] !== "*") {
                    $r->setDomain($route['host']);
                }

                $router->addRoute($r);
            }
            $router->compileRegex();
            $router->export();
        }
        return $router;
    };
});
