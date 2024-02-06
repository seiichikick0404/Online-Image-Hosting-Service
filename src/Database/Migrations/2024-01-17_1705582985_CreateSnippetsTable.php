<?php

namespace Database\Migrations;

use Database\SchemaMigration;

class CreateSnippetsTable implements SchemaMigration
{
    public function up(): array
    {
        // マイグレーションロジックをここに追加してください
        return [
            "CREATE TABLE snippets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                url VARCHAR(255) NOT NULL UNIQUE,
                expiration DATETIME,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                syntax_id INT,
                FOREIGN KEY (syntax_id) REFERENCES syntaxes(id)
            )"
        ];
    }

    public function down(): array
    {
        // ロールバックロジックを追加してください
        return ["DROP TABLE IF EXISTS snippets;"];
    }
}