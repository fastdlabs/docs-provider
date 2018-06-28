# fastd-swagger

FastD Swagger 是一个整合 [swagger](https://github.com/zircote/swagger-php) 文档的一个组件，初衷是用于自动生成API文档，但是请做好写 **注释** 的准备。

### 使用

FastD Swagger 提供一个可生成文档的命令工具。

```
$ composer require fastd/docs-provider
```

```php
<?php

return [
    'services' => [
        \FastD\DocsProvider\DocumentProvider::class,
    ]
];
```

文档类型支持 `markdown`, `postman`，为了更好地管理API接口，未来还可能会整合其他接口管理平台。

```php
$ php bin/console document [-m|-e|-p]
```

### Support

如果你在使用中遇到问题，请联系: [bboyjanhuang@gmail.com](mailto:bboyjanhuang@gmail.com). 微博: [编码侠](http://weibo.com/ecbboyjan)

## License MIT
