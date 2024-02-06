<?php

namespace Database;

use mysqli;
use Helpers\Settings;

class MySQLWrapper extends mysqli{
    public function __construct(?string $hostname = null, ?string $username = null, ?string $password = null, ?string $database = null, ?int $port = null, ?string $socket = null)
    {
        /*
            接続の失敗時にエラーを報告し、例外をスローします。データベース接続を初期化する前にこの設定を行ってください。
            テストするには、.env設定で誤った情報を入力します。
        */
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        $username = $username??Settings::env('DATABASE_USER');
        $password = $password??Settings::env('DATABASE_PASSWORD');
        $database = $database??Settings::env('DATABASE_NAME');
        $hostname = $hostname??Settings::env('DATABASE_HOST');

        parent::__construct($hostname, $username, $password, $database, $port, $socket);
    }

    // クエリが問い合わせられるデフォルトのデータベースを取得します。
    // エラーは失敗時にスローされます（つまり、クエリがfalseを返す、または取得された行がない）
    // これらに対処するためにifブロックやcatch文を使用することもできます。
    public function getDatabaseName(): string{
        return $this->query("SELECT database() AS the_db")->fetch_row()[0];
    }

    /**
     * 現在のバックアップ用のSQLを発行
     */
    public function backup($backupFilePath) {
        // バックアップファイルを開く
        $fileHandle = fopen($backupFilePath, 'w');
        if ($fileHandle === false) {
            throw new \Exception("Cannot open the backup file: $backupFilePath");
        }

        // データベースの全テーブルを取得
        $tables = $this->query("SHOW TABLES")->fetch_all(MYSQLI_NUM);
        if ($tables === false) {
            throw new \Exception("Failed to retrieve the tables.");
        }

        // 各テーブルのデータをバックアップ
        foreach ($tables as $table) {
            $tableName = $table[0];

            // テーブルの構造をダンプ
            $createStatement = $this->query("SHOW CREATE TABLE `$tableName`")->fetch_assoc();
            fwrite($fileHandle, $createStatement['Create Table'] . ";\n\n");

            // テーブルのデータをダンプ
            $rows = $this->query("SELECT * FROM `$tableName`");
            while ($row = $rows->fetch_assoc()) {
                $vals = array_map([$this, 'real_escape_string'], array_values($row));
                fwrite($fileHandle, "INSERT INTO `$tableName` VALUES ('" . implode("', '", $vals) . "');\n");
            }
            fwrite($fileHandle, "\n\n");
        }

        // ファイルを閉じる
        fclose($fileHandle);
    }
}