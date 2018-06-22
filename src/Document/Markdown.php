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

class Markdown extends AbstractDocument
{
    public function template(Operation $annotation)
    {
        $parameters = '';
        foreach ($annotation->parameters as $parameter) {
            $parameters .= sprintf(
                '|%s|%s|%s|%s|',
                $parameter->name,
                ($parameter->required ? 'true' : 'false'),
                $parameter->type,
                $parameter->description
                )
                .PHP_EOL;
        }

        $responses = '';
        foreach ($annotation->responses as $response) {
            $responses .= '> '.$response->response.PHP_EOL.PHP_EOL;
            $responses .= '```'.PHP_EOL;
            $responses .= !empty($response->examples) ? json_encode($response->examples, JSON_PRETTY_PRINT) : $response->description;
            $responses .= PHP_EOL.'```'.PHP_EOL.PHP_EOL;
        }

        $template = <<<EOF
### {$annotation->summary}

{$annotation->description}

##### 请求地址

```
{$annotation->method} {$annotation->path}
```

#### 请求参数

| 参数名称  |  必填  |   类型   |  说明  |
| :---: | :--: | :----: | :--: |
{$parameters}

##### 返回结果

{$responses}

EOF;

        return $template;
    }

    /**
     * @return string
     */
    public function suffix()
    {
        return '.md';
    }
}