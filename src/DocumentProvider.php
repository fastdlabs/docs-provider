<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\DocsProvider;


use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\DocsProvider\Console\DocumentConsole;

class DocumentProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return mixed
     */
    public function register(Container $container)
    {
        config()->merge([
            'consoles' => [
                DocumentConsole::class
            ]
        ]);
    }
}