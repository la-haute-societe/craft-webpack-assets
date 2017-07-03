<?php

namespace Craft;


class WebpackAssets_JsonReaderService extends BaseApplicationComponent
{

    protected $cachedChunks;


    /**
     * Return content of JSON file
     * @return mixed
     * @throws Exception
     */
    private function getDataFromJson()
    {
        $jsonPath = craft()->config->get('jsonPath', 'webpackassets');

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

        return $paths;
    }


    /**
     * Returns an array containing the full paths to the CSS assets, optionally filtered by $chunkNames
     * @param string|array $chunkNames [optional] A list of webpack chunk names used to filter the CSS assets returned
     * @return array An array of full paths to the CSS assets
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

        return $paths;
    }
}