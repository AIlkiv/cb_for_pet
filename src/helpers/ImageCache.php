<?php

namespace crocodicstudio\crudbooster\helpers;

use Config;
use Intervention\Image\ImageManager;

class ImageCache
{
    /**
     * @var string
     */
    protected static $cachePath = 'storage/imagecache';

    /**
     * @param  callable  $callback
     * @param  int|null  $lifetime
     * @return \Intervention\Image\Image
     */
    public static function cache(string $key, callable $callback, $lifetime = null)
    {
        $cachePath = storage_path('imagecache');
        $hash = md5($key);

        // Визначаємо шлях до кеш-файлу
        $cacheFile = $cachePath . DIRECTORY_SEPARATOR . $hash;

        // Перевіряємо наявність кешу
        if (file_exists($cacheFile)) {
            // Якщо задано час життя кешу
            if ($lifetime !== null) {
                $cacheTime = filemtime($cacheFile);
                $expireTime = $cacheTime + ($lifetime * 60);

                // Якщо кеш прострочений, видаляємо файл
                if (time() > $expireTime) {
                    unlink($cacheFile);
                } else {
                    // Повертаємо зображення з кешу
                    return file_get_contents($cacheFile);
                }
            } else {
                // Безстроковий кеш
                return file_get_contents($cacheFile);
            }
        }

        // Генеруємо нове зображення
        $manager = new ImageManager(Config::get('image'));
        $image = $callback($manager);

        // Створюємо директорію для кешу, якщо її немає
        if (!is_dir($cachePath)) {
            mkdir($cachePath, 0755, true);
        }

        // Зберігаємо зображення в кеш
        $image->save($cacheFile);

        return $image;
    }

    /**
     * Встановлює шлях до директорії кешу.
     *
     * @param  string  $path
     * @return void
     */
    public static function setCachePath($path)
    {
        $cachePath = $path;
    }
}
