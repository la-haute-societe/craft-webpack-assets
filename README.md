# Webpack assets - Craft plugin

A simple plugin allowing to handle [Webpack](https://webpack.js.org) generated CSS and Javascript assets within [Craft CMS](http://craftcms.com/) templates.

## Requirements

This plugin requires **Craft CMS 3.0.0-RC1** or later.
It is intended to work with **Webpack 2 or 3**.

### Installation

- Install with composer from your project directory:
```
composer require la-haute-societe/craft-webpack-assets 
```

### Webpack setup

- To generate the intended JSON files, add the following NPM packages:
```
yarn add html-webpack-plugin write-file-webpack-plugin underscore-template-loader -D
# Or
npm install html-webpack-plugin write-file-webpack-plugin underscore-template-loader --save-dev
```

- Somewhere in your assets source folder, create a `assets-files.json.tpl` file with the following content :
```
<%= JSON.stringify( htmlWebpackPlugin ) %>
```

- In the Webpack config add the following plugins :
```js
{
    plugins: [
        new HtmlWebpackPlugin({
            filename: 'generated-assets-files.json',
            template: 'underscore-template-loader!path/to/templates/assets-files.json.tpl',
            inject: false,
            chunksSortMode: 'dependency'
        }),
        new WriteFilePlugin({
            test:  /generated-assets-files\.json$/,
            force: true,
            log: false
        })
    ]
}
```
> Adjust the `template` value to the path of the previously created `assets-files.json.tpl` file.

- Finally, run your Webpack process, as usual.

## Configuration

In the `config` folder at the root of your Craft project, create a `webpackassets.php` file with the following content
and adjust the path to the JSON generated by Webpack.

```php
<?php

return [
    'jsonPath' => realpath(__DIR__ . '/path/to/generated-assets-files.json'),
];
```

## Usage

In your Twig templates, you can include your Webpack generated assets as follow:

```twig
<html>
<head>
    ...
   
    {{ craft.webpackAssets.cssTags() | raw }}
    
    ... Or with chunk name ...
    {{ craft.webpackAssets.cssTags('app') | raw }}
   
    ...
</head>
<body>
...

{{ craft.webpackAssets.jsTags() | raw }}

... Or with chunk name ...

{{ craft.webpackAssets.jsTags('app') | raw }}
{{ craft.webpackAssets.jsTags('libs') | raw }}

...
</body>
</html>
```

You can detect if the public path provided by webpack is an absolute URL with the method `isPublicPathAbsoluteUrl` (this is the case when assets are served by Webpack).

If needed, you can override the webpack public path at runtime when this path is not absolute :
```
{# injecte le public path au runtime car pas connu au build. __webpack_public_path__ #}
{% if not craft.webpackAssets.isPublicPathAbsoluteUrl() %}
<script>
    runtime_webpack_public_path = '{{ siteUrl }}assets/';
</script>
{% endif %}
```

Brought to you by ![LHS Logo](resources/img/lhs.png) [La Haute Société](https://www.lahautesociete.com)
