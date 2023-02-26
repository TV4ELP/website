<?php
require_once('database/DatabaseEngine.php');
require_once('parser/DefaultParser.php');
//Every Request will be redirected to here!

/*
    To configure SQL Settings check /database/DatabaseEngine.php
*/


//
// These 3 functions do all the heavy lifting of finding a file
//
$allpaths = GetRegisteredPaths();
$path = GetRealPath($allpaths);
$realPath = GetRealFileFromPath($path);
// Now we need to parse any relevant things and send the output back
// Doing so with a dedicated Class/Wrapper allows us to do more fancy things in the future like output buffering
// Compared to a simple 'require(file)'
$parser = new Parser($realPath);
$parser->OutputContent();


//
// We need to register every path a User could request
//
function GetRegisteredPaths() : array {
    $paths = array();

    $conn = new DatabaseEngine();
    $paths = $conn->GetTableAsFlatArray('paths');

    return $paths;
}

//
// Match the request to our registered paths
// No path = /sites/home template
//
function GetRealPath(array $paths) : string {
    $requestUrl = $_SERVER['REQUEST_URI'];
    foreach($paths as $row){

        if (trim($requestUrl) === $row[0]){
            return $row[1];
        }
    }

    //Fallback will be always home page
    return "";
    return $paths[0][1];
}

//
// Search for a template with the correct path
//
function GetRealFileFromPath(string $path) : string {
    $file = Parser::FindFile($path);
    
    //If we can't find something, it means we have stray site in our DB
    //This is a valid case for a 404
    if($file == ""){
        return "templates/sites/404.html";
    }

    return $file;
}


?>
