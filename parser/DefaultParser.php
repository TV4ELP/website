<?php

//
// Eevery Site can contain a handfull of things
// This parser is able to render them
//
class Parser {

    private string $_template = "";


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


    private function Parse() {

        $templateString = $this->LoadTemplate();

        echo $templateString;
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