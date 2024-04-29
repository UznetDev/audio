<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require_once "db/mysql_db.php";
require_once "data/config.php";
require_once "function/returner.php";
require_once "data/config.php";
require_once "function/function.php";

if(isset($url)){
    require "function/youtube.php";
    $video_id = getVideoId($url);
    $res = $conn->query("SELECT * FROM `youtube_audio` WHERE `video_id`='$video_id'");
    if($res->num_rows){
        try{
            $ro = $res->fetch_assoc();
            $message_id = $ro["message_id"];
            $file_id = $ro["file_id"];
            $file_unique_id = $ro["file_unique_id"];
            $mime_type = $ro["mime_type"];
            $duration = $ro["duration"];
            $file_size = $ro["file_size"];   
            $author_name = $ro["author_name"];
            $uploadDate = $ro["date"];
            $title = $ro["title"];
            $meta = array("title"=>$title,
                          "owner"=>$author_name);

            $data = array("id"=>$video_id,           
                          "url"=>'https://t.me/YouTube_Downs/'.$message_id, 
                          "file_id"=>$file_id,
                          "file_unique_id"=>$file_unique_id, 
                          "mime_type"=>$mime_type, 
                          "duration"=>$duration, 
                          "file_size"=>$file_size); 
            returner(True, $data, $meta, Null, 200,  null);            
            }
            catch(Exception $err) {
                returner(message: $err, code: 0,  error: true);
            }
    }else{
        returner(message: $err, code: 0,  error: true);
    }
}else{
    returner(message: "url empity", code: 0, error: true);
}

?>