<?php
//All Database Engines troughout this project needs to implement all the functions in here
//Additionally, all new function thatmight be needed, goes in here

abstract class DatabaseEngine {

    public abstract function __construct ();

    //
    // Get a Full Table
    //  [
    //      [data, ...],
    //      [data, ...],
    //  ]
    //
    public abstract function GetTableAsFlatArray(string $tableName) : array;


    //
    // Find a coresponding file and additional information for a variable
    //
    public abstract function GetTemplatePath(string $templateVarName) : array;

}