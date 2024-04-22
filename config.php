<?php

require_once('database/DatabaseEngineSQL.php');
require_once('database/DatabaseEngineJSON.php');

enum Database {
    case JSON;
    case MYSQL;

    public function DatabaseEngine() : DatabaseEngineSQL|DatabaseEngineJSON {
        $dbEngine = match($this) {
            Database::JSON => new DatabaseEngineJSON(),
            Database::MYSQL => new DatabaseEngineSQL()
        };

        return $dbEngine;
    }
}

Class Config {

    private const Database = Database::JSON;

    public static function DatabaseEngine() : DatabaseEngineSQL|DatabaseEngineJSON {
        return self::Database->DatabaseEngine();
    }

}