<?php

use Swagger\Analysis;
use Swagger\StaticAnalyser;

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

/**
 * @param \FastD\Routing\Route $route
 * @return string|\Swagger\Annotations\Swagger
 * @throws ReflectionException
 */
function scan_route (\FastD\Routing\Route $route) {
    $callback = $route->getCallback();
    if (is_array($callback)) {
        list($controller, ) = $callback;
    } else if (is_string($callback)) {
        list($controller, ) = explode('@', $callback);
    } else {
        $controller = '';
    }

    if (!empty($controller)) {
        $rfc = new ReflectionClass($controller);
        $analyser = new StaticAnalyser();
        $analysis = new Analysis();
        $processors =  Analysis::processors();
        $analysis->addAnalysis($analyser->fromFile($rfc->getFileName()));
        $analysis->process($processors);
        return $analysis->swagger;
    }

    return '';
}