<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2018
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\DocsProvider\Console;


use FastD\Application;
use FastD\DocsProvider\Document\AbstractDocument;
use FastD\DocsProvider\Document\Markdown;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DocumentConsole
 * @package FastD\DocsProvider\Console
 */
class DocumentConsole extends Command
{
    public function configure()
    {
        $this->setName('document')->setDescription('Create document into the code.');
        $this->addOption('postman', '-p', InputOption::VALUE_OPTIONAL, 'Use postman format');
        $this->addOption('eolinker', '-e', InputOption::VALUE_OPTIONAL, 'Use eolinker api manage');
        $this->addOption('markdown', '-m', InputOption::VALUE_OPTIONAL, 'Use markdown format');
    }

    /**
     * @param InputInterface $input
     * @return AbstractDocument
     */
    protected function makeDocumentObject(InputInterface $input)
    {
        if ($input->hasParameterOption(['--markdown', '-m'])) {
            return new Markdown();
        } elseif ($input->hasParameterOption(['--paostman', '-p'])) {

        } elseif ($input->hasParameterOption(['--eolinker', '-e'])) {

        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \ReflectionException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $config = app()->getPath().'/swagger.json';
        if (!file_exists($config)) {
            file_put_contents($config, json_encode([
                "swagger" => Application::VERSION,
                "schemes" => ["http", "https"],
                "basePath" => "/"
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        $path = config()->get('document.path', app()->getPath().'/docs');

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $document = $this->makeDocumentObject($input);

        $this->scan($document, $path);
    }

    /**
     * @param $document
     * @param $path
     * @throws \ReflectionException
     */
    protected function scan($document, $path)
    {
        foreach (route()->aliasMap as $routes) {
            foreach ($routes as $route) {
                $swagger = scan_route($route);
                if (isset($swagger->paths) && !empty($swagger->paths)) {
                    $callback = $route->getCallback();
                    if (is_array($callback)) {
                        list($controller, ) = $callback;
                    } else if (is_string($callback)) {
                        list($controller, ) = explode('@', $callback);
                    }
                    $content = $document->make($controller, $swagger);;
                    $controller = str_replace('\\', '_', strtolower(trim($controller, '\\')));
                    file_put_contents($path.'/'.$controller.$document->suffix(), $content);
                    output()->writeln($document->getController().' created');
                }
            }
        }
    }
}