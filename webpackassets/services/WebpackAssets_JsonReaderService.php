<?php

namespace Craft;


class WebpackAssets_JsonReaderService extends BaseApplicationComponent
{

    /**
     * Return content of JSON file
     * @return mixed
     * @throws Exception
     */
    private function getData()
    {
        $jsonPath = craft()->config->get('jsonPath', 'webpackassets');

        if (!file_exists($jsonPath)) {
            throw new Exception('WebpackAssets : Unable to find generated assets JSON file.');
        }

        $fileContent = file_get_contents($jsonPath);

        return json_decode($fileContent, true);
    }

    /**
     * Return JS file
     * @return mixed
     */
    public function getJsFiles()
    {
        $data = $this->getData();

        if (!isset($data['files']['chunks']['app']['entry'])) {
            return [];
        }

        return [$data['files']['chunks']['app']['entry']];
    }

    /**
     * Return CSS files list
     * @return mixed
     */
    public function getCssFiles()
    {
        $data = $this->getData();

        if (!isset($data['files']['chunks']['app']['css'])) {
            return [];
        }

        return $data['files']['chunks']['app']['css'];

    }
}