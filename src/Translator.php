<?php
/**
 * Created by PhpStorm.
 * User: m.valiolahi
 * Date: 11/7/2017
 * Time: 6:59 PM
 */

namespace Mvaliolahi\Translator;


use Mvaliolahi\Translator\Exceptions\TranslationFileNotFoundException;

/**
 * Class Translator
 * @package Mvaliolahi\Translator
 */
class Translator
{
    /**
     * Locale to figure-out translations.
     * @var
     */
    protected $locale;

    /**
     * When translation not found check this folder.
     * @var
     */
    protected $baseLocale;

    /**
     * Resources\lang\en and other folders.
     * @var null
     */
    protected $resourcesPath;

    /**
     * Translation constructor.
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->locale = $config['locale'] ?? 'en';
        $this->resourcesPath = $config['resourcePath'];
    }

    /**
     * @param $fileDotKey
     * @param array $placeHolders
     * @return string
     */
    public function of($fileDotKey, $placeHolders = null)
    {
        [$file, $key] = $this->extractFileAndSearchKey($fileDotKey);
        $translate = self::translate($file, $key);

        if ($placeHolders) {
            $keys = array_keys($placeHolders);
            $values = array_values($placeHolders);

            return str_replace(':', '', str_replace($keys, $values, $translate));
        }

        return $translate;
    }

    /**
     * @param $fileDotKey
     * @return array
     */
    private function extractFileAndSearchKey($fileDotKey = 'messages.test')
    {
        $file = explode('.', $fileDotKey)[0];
        $key = explode('.', $fileDotKey)[1] ?? $file;
        $file = self::defaultFileForGetTranslation($file, $key);

        return [$file, $key];
    }

    /**
     * @param $file
     * @param $key
     * @return string
     */
    private function defaultFileForGetTranslation($file, $key): string
    {
        if ($file == $key) {
            return 'messages';
        }

        return $file;
    }

    /**
     * @param $file
     * @param $key
     * @return string
     * @throws TranslationFileNotFoundException
     */
    private function translate($file, $key)
    {
        $translationFile = "{$this->getResourcesPath()}/{$file}.php";

        if (file_exists($translationFile)) {
            $file = require $translationFile;

            return $file[$key] ?? 'Undefined translation.'; //TODO:: check in default locale
        }

        throw new TranslationFileNotFoundException("Oops, the {$file} not found in {$translationFile}");
    }

    /**
     * @return string
     */
    public function getResourcesPath()
    {
        $path = trim($this->resourcesPath, '/');

        return "{$path}/{$this->locale}";
    }
}