<?php

namespace Helpers;

use Exception;

class ImageHelper 
{
    const TARGET_DIR = __DIR__ . '/../public/images/';

    public function saveImageFile(array $image): bool
    {
        // 一意の画像パスを生成
        $imagePath = self::generateImagePath($image['name']);
        $targetFile = self::TARGET_DIR . $imagePath;

        // ディレクトリの存在を確認し、必要に応じて作成
        $dirPath = dirname($targetFile);
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        // 画像ファイルをサーバーに保存
        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            return true; // 成功
        } else {
            return false; // 失敗
        }
    }

    public static function generateImagePath(string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $uniqueString = uniqid();
        $dir = substr($uniqueString, 0, 2);
        $imagePath = $dir . '/' . $uniqueString . '.' . $extension;

        return $imagePath;
    }
}