<?php

if(49 < $file_size / (1024*1024)){

    $MadelineProto = new \danog\MadelineProto\API('session.madeline');
    $MadelineProto->start();
    $data = [
        'peer' => '@'.Channel,
        'media' => [
            '_' => 'inputMediaUploadedDocument',
            'file' => $audio_path,
            'attributes' => [
            ['_' => 'documentAttributeAudio', 'voice' => false, 'title' => $title, 'performer' => $author_name]
            ]],
        'message' => '@General_Music_Bot',
        'parse_mode' => 'Markdown'];
    $send = $MadelineProto->messages->sendMedia($data);
    $message_id = $send["updates"][0]['id'];
    $send = send_audio($video_id, 'https://t.me/'.Channel.'/'.$message_id, $title, $author_name, $thumbnail_path);

    // $name = $send['request']['body']['media']['file']['name'];
    // $file_id = $response->result->audio->file_id;
    // $file_unique_id = $response->result->audio->file_unique_id;
    // $file_size = $send['updates'][2]['message']['media']['document']['size'];
    // $duration = $send["updates"][2]['message']['media']['document']['attributes'][0]['duration'];
    // $mime_type = $send['updates'][2]['message']['media']['document']['mime_type'];
    if($send){
        unlink($audio_path);
        unlink($thumbnail_path);
    }
}else{

    $data = [
        'chat_id' => '@' . Channel,
        'audio' => new CURLFile($audio_path),
        'title' => $title,
        'performer' => $author_name,
        'thumb' => new CURLFile($thumbnail_path),
        'caption' => '@General_Music_Bot', 
        'parse_mode' => 'html'
    ];
    
    $url = "https://api.telegram.org/bot" . API_KEY . "/sendAudio";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
    $res = curl_exec($ch);
    
    if(curl_error($ch)){
        returner(message: "cURL xatosi: " . curl_error($ch), error: TRUE);
    }else {
        $response = json_decode($res);
        if($response->ok) {
            $message_id = $response->result->message_id;
            $name = $response->result->audio->file_name;
            $file_id = $response->result->audio->file_id;
            $file_size = $response->result->audio->file_size;
            $duration = $response->result->audio->duration;
            $file_unique_id = $response->result->audio->file_unique_id;
            $mime_type = $response->result->audio->mime_type;
            $set = set_data(video_id: $video_id, message_id: $message_id, file_id: $file_id, file_unique_id:  $file_unique_id, mime_type: $mime_type, file_size: $file_size, author_name: $author_name, title: $title, duration: $duration);
            if($set){
                unlink($audio_path);
                unlink($thumbnail_path);
            }
        } else{
            returner(message: $response->description, error: true);
        }
    }
    curl_close($ch);
}

