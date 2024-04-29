<?php

if(!file_exists($thumbnail_path)){
    $file = file_get_contents($thumbnail_url);
    $put = file_put_contents($thumbnail_path, $file);
    try{
        list($rasmEni, $rasmBoyi) = getimagesize($thumbnail_path);

        $img = imagecreatefromjpeg($thumbnail_path); // Ehtiyotkorona: Bu misol uchun JPEG formatini qo'llash kutilmoqda
        
        // Yozuv rangi
        $yozuvRangi = imagecolorallocate($img, 255, 255, 255); // Oq rang
        
        $yozuvUzunligi = strlen($AD) * 8; // Yozuv uzunligini belgilash (harf vaqtinchalik 8 piksel)
        
        $yozuvX = ($rasmEni - $yozuvUzunligi) / 1.05; // Yozuv boshlang'ich x koordinatasi
        $yozuvY = $rasmBoyi - 20; // Yozuv boshlang'ich y koordinatasi (pastga 30 piksel)
        
        imagestring($img, 4, $yozuvX, $yozuvY, $AD, $yozuvRangi); // Yozuvni rasmga qo'yish
    
        imagejpeg($img, $thumbnail_path, 100); // Fayl nomi va joylashuvi o'zingizni belgilang
        
        imagedestroy($img);
    }
    catch(Exception $e){
        error_log($e);
    } 
}