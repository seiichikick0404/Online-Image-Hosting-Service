<?php

namespace Helpers;

use Database\MySQLWrapper;
use Exception;;
use DateTime;

class DatabaseHelper
{
    public static function getRandomComputerPart(): array{
        $db = new MySQLWrapper();

        $stmt = $db->prepare("SELECT * FROM computer_parts ORDER BY RAND() LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        $part = $result->fetch_assoc();

        if (!$part) throw new Exception('Could not find a single part in database');

        return $part;
    }

    public static function getComputerPartById(int $id): array{
        $db = new MySQLWrapper();

        $stmt = $db->prepare("SELECT * FROM computer_parts WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $part = $result->fetch_assoc();

        if (!$part) throw new Exception('Could not find a single part in database');

        return $part;
    }

    public static function getComputerPartsByType(string $type, string $page, string $perpage): array
    {
        $db = new MySQLWrapper();

        // ページネーションの計算
        $offset = ($page - 1) * $perpage;
        $limit = $perpage;

        // タイプに基づいて部品を取得し、ページネーションを適用する
        $stmt = $db->prepare("SELECT * FROM computer_parts WHERE type = ? LIMIT ? OFFSET ?");
        $stmt->bind_param('sii', $type, $limit, $offset);
        $stmt->execute();

        $result = $stmt->get_result();

        // 全ての結果を取得
        $parts = [];
        while ($part = $result->fetch_assoc()) {
            $parts[] = $part;
        }

        if (!$parts) throw new Exception('Could not find any parts of the specified type in the database');

        return $parts;
    }

    public static function getComputerPartsByPerformance(string $type, string $order): array
    {
        $db = new MySQLWrapper();

        $sql = "SELECT *
                FROM computer_parts
                WHERE type = ?
                ORDER BY performance_score $order LIMIT 50";

        $stmt = $db->prepare($sql);

        $stmt->bind_param('s', $type);
        $stmt->execute();

        $result = $stmt->get_result();

        // 全ての結果を取得
        $parts = [];
        while ($part = $result->fetch_assoc()) {
            $parts[] = $part;
        }

        if (!$parts) throw new Exception('Could not find any parts of the specified type in the database');

        return $parts;
    }

    public static function getSyntaxes(): array
    {
        $db = new MySQLWrapper();

        $sql = "SELECT * FROM syntaxes";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();

        // 全ての結果を取得
        $dataList = [];
        while ($data = $result->fetch_assoc()) {
            $dataList[] = $data;
        }

        if (!$dataList) throw new Exception('Could not find a data in database');

        return $dataList;
    }

    public static function storeSnippet(array $data, string $uniquePath): void
    {
        $db = new MySQLWrapper();

        $expirationDate = self::processExpiration($data['expiration']);

        $sql = "INSERT INTO snippets (title, content, url, expiration, syntax_id) VALUES (?, ?, ?, ?, ?)";

        $stmt = $db->prepare($sql);

        $stmt->bind_param('ssssi',
            $data['title'],
            $data['content'],
            $uniquePath,
            $expirationDate,
            $data['syntax']
        );

        if (!$stmt->execute()) {
            throw new Exception('Failed to store snippet: ' . $stmt->error);
        }
    }

    public static function getSnippet(string $path): array
    {
        $db = new MySQLWrapper();

        $stmt = $db->prepare("SELECT * FROM snippets WHERE url = ?");
        $stmt->bind_param('s', $path);
        $stmt->execute();

        $result = $stmt->get_result();
        $snippet = $result->fetch_assoc();

        // スニペットが見つからない場合にリダイレクト
        self::redirectIfInvalid($snippet);

        $snippet['status'] = true;
        if ($snippet['expiration'] !== null) {
            $expiration = new \DateTime($snippet['expiration'], new \DateTimeZone('Asia/Tokyo'));
            $now = new \DateTime('now', new \DateTimeZone('Asia/Tokyo'));

            // 有効期限が有効化チェック
            if ($expiration < $now) {
                $snippet['status'] = false;
            }
        }
        return $snippet;
    }

    public static function getActiveSnippets(): array
    {
        $db = new MySQLWrapper();

        // 現在の日時よりも後の有効期限、または有効期限がNULLのスニペットを選択
        $sql = "SELECT *
                FROM snippets
                INNER JOIN syntaxes ON snippets.syntax_id = syntaxes.id
                WHERE expiration > NOW()
                OR expiration IS NULL
                ORDER BY snippets.id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();

        // 全ての結果を取得
        $dataList = [];
        while ($data = $result->fetch_assoc()) {
            $dataList[] = $data;
        }

        if (!$dataList) throw new Exception('Could not find a data in database');

        return $dataList;
    }

    private static function redirectIfInvalid($snippet): void
    {
        if (!$snippet) {
            header("Location: ../snippet/create");
            exit;
        }
    }

    private static function processExpiration($expiration): ?string
    {
        if ($expiration === 'forever') {
            return null;
        }

        $now = new \DateTime('now', new \DateTimeZone('Asia/Tokyo'));
        switch ($expiration) {
            case '10min':
                $now->modify('+10 minutes');
                break;
            case '1hour':
                $now->modify('+1 hour');
                break;
            case '1day':
                $now->modify('+1 day');
                break;
            // その他のケース...
        }

        return $now->format('Y-m-d H:i:s');
    }


}