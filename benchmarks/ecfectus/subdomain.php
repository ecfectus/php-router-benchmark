<?php

$generator->host('subdomain{%hostId%}.domain.com', '*');
$generator->template('/controller{%id%}/action{%id%}/{id}/{arg1}/{arg2}');

$buildRoutes = box('ecfectus')->get('build-routes');

list($ids, $routes) = $buildRoutes($generator, [
    'nbHosts'  => box('benchmark')->get('nbHosts'),
    'isolated' => box('benchmark')->get('isolated')
]);

$benchmark->run('Ecfectus', function() use ($box, $generator, $ids, $routes) {

    $addRoutes = box('ecfectus')->get('add-routes');
    $router = $addRoutes($routes);

    $strategy = box('benchmark')->get('strategy');

    foreach ($generator->hosts() as $host) {
        foreach ($generator->methods() as $method) {
            $id = $strategy($ids, $method, $host);

            try {
                $router->match("{$host}/controller{$id}/action{$id}/{$id}/arg1/arg2", $method);
            } catch (Exception $e) {
                return false;
            }
        }
    }
});
