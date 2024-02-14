<?php
class Response {
    public $status;
    public $content;
    
    function send() {
        http_response_code($this->status ?? 200);
        echo json_encode($this->content);
        exit();
    }
}

?>
