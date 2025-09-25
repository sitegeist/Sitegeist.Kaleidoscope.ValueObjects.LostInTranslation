<?php
declare(strict_types=1);

namespace Sitegeist\Kaleidoscope\ValueObjects\LostInTranslation;

use Sitegeist\Kaleidoscope\ValueObjects\ImageSourceProxy;
use Sitegeist\Kaleidoscope\ValueObjects\ImageSourceProxyCollection;
use Sitegeist\LostInTranslation\Domain\TranslationConnectorInterface;

/**
 * @implements TranslationConnectorInterface<ImageSourceProxyCollection>
 */
class ImageSourceProxyCollectionLostInTranslationConnector implements TranslationConnectorInterface {

    /**
     * @param ImageSourceProxyCollection $object
     * @return array<string, string>
     */
    public function extractTranslations(object $object): array
    {
        $translations = [];
        foreach ($object as $key => $item) {
            $subTranslations = ImageSourceProxyLostInTranslationConnector::extractTranslationsStatic($item);
            foreach ($subTranslations as $subkey => $subvalue) {
                $translations[$key . '_' . $subkey] = $subvalue;
            }
        }
        return $translations;
    }

    /**
     * @param ImageSourceProxyCollection $object
     * @param array<string, string> $translations
     * @return ImageSourceProxyCollection
     */
    public function applyTranslations(object $object, array $translations): object
    {
        $translationsByIndex = [];
        foreach ($translations as $key => $value) {
            list($index, $subkey) = explode('_', $key, 2);
            if (!array_key_exists($index, $translationsByIndex)) {
                $translationsByIndex[$index] = [$subkey => $value];
            } else {
                $translationsByIndex[$index][$subkey] = $value;
            }
        }
        $imageSources = [];
        foreach ($object as $key => $item) {
            if (array_key_exists($key, $translationsByIndex)) {
                $imageSources[$key] = ImageSourceProxyLostInTranslationConnector::applyTranslationsStatic($item, $translationsByIndex[$key]);
            } else {
                $imageSources[$key] = $item;
            }
        }
        return new ImageSourceProxyCollection(...$imageSources);
    }
}
