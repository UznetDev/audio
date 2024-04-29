<?php

function returner($ok = False,$result = Null,$meta = Null, $message = Null, $code = 0,  $error = Null){
    $ds = array("Developer"=>"@UZNet_Dev",
                "Service"=>"@ApisTelegramBot",
                "description"=>"Youtube downloader", 
                "download"=>true,
                "limit"=>Null,
                "done"=>NULL,);

    $myObj = new stdClass();
    $myObj->ok = $ok;
    $myObj->description = $ds;
    $myObj->meta = $meta;
    $myObj->code = $code;
    $myObj->message = $message;
    $myObj->result = $result;
    $myObj->error = $error;
    $myJSON = json_encode($myObj,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    header("Content-Type: application/json; charset=UTF-8");
    echo $myJSON;

}