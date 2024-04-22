<?php

require_once('DatabaseEngine.php');


class DatabaseEngineJSON  extends DatabaseEngine {
    
    private $handle = null;
    private static $_PATH = __DIR__ . "/config.json";
    
    public function __construct () {
        $this->handle = $this->GetHandle();
    }

    //
    // Get a Full Table
    //  [
    //      [data, ...],
    //      [data, ...],
    //  ]
    //
    public function GetTableAsFlatArray(string $tableName) : array {
        return [];
    } 


    //
    // Find a coresponding file and additional information for a variable
    //
    public function GetTemplatePath(string $templateVarName) : array{

        return [];
    }

    private function GetHandle() {

        if(file_exists(self::$_PATH)) {
            try {
                return fopen(self::$_PATH, 'r+', false);
            } catch (Exception $e) {
                throw new Exception("Database busy", 1);
            }
        }

       //NO FILE?
        try {
            return fopen(self::$_PATH, 'a+', false);
        } catch (Exception $e) {
            throw new Exception("Database busy", 1);
        }
    }

}