<?php

namespace Helpers;

use Exception;

class ImageHelper 
{
    const TARGET_DIR = __DIR__ . '/../public/images/';

    public function saveImageFile(array $image): string
    {
        $imagePath = self::generateImagePath($image['name']);
        $targetFile = self::TARGET_DIR . $imagePath;

        // ディレクトリの存在を確認し、必要に応じて作成
        $dirPath = dirname($targetFile);
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        // 画像ファイルをサーバーに保存
        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            return $imagePath;
        } else {
            return null;
        }
    }

    public static function generateImagePath(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $uniqueId = md5(uniqid(rand(), true));
        $dir = substr($uniqueId, 0, 2);
        $imagePath = "{$dir}/{$uniqueId}.{$extension}";

        return $imagePath;
    }
}