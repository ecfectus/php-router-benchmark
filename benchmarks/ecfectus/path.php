<?php

$generator->template('/controller{%id%}/action{%id%}/{id}/{arg1}/{arg2}');

$buildRoutes = box('ecfectus')->get('build-routes');

list($ids, $routes) = $buildRoutes($generator, [
    'isolated' => box('benchmark')->get('isolated')
]);

$benchmark->run('Ecfectus', function() use ($box, $generator, $ids, $routes) {

    $addRoutes = box('ecfectus')->get('add-routes');
    $router = $addRoutes($routes);

    $strategy = box('benchmark')->get('strategy');

    foreach ($generator->methods() as $method) {
        $id = $strategy($ids, $method);

        try{
            $router->match("/controller{$id}/action{$id}/{$id}/arg1/arg2", $method);
        }catch(Exception $e){
            return false;
        }
    }
});
