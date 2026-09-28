<?php

declare(strict_types=1);

namespace Sitegeist\Kaleidoscope\ValueObjects\LostInTranslation;

use Sitegeist\Kaleidoscope\ValueObjects\ImageSourceProxy;
use Sitegeist\LostInTranslation\Domain\TranslationConnectorInterface;

/**
 * @implements TranslationConnectorInterface<ImageSourceProxy>
 */
class ImageSourceProxyLostInTranslationConnector implements TranslationConnectorInterface
{
    /**
     * @param ImageSourceProxy $object
     * @return array<string, string>
     */
    public function extractTranslations(object $object): array
    {
        return self::extractTranslationsStatic($object);
    }

    /**
     * @param ImageSourceProxy $object
     * @param array<string, string> $translations
     * @return ImageSourceProxy
     */
    public function applyTranslations(object $object, array $translations): object
    {
        return self::applyTranslationsStatic($object, $translations);
    }

    /**
     * @param ImageSourceProxy $object
     * @return array<string, string>
     */
    public static function extractTranslationsStatic(object $object): array
    {
        $translations = [];
        if ($object->title !== '') {
            $translations['title'] = htmlspecialchars(
                $object->title,
                ENT_QUOTES | ENT_SUBSTITUTE | ENT_XML1,
                'UTF-8',
                false
            );
        }
        if ($object->alt !== '') {
            $translations['alt'] = htmlspecialchars(
                $object->alt,
                ENT_QUOTES | ENT_SUBSTITUTE | ENT_XML1,
                'UTF-8',
                false
            );
        }
        return $translations;
    }

    /**
     * @param ImageSourceProxy $object
     * @param array<string, string> $translations
     * @return ImageSourceProxy
     */
    public static function applyTranslationsStatic(object $object, array $translations): object
    {
        return new ImageSourceProxy(
            $object->asset,
            (array_key_exists('alt', $translations) && $translations['alt'])
                ? htmlspecialchars_decode($translations['alt'], ENT_QUOTES | ENT_XML1)
                : $object->alt,
            (array_key_exists('title', $translations) && $translations['title'])
                ? htmlspecialchars_decode($translations['title'], ENT_QUOTES | ENT_XML1)
                : $object->title
        );
    }
}
