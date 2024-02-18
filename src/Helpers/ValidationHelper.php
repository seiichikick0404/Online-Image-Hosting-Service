<?php

namespace Helpers;

use Helpers\DatabaseHelper;

class ValidationHelper
{
    public static function integer($value, float $min = -INF, float $max = INF): int
    {
        // PHPには、データを検証する組み込み関数があります。詳細は https://www.php.net/manual/en/filter.filters.validate.php を参照ください。
        $value = filter_var($value, FILTER_VALIDATE_INT, ["min_range" => (int) $min, "max_range"=>(int) $max]);

        // 結果がfalseの場合、フィルターは失敗したことになります。
        if ($value === false) throw new \InvalidArgumentException("The provided value is not a valid integer.");

        // 値がすべてのチェックをパスしたら、そのまま返します。
        return $value;
    }

    public static function string($value, int $minLength = 0, int $maxLength = PHP_INT_MAX, $pattern = null): string
    {
        // 文字列が指定された長さの範囲内にあるか確認
        $length = mb_strlen($value);
        if ($length < $minLength || $length > $maxLength) {
            throw new \InvalidArgumentException("The string length must be between $minLength and $maxLength characters.");
        }

        // 正規表現パターンが提供されている場合は、そのパターンに合致するか確認
        if ($pattern !== null && !preg_match($pattern, $value)) {
            throw new \InvalidArgumentException("The string does not match the required pattern.");
        }

        // 文字列がすべてのチェックをパスしたら、そのまま返す
        return $value;
    }

    public static function createSnippetPost($postData): array
    {
        $errors = [];

        // Title empty check
        if (empty($postData['title'])) {
            $errors['title'] = "Title is required.";
        } else {
            // Title length check
            try {
                self::string($postData['title'], 1, 255);
            } catch (\Exception $e) {
                $errors['title'] = "The title must be within 255 characters.";
            }
        }

        // Expiration validation
        $validExpirations = ["10min", "1hour", "1day", "forever"];
        if (empty($postData['expiration']) || !in_array($postData['expiration'], $validExpirations)) {
            $errors['expiration'] = "Invalid expiration value selected.";
        }

        // Syntax validation
        if (empty($postData['syntax'])) {
            $errors['syntax'] = "Syntax selection is required.";
        } else {
            // Syntax ID type check
            try {
                self::integer($postData['syntax']);
            } catch (\Exception $e) {
                $errors['syntax'] = "Invalid syntax selected.";
            }
        }

        // Content empty check
        if (empty($postData['content'])) {
            $errors['content'] = "Content is required.";
        } else {
            // Content length check
            try {
                self::string($postData['content'], 1);
            } catch (\Exception $e) {
                $errors['content'] = "There is an issue with the content.";
            }
        }

        return $errors;
    }

    public static function uploadImage(?string $title,  $image, ?string $ipAddress): array
    {
        $validated = [
            'success' => true,
            'errors' => [
                'title' => [],
                'image' => [],
                'ipAddress' => [],
            ],
        ];

        // タイトルのnullチェックと文字数制限チェック
        if (empty($title)) {
            array_push($validated['errors']['title'], "タイトルを入力してください。");
            $validated['success'] = false;
        } elseif (mb_strlen($title) > 255) { // 例: タイトルの最大文字数を255とする
            array_push($validated['errors']['title'], "タイトルは255文字以内で入力してください。");
            $validated['success'] = false;
        }

        // 画像の添付有無、容量制限チェック（3MB以下),  拡張子チェック
        if (empty($image['name'])) {
            array_push($validated['errors']['image'], "画像を添付してください。");
            $validated['success'] = false;
        } elseif ($image['size'] > 3145728) { // 3 * 1024 * 1024 = 3145728
            array_push($validated['errors']['image'], "画像ファイルのサイズは3MB以下にしてください。");
            $validated['success'] = false;
        } else {
            // 拡張子チェック
            $extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif'];
            if (!in_array($extension, $allowedExtensions)) {
                array_push($validated['errors']['image'], "許可されていないファイル形式です。JPEG、PNG、GIFのみが許可されています。");
                $validated['success'] = false;
            }

            // 1日のアップロード制限チェック
            if (!DatabaseHelper::checkDailyUploadLimit($ipAddress)) {
                array_push($validated['errors']['image'], "1日にアップロードできるファイル数は5枚までです。");
                $validated['success'] = false;
            } elseif (!DatabaseHelper::checkDailyUploadCapacityLimit($ipAddress, $image['size'])) {
                array_push($validated['errors']['image'], "1日にアップロードできる総容量は5MBまでです。");
                $validated['success'] = false;
            }
        }

        


        // IPアドレスのフォーマットチェック
        if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) {
            array_push($validated['errors']['ipAddress'], "IPアドレスが不正です。");
            $validated['success'] = false;
        }

        if (!$validated['success']) {
            return $validated;
        }


        return $validated;
    }

}