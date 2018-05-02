<?php

namespace Craft;


class WebpackAssetsVariable
{
    /**
     * Return CSS tags
     * @param string|array $chunkNames [optional] A list of webpack chunk names used to filter the CSS assets returned
     * @return string HTML tags
     */
    public function cssTags($chunkNames = null)
    {
        $files = craft()->webpackAssets_jsonReader->getCssFiles($chunkNames);
        $tags = implode("\r\n", array_map([$this, 'getCssTagFromFile'], $files));

        return $tags;
    }

    /**
     * Return Js tags
     * @param string|array $chunkNames [optional] A list of webpack chunk names used to filter the CSS assets returned
     * @return string HTML tags
     */
    public function jsTags($chunkNames = null)
    {
        $files = craft()->webpackAssets_jsonReader->getJsFiles($chunkNames);

        $tags = implode("\r\n", array_map([$this, 'getJsTagFromFile'], $files));

        return $tags;
    }

    public function isPublicPathAbsoluteUrl()
    {
        return craft()->webpackAssets_jsonReader->isPublicPathAbsoluteUrl();
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
