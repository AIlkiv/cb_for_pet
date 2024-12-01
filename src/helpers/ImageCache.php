<?php

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
        $hash = md5($key);

        // Визначаємо шлях до кеш-файлу
        $cacheFile = self::$cachePath . DIRECTORY_SEPARATOR . $hash;

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
                    $manager = new ImageManager();
                    return $manager->make($cacheFile);
                }
            } else {
                // Безстроковий кеш
                $manager = new ImageManager();
                return $manager->make($cacheFile);
            }
        }

        // Генеруємо нове зображення
        $manager = new ImageManager();
        $image = $callback($manager);

        // Створюємо директорію для кешу, якщо її немає
        if (!is_dir(self::$cachePath)) {
            mkdir(self::$cachePath, 0755, true);
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
        self::$cachePath = $path;
    }
}
