<?php

//
// Eevery Site can contain a handfull of things
// This parser is able to render them
//
class Parser {

    private string $_template = "";
    private ?DatabaseEngine $_databaseEngine = null;

    //These are needed to match the blocks we want to replace in our template
    private static string $_beforeDelimiter = "{{";
    private static string $_afterDelimiter = "}}";

    public function __construct (string $template) {
        $this->_template = $template;
    }

    //
    // Everything went wrong we need to Fallback to a know good site
    // We don't have a known good site.. because we shouldn't land here if we had one
    //
    private function Fallback() {
        echo "Critical Error";
    }


    //
    // All the blocks and additional things need to be identified and parsed into it
    //
    private function Parse() {

        $templateString = $this->LoadTemplate();

        // Due to PHP handling lookahead/lookbehind stupidly, we have each match 2 times... 
        // This cleans it up
        $this->ReplaceTemplateBlocks($templateString);
        
        echo $templateString;
    }

    //
    // Recursivly Replace every Variable until nothing is left. 
    // This includes variables that resolve into variables 
    //
    private function ReplaceTemplateBlocks (string &$template) : void {
        $matches = array();

        //This magic regex is rather simple if you can read it, so let me explain: 
        // (.*) dot means "any character", the star means "zero or more" in contrast to just one charackter
        // This would match everything not a line break
        // (?<=X) means "before you match, check if there is 'X' infront and only then match it" But also to exclude X from the match
        // This would match everything after X, but not X itself until a line break
        // (?=X) means "before you match, check if there is 'X' after the match, and only then match it" And exclude X from the match
        // So now we match everything that starts with an X and ends with an X, but in our match the X is not contained
        $regexPattern = '/(?<=' . self::$_beforeDelimiter . ')(.*)(?=' . self::$_afterDelimiter . ')/';
        $didMatch = preg_match($regexPattern, $template, $matches);
        
        // IF found something, parse it
        if($didMatch){
            $templateVar = $matches[0];
            $replacement = $this->GetVarData($templateVar);

            $replacePattern = '/(' . self::$_beforeDelimiter . $templateVar . self::$_afterDelimiter . ')/';
            $template = preg_replace($replacePattern, $replacement, $template);

            //Recursivly try to replace everything
            $this->ReplaceTemplateBlocks($template);
        }

        //We have not found a single match? I guess we can stop now
        return;
    }

    //
    // TODO: DEFINE A STRUCT LIKE THING THAT HAS THE VAR AND THE REPLACEMENT AND AN INFO WHERE TO FIND THE REPLACMENT
    // DEFINE IF IT IS A DIRECT REPLACEMENT OR A PATH. WHEN PATH USE THAT. ALSO DEFINE IF THERE IS PHP CODE THAT WE WANT TO 
    // EXECUTE AFTER REPLACEMENTS
    //
    private function GetVarData(string $templateVar) : string {

        $database = $this->GetDataBaseConnection();
        $templateData = $database->GetTemplatePath($templateVar);

        //No result, no fun
        if(count($templateData) == 0){
            return "";
        }

        $data = $templateData[0];
        //Local Files we want to directly fetch and use
        if($data['localFile'] == true){

            $file = self::FindFile($data['replacement'], "");

            //Couldn't find a file, weird
            if($file == ""){
                //TODO: Maybe log this
                return $file;
            }
            $content = file_get_contents($file);
            return $content;
        }

        //Ohterwise return directly
        return $data['replacement'];
    }


    //
    // Find a file based on a relative path
    //
    public static function FindFile(string $path, string $extension = ".php") : string {

        $fileList = glob('templates' . $path . $extension);
        print_r($fileList);
        //If we match somehow more, just use the first
        //Matching more than one is bad
        if(count($fileList) > 0){
            return $fileList[0];
        }

        return "";
    }


    //
    // Singleton Database
    //
    private function GetDataBaseConnection() : DatabaseEngine {
        if($this->_databaseEngine == null){
            $this->_databaseEngine = new DatabaseEngine();
        }

        return $this->_databaseEngine;
    }


    //
    // Just load the file and make sure all is nice 
    //
    private function LoadTemplate() : string {
        $templateString = file_get_contents($this->_template);

        // We should never get no template because the index should figure that out
        // But if we do, atleast say something. We NEVER want a 500/404 (404 page with 200 header is oki tho)
        if($templateString === false){
            $this->Fallback();
            die;
        }

        return $templateString;
    }

    //
    // Actually Output stuff. We use output buffering to send data faster to the browser
    // Output Buffering also allows us to manipulate all headers (like cookies) without having to worry
    // about outputting anything before
    //
    public function OutputContent() : void {
        
        ob_start();
        $this->Parse();
        ob_end_flush();
    }



}


?>