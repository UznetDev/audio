<?php
$url = $_GET["url"];

const API_KEY = '1442572143:AAGGFSRhpNWzDaheTr2k3IBkc2pu9weErJo';

const Channel = 'YouTube_Downs';

$path = parse_url($url, PHP_URL_PATH);
$track_id = substr($path, 7);
$folder = array('media','media/audio','media/img');
$AD = '@theFmMusic';
$date_time = date("Y-m-d-H-i-s");

foreach ($folder as $dir) {
    if (!is_dir( $dir ) ) {
        mkdir( $dir );       
    }
}

$ds = array("Developer"=>"@UZNet_Dev",
            "Service"=>"@ApisTelegramBot",
            "description"=>"Youtube downloader", 
            "download"=>true,
            "limit"=>Null,
            "done"=>NULL);
            