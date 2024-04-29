<?php

$result = youtube_audio_url($url);
$title = remove_invalid($check['title']);
$author_name = remove_invalid($check['channelTitle']);
// $author_url = $check->author_url;
$thumbnail_url = $check['thumbnails'];

$thumbnail_path = "media/img/$video_id.jpg";
$audio_path = "media/audio/".str_replace('%','',str_replace("+",' ',urlencode($title)))." $AD - $video_id.mp3";
if($result){
    
    if(file_exists($audio_path)){
        $file_size = filesize($audio_path);
        if($file_size < 500){
            unlink($audio_path);
        }
    }
    if(!file_exists($audio_path)){
        foreach($result as $res){
            if(file_exists($audio_path)) unlink($audio_path);
            $audio_url = $res['url'];
            $get = file_get_contents($audio_url);
            $size = strlen($get);
            if($size>1000){
                file_put_contents($audio_path, $get);
                break;
            }
        }
        if(!file_exists($audio_path)){
            $res = get_audio_url($video_id);
            $get = file_get_contents($res);
            file_put_contents($audio_path, $get);
        }
    }
    if(file_exists($audio_path)){
        require "function/put_thumb.php";
        include "function/getid3_writetags.php";
        $file_size = filesize($audio_path);
        write_tags($audio_path, $thumbnail_path, $title, $author_name);
        require "function/send_audio.php";
    }else{
        returner(message: 'I cant download audio', result: $result, error: true);
    }
}else{
    returner(message: "i cant download audio", error: TRUE);
}


