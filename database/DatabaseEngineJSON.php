<?php

require_once('DatabaseEngine.php');


class DatabaseEngineJSON  extends DatabaseEngine {
    
    private $handle = null;

    private Array $paths = [];
    private string $data = "";

    private static string $_PATH = __DIR__ . "/config.json";
    
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

        
        if($tableName === "paths") {
            $paths = $this->GetPaths();
            return $paths;

        }


        if($tableName === "template_variables") {

        }

        return [];

    } 


    //
    // Load the paths only if needed, else cached member variable
    //
    private function GetPaths() : array{

        

        //Only reread the paths if we don't have them.
        if(count($this->paths) === 0) {
            
            $encodedPaths = $this->GetTable("paths");
            if(count($encodedPaths) > 0) {
                $this->paths = $encodedPaths;
            } else {

                //Something wen't supre wrong. Default site
                $this->paths = ['/' => "/sites/home"];
            }
        }

        return $this->paths;
    }


    //
    // Just a single table already decoded as array
    //
    private function GetTable(string $tableName) : array {

        $fileSize = filesize(self::$_PATH);

        $data = fread($this->handle, $fileSize);


        //We read new stuff? Append
        if(strlen($data != 0)) {
            $this->data .= $data;
        } 

        $encoded = json_decode($this->data, true);

        if(is_array($encoded) === false) {
            return [];
        }

        if(isset($encoded[$tableName]) && count($encoded[$tableName]) > 0) {
            return $encoded[$tableName];
        }

        return $encoded;
    }

    //
    // Reset the Values so the next read uses fresh data
    //
    private function InvalidateCache() {
        $this->paths = [];
    }


    //
    // Find a coresponding file and additional information for a variable
    //
    public function GetTemplatePath(string $templateVarName) : array{

        $encodedVars = $this->GetTable("template_vars");

        foreach($encodedVars as $templateVarArray) {

            if($templateVarArray["var"] === $templateVarName) {
                return [$templateVarArray]; //needs to be wrappen in an array due to historical reasons
            }

        }

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
            $handle =  fopen(self::$_PATH, 'a+', false);

            //Default values 
            $defaultJson = <<< def
                {
                    "paths" : [
                        ["/", "/sites/home"]
                    ],
                    "template_vars" : [
                        {"var" : "default-css", "replacement" : "/style.html", "localFile" : true},
                        {"var" : "default_header", "replacement" : "/blocks/header.html", "localFile" : true},
                        {"var" : "main-menu", "replacement" : "/blocks/main-menu.html", "localFile" : true}
                    ]
                }
            
            def;

            fwrite($handle, $defaultJson);

            return $handle;
        } catch (Exception $e) {
            throw new Exception("Database busy", 1);
        }
    }

}