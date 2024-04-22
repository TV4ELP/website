<?php

require_once('DatabaseEngine.php');


class DatabaseEngineSQL  extends DatabaseEngine {
    private static string $_HOST = "localhost";
    private static string $_TABLE = "website";
    private static string $_USER = "main";
    private static string $_PASSWORD = "main";

    private $connection = null;

    public function __construct () {
        $conn = new mysqli(self::$_HOST, self::$_USER, self::$_PASSWORD, self::$_TABLE);

        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        } 

        //Setup the connection to be used troughout the class
        $this->connection = $conn;
    }

    //
    // Get a Full Table via stored procedure
    //  [
    //      [data, ...],
    //      [data, ...],
    //  ]
    //
    public function GetTableAsFlatArray(string $tableName) : array {
        //PL only matches letters. Everything not a letter is bad
        $tableName = preg_replace('/\PL/u', '', $tableName);
        $sql = "SELECT * FROM $tableName";
        $result = $this->connection->query($sql);
        
        $data = mysqli_fetch_all($result);

        return $data;
    } 


    //
    // Find a coresponding file and additional information for a variable
    //
    public function GetTemplatePath(string $templateVarName) : array{

        $sql = "SELECT * FROM template_variables WHERE var = \"$templateVarName\"";

        $result = $this->connection->query($sql);
        //We could use mysqli_fetch_row (since each var is unique, but the speed difference is virtually not measureable)
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $data;
    }

}