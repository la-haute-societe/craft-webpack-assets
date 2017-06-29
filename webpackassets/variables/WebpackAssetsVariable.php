<?php

namespace Craft;


class WebpackAssetsVariable
{
    /**
     * Return CSS tags
     */
    public function cssTags()
    {
        $files = craft()->webpackAssets_jsonReader->getCssFiles();
        $tags = implode("\r\n", array_map([$this, 'getCssTagFromFile'], $files));

        return $tags;
    }

    /**
     * Return Js tags
     */
    public function jsTags()
    {
        $files = craft()->webpackAssets_jsonReader->getJsFiles();

        $tags = implode("\r\n", array_map([$this, 'getJsTagFromFile'], $files));

        return $tags;
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