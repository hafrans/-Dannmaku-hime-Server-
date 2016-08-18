<?php
if(strpos($_SERVER["PHP_SELF"], "key.php") !== false){
    @header("HTTP/1.0 404 Not Found");
    exit("");
}
return array(
    "upload_key" => "HIME",
    "download_key" => "DAMA",
    "queue-name" =>  "msg"
);