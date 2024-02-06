<?php

namespace Helpers;

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


    public static function isNoEmpty($value): bool
    {
        return $value;
    }
}