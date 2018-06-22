<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\DocsProvider\Document;


use Swagger\Annotations\Operation;
use Swagger\Annotations\Path;
use Swagger\Annotations\Swagger;

abstract class AbstractDocument
{
    protected $controller;

    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param Operation $annotation
     * @return mixed
     */
    abstract public function template(Operation $annotation);

    /**
     * @return string
     */
    abstract public function suffix();

    protected function swagger($controller, Swagger $swagger)
    {
        $get_path = function (Path $path) {
            if (!empty($path->get)) {
                return $path->get;
            } elseif (!empty($path->post)) {
                return $path->post;
            } elseif (!empty($path->delete)) {
                return $path->delete;
            } elseif (!empty($path->options)) {
                return $path->options;
            } elseif (!empty($path->put)) {
                return $path->put;
            } elseif (!empty($path->patch)) {
                return $path->patch;
            } else {
                return $path->head;
            }
        };
        $template = '';
        foreach ($swagger->paths as $path) {
            $path = $get_path($path);
            $template .= $this->template($path);
        }

        return $template;
    }

    /**
     * @param $controller
     * @param Swagger $swagger
     * @return string
     */
    public function make($controller, Swagger $swagger)
    {
        $this->controller = $controller;

        return $this->swagger($controller, $swagger);
    }
}