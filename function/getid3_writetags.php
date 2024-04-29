<?php

use getID3 as getID3;
use getid3_writetags as getid3_writetags;


function write_tags($audio_path, $thumbnail_path, $title, $author_name){
    global $AD;
    $getID3 = new getID3;
    $tagwriter = new getid3_writetags;

    $TagData = [
        'title' => [$title],    // Matnlarni massiv shaklida uzatamiz
        'artist' => [$author_name.' - '.$AD],  // Matnlarni massiv shaklida uzatamiz
        'album' => [$title.' - '.$AD],
        'genre' => ['@General_Music_Bot'],   // Janr
        'comment' => ['by @ApisTelegramBot download from youtube'],
    ];
    $tagwriter->filename = $audio_path;
    $tagwriter->tagformats = ['id3v2.3']; // Albom san'atini qo'shish uchun format
    $tagwriter->overwrite_tags = true; // Avvalgi metadatalarni o'chirib yozish
    $tagwriter->tag_data = $TagData;
    
    $tagwriter->tag_data['attached_picture'][0]['data'] = file_get_contents($thumbnail_path); // Albom san'atini qo'shish
    $tagwriter->tag_data['attached_picture'][0]['picturetypeid'] = 3; // Albom san'ati ravishi (Cover Art)
    $tagwriter->tag_data['attached_picture'][0]['description'] = 'Album Art'; // Albom san'ati tavsifi
    $tagwriter->tag_data['attached_picture'][0]['mime'] = 'image/jpeg'; // Rasm formati (misol uchun JPEG)
    
    return $tagwriter->WriteTags();
}

?>