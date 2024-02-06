<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateSyntaxesTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE syntaxes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ["DROP TABLE syntaxes"];
    }
}