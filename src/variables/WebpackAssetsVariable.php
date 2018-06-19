<?php
/**
 * WebpackAssets plugin for Craft CMS 3.x
 *
 * Webpack assets for craft cms
 *
 * @link      https://www.lahautesociete.com
 * @copyright Copyright (c) 2018 La Haute Société
 */

namespace lhs\webpackassets\variables;
use lhs\webpackassets\WebpackAssets;


/**
 * Class WebpackAssetsVariable
 * @package lhs\webpackassets\variables
 */
class WebpackAssetsVariable
{
    /**
     * Return CSS tags
     * @param string|array $chunkNames [optional] A list of webpack chunk names used to filter the CSS assets returned
     * @return string HTML tags
     * @throws \Exception
     */
    public function cssTags($chunkNames = null)
    {
        $files = WebpackAssets::$plugin->jsonReader->getCssFiles($chunkNames);
        $tags = implode("\r\n", array_map([$this, 'getCssTagFromFile'], $files));

        return $tags;
    }

    /**
     * Return Js tags
     * @param string|array $chunkNames [optional] A list of webpack chunk names used to filter the CSS assets returned
     * @return string HTML tags
     * @throws \Exception
     */
    public function jsTags($chunkNames = null)
    {
        $files = WebpackAssets::$plugin->jsonReader->getJsFiles($chunkNames);

        $tags = implode("\r\n", array_map([$this, 'getJsTagFromFile'], $files));

        return $tags;
    }

    public function isPublicPathAbsoluteUrl()
    {
        return WebpackAssets::$plugin->jsonReader->isPublicPathAbsoluteUrl();
    }

    /**
     * Create CSS tag from file
     * @param $file
     * @return string
     */
    private function getCssTagFromFile($file)
    {
        return '<link rel="stylesheet" href="' . $file . '"/>';
    }

    /**
     * Create JS tag from file
     * @param $file
     * @return string
     */
    private function getJsTagFromFile($file)
    {
        return '<script src="' . $file . '"></script>';
    }
}
