<?php

function remove_invalid($text, $to='', $per='"\\/?') {
  $yangi_matn = preg_replace("/[$per]/", $to, $text);
  return $yangi_matn;
}

function getJson($url)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
}


function set_data($video_id, $message_id, $file_id, $file_unique_id, $mime_type, $file_size, $author_name, $title, $duration){
    global $conn;
    $sql = "INSERT INTO `youtube_audio`
    (`video_id`, `message_id`, `file_id`, `file_unique_id`, `mime_type`, `duration`, `file_size`, `author_name`, `title`, `date`) VALUES 
    (".'"'.$video_id.'"'.",".'"'.$message_id.'"'.",".'"'.$file_id.'"'.",".'"'.$file_unique_id.'"'.",".'"'.$mime_type.'"'.",".'"'.$duration.'"'.",".'"'.$file_size.'"'.",".'"'.$author_name.'"'.",".'"'.$title.'"'.",".'"'.date('d-m-y').'"'.")";
    // echo $sql.'<br>';
    $result = $conn->query($sql);

    $data = array("id"=>$video_id,            
                "url"=>'https://t.me/'.Channel.'/'.$message_id, 
                "file_id"=>$file_id,
                "file_unique_id"=>$file_unique_id, 
                "mime_type"=>$mime_type, 
                "duration"=>$duration, 
                "file_size"=>$file_size,);
    $meta = array("title"=>$title,
                  "owner"=>$author_name);
    returner(True, $data, $meta, Null, 200,  null);
    return $result;
}

function curl_get_contents($url)
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}


function send_audio($video_id, $audo, $title, $author_name, $thumbnail_path){
  $data = [
    'chat_id' => '@' . Channel,
    'audio' => $audo,
    'title' => $title,
    'performer' => $author_name,
    'thumb' => new CURLFile($thumbnail_path),
    'caption' => '@General_Music_Bot', 
    'parse_mode' => 'html'];

  $url = "https://api.telegram.org/bot" . API_KEY . "/sendAudio";

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
  $res = curl_exec($ch);

  if(curl_error($ch)){
      returner(message: "cURL xatosi: " . curl_error($ch), error: TRUE);
      return false;
  }else {
      $response = json_decode($res);
      if($response->ok) {
          $message_id = $response->result->message_id;
          $file_id = $response->result->audio->file_id;
          $file_size = $response->result->audio->file_size;
          $duration = $response->result->audio->duration;
          $file_unique_id = $response->result->audio->file_unique_id;
          $mime_type = $response->result->audio->mime_type;
          $set = set_data(video_id: $video_id, message_id: $message_id, file_id: $file_id, file_unique_id:  $file_unique_id, mime_type: $mime_type, file_size: $file_size, author_name: $author_name, title: $title, duration: $duration);
          if($set){
              return true;
          }
      } else{
          return false;
          returner(message: $response->description, error: true);
      }
  }
  curl_close($ch);
}