<?php
/**
 * WebpackAssets plugin for Craft CMS 3.x
 *
 * Webpack assets for craft cms
 *
 * @link      https://www.lahautesociete.com
 * @copyright Copyright (c) 2018 La Haute Société
 */

namespace lhs\webpackassets\services;

use Craft;
use craft\base\Component;
use Exception;

/**
 * Class JsonReader
 * @package lhs\webpackassets\services
 */
class JsonReader extends Component
{
    protected $cachedChunks;


    /**
     * Return content of JSON file
     * @return mixed
     * @throws Exception
     */
    private function getDataFromJson()
    {
        $jsonPath = Craft::$app->config->getConfigFromFile('webpackassets')['jsonPath'];

        if (!file_exists($jsonPath)) {
            throw new Exception('WebpackAssets : Unable to find generated assets JSON file.');
        }

        $fileContent = file_get_contents($jsonPath);

        return json_decode($fileContent, true);
    }

    /**
     * Extract the chunks from the webpack JSON output
     * @throws Exception If the webpack output has unexpected content
     */
    protected function getChunks()
    {
        $webpackOutput = $this->getDataFromJson();
        $chunks = [];

        if (
            !isset($webpackOutput['files']) ||
            !isset($webpackOutput['files']['chunks'])
        ) {
            throw new Exception('Unexpected webpack output content');
        }

        foreach ($webpackOutput['files']['chunks'] as $name => $chunk) {
            $chunks[$name] = [
                'js'  => [$chunk['entry']],
                'css' => $chunk['css'],
            ];
        }

        return $chunks;
    }


    /**
     * Extract the chunks from the webpack JSON output
     * @throws Exception If the webpack output has unexpected content
     */
    protected function getCachedChunks()
    {

        if (!$this->cachedChunks) {
            $this->cachedChunks = $this->getChunks();
        }

        return $this->cachedChunks;
    }

    /**
     * Returns an array containing the full paths to the JS assets, optionally filtered by $chunkNames
     * @param string|array $chunkNames [optional] A list of webpack chunk names used to filter the JS assets returned
     * @return array An array of full paths to the JS assets
     * @throws Exception
     */
    public function getJsFiles($chunkNames = null)
    {
        $chunks = $this->getCachedChunks();
        $paths = [];

        // Ensure $chunkNames is either null or an array
        if (!is_null($chunkNames) && !is_array($chunkNames)) {
            $chunkNames = [$chunkNames];
        }

        foreach ($chunks as $name => $chunk) {
            if (is_null($chunkNames) || in_array($name, $chunkNames)) {
                $paths = array_merge($paths, $chunk['js']);
            }
        }

        $paths = $this->transformToAbsolutePath($paths);

        return $paths;
    }


    /**
     * Returns an array containing the full paths to the CSS assets, optionally filtered by $chunkNames
     * @param string|array $chunkNames [optional] A list of webpack chunk names used to filter the CSS assets returned
     * @return array An array of full paths to the CSS assets
     * @throws Exception
     */
    public function getCssFiles($chunkNames = null)
    {
        $chunks = $this->getCachedChunks();
        $paths = [];

        // Ensure $chunkNames is either null or an array
        if (!is_null($chunkNames) && !is_array($chunkNames)) {
            $chunkNames = [$chunkNames];
        }

        foreach ($chunks as $name => $chunk) {
            if (is_null($chunkNames) || in_array($name, $chunkNames)) {
                $paths = array_merge($paths, $chunk['css']);
            }
        }

        $paths = $this->transformToAbsolutePath($paths);

        return $paths;
    }

    /**
     * Transform all paths to absolute path
     * @param $paths array
     * @return array
     */
    private function transformToAbsolutePath($paths)
    {
        return array_map(function ($path) {
            if ($this->isRelativePath($path)) {
                return Craft::getAlias('@web') . '/' . $path;
            }

            return $path;
        }, $paths);
    }

    /**
     * Return true if $path is a relative path
     * @param $path string
     * @return bool
     */
    private function isRelativePath($path)
    {
        return substr($path, 0, '4') != 'http';
    }
}
