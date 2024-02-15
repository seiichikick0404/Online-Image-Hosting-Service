<?php

namespace Helpers;

use Database\MySQLWrapper;
use Exception;
use DateTime;
use Helpers\ImageHelper;

class DatabaseHelper
{
    public static function createImage(string $title, array $image, string $ipAddress): array
    {
        try {
            // 画像ファイル保存処理
            $imageHelper = new ImageHelper();
            $imagePath = $imageHelper->saveImageFile($image);

            if ($imagePath === null) {
                return [
                    'success' => false,
                    'message' => '画像ファイルの保存に失敗しました。'
                ];
            }

            $db = new MySQLWrapper();

            $sql = "INSERT INTO images (ip_address, title, image_path, image_url, delete_url, view_count, last_access_time) 
                    VALUES (?, ?, ?, ?, ?, ?, NOW())";

            if (!$stmt = $db->prepare($sql)) {
                throw new Exception('Failed to prepare statement: ' . $db->error);
            }

            $deleteUrl = "sample_delete_url"; // 仮の削除URL
            $imageUrl = "sample_image_url"; // 仮の画像URL
            $viewCount = 0;

            $stmt->bind_param('sssssi', 
                $ipAddress,
                $title,
                $imagePath,
                $imageUrl,
                $deleteUrl,
                $viewCount
            );

            if (!$stmt->execute()) {
                throw new Exception('Failed to execute statement: ' . $stmt->error);
            }

        } catch(Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }

        return [
            'success' => true,
            'message' => '画像の登録が完了しました。'
        ];
    }

}