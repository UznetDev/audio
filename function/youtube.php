<?php

function getVideoId($url){
    $parts = parse_url($url);
    if(isset($parts['query'])){
        parse_str($parts['query'], $qs);
        if(isset($qs['v'])){
            return $qs['v'];
        }else if(isset($qs['vi'])){
            return $qs['vi'];
        }
    }
    if(isset($parts['path'])){
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path)-1];
    }
    return false;
}



function checkVideo($video_id){
    // $url = 'https://www.youtube.com/oembed?url=https://youtu.be/5s3F1GkfZIU?si=epDoZ4Jtr8YXxqj5&format=json';
    // $url = 'https://www.youtube.com/oembed?url='.$video_url.'&format=json';
    // $handle = curl_init($url);
    // curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
    // $response = curl_exec($handle);
    // $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    // curl_close($handle);
    // if($httpCode == 200){
    //     return json_decode($response); // json_encode ishlatmaymiz, chunki $response JSON formatida allaqachon
    // }
    // else{
    //     return FALSE;
    // }
    $key = 'AIzaSyAWAaurvOS2ye8TfI1pis1ZBXyQkEUl5mo';
    $html = 'https://www.googleapis.com/youtube/v3/videos?id='.$video_id."&key=$key&part=snippet";
    $response = file_get_contents($html);
    $decoded = json_decode($response, true);
    foreach ($decoded['items'] as $items) {
        return array('channelTitle'=> $items['snippet']['channelTitle'],
                     'title'=> $items['snippet']['title'],
                     'thumbnails'=>end($items['snippet']['thumbnails'])['url']);
    }
    return false;
}
  
  
function get_mp3_url_yt($url){
    $post= array(
        'q' => $url,
        'vt' => 'mp3'
    );
    $ch = curl_init('https://yt5s.io/api/ajaxSearch');
    $header[] = "X-Requested-With': 'XMLHttpRequest";
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
    $response = curl_exec($ch);
    curl_close($ch);
  
    return json_decode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  }
  
  
  function get_all_mp3_urls($data) {
    $base_url = 'https://ve44.aadika.xyz/download/';
    $video_id = $data['vid'];
    $time_expires = $data['timeExpires'];
    $token = $data['token'];
    $mp3_info_dict = array();
  
    $index = 1;
    foreach ($data['links']['mp3'] as $quality => $info) {
        if ($info['f'] == 'mp3') {
            $mp3_info = array(
                'title' => $data['title'],
                'format' => $info['k'],
                'q' => $info['q'],
                'size' => $info['size'],
                'key' => $info['key'],
                'url' => "{$base_url}{$video_id}/mp3/{$info['k']}/{$time_expires}/{$token}/{$index}?f=yt5s.io"
            );
            $mp3_info_dict[$index] = $mp3_info;
            $index++;
        }
    }
    return $mp3_info_dict;
  }

function youtube_audio_url($url) {
    try {
        $result = get_mp3_url_yt($url);
        if ($result['mess'] == "") {
            $res = get_all_mp3_urls($result);
            return $res;
        } else {
            return FALSE;
        }
    } catch (Exception $e) {
        error_log(date('Y-m-d H:i:s') . " - " . $e . "\n", 3, 'error.log');
        return FALSE;
    }
}


function get_audio_url($videoId)
{
    $url = 'https://nu.mnuu.nu/api/v1/init?p=y&23=1llum1n471';

    $res = getJson($url);
    $convertedUrl = $res['convertURL'] . "&v=https://www.youtube.com/watch?v={$videoId}&f=mp3&_=0.9766432686755606";
    $res = getJson($convertedUrl);
    $res = getJson($res['redirectURL']);
    return $res['downloadURL'];
}

