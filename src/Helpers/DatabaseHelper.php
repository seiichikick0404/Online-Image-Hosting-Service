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

            $sql = "INSERT INTO images (ip_address, title, image_path, image_url, delete_url, view_count, last_access_time, byte_size) 
                VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";

            if (!$stmt = $db->prepare($sql)) {
                throw new Exception('Failed to prepare statement: ' . $db->error);
            }

            $deleteUrl = "sample_delete_url"; // 仮の削除URL
            $imageUrl = ImageHelper::generateImageShowPath($image['name']);
            $viewCount = 0;
            $byteSize = $image['size'];

            $stmt->bind_param('sssssii', 
                $ipAddress,
                $title,
                $imagePath,
                $imageUrl,
                $deleteUrl,
                $viewCount,
                $byteSize
            );

            if (!$stmt->execute()) {
                return [
                    'success' => false,
                    'message' => 'データベースへの登録に失敗しました。',
                ];
            }

        } catch(Exception $e) {
            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ];
        }

        return [
            'success' => true,
            'message' => '画像の登録が完了しました。',
            'data' => [
                'imageUrl' => $imageUrl,
                'deleteUrl' => $deleteUrl,
            ],
        ];
    }

    public static function checkDailyUploadLimit(string $ipAddress): bool
    {
        $db = new MySQLWrapper();
        $today = date("Y-m-d");

        $stmt = $db->prepare(
            "SELECT COUNT(*) AS uploads_today
            FROM images
            WHERE ip_address = ?
            AND DATE(created_at) = ?"
        );
        $stmt->bind_param('ss', $ipAddress, $today);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $dailyUploadLimit = 4;
        if ($row) {
            return $row['uploads_today'] <= $dailyUploadLimit;
        } else {
            return true;
        }
    }

    public static function checkDailyUploadCapacityLimit(string $ipAddress, int $byteSize): bool
    {
        $db = new MySQLWrapper();
        $today = date("Y-m-d");

        $stmt = $db->prepare(
            "SELECT SUM(byte_size) AS total_bytes
            FROM images
            WHERE ip_address = ?
            AND DATE(created_at) = ?"
        );

        $stmt->bind_param('ss', $ipAddress, $today);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // 5MBの上限をバイトで指定
        $dailyUploadLimitBytes = 5 * 1024 * 1024;

        if ($row && $row['total_bytes'] !== null) {
            return $row['total_bytes'] + $byteSize <= $dailyUploadLimitBytes;
        } else {
            return true;
        }
    }

}