<?php

namespace Commands\Programs;

use Commands\AbstractCommand;
use Database\MySQLWrapper;
use Commands\Argument;
use Exception;
use Helpers\Settings;

class DbWipe extends AbstractCommand
{
    // 使用するコマンド名を設定
    protected static ?string $alias = 'db-wipe';
    // protected static bool $requiredCommandValue = true;

    // 引数を割り当て
    public static function getArguments(): array
    {
        return [
            (new Argument('backup'))->description('Roll backup db tables.')->required(false)->allowAsShort(true),
        ];
    }

    public function execute(): int
    {
        $is_backup = $this->getArgumentValue('backup');

        if ($is_backup) {
            $this->log('Taking a backup of the practice_db database...');

            $mysqlWrapper = new MySQLWrapper();
            $backupFilePath = 'Dumps/backup_' . date('YmdHis') . '.sql';

            try {
                $mysqlWrapper->backup($backupFilePath);
                echo "Backup to {$backupFilePath} completed successfully.";
            } catch (\Exception $e) {
                echo "Error during backup: " . $e->getMessage();
            }
        }

        exit;

        $mysqli = new MySQLWrapper();

        $this->log('removing practice db tables.......');

        $result = $mysqli->query(file_get_contents(__DIR__ . '/../../Database/BlogBook/db-fresh.sql'));


        if ($result === false) throw new Exception('Could not execute query.');
        else print("Successfully fresh db".PHP_EOL);


        return 0;
    }
}