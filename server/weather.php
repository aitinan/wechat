<?php

       
//$url = "http://op.juhe.cn/onebox/weather/query?cityname=%E6%AD%A6%E6%B1%89&key=87ffc29722810c9dcaa06d6f5a8a7700";
//     $output = httpRequest($url);
//     $weather = json_decode($output, true); 
//     $info = $weather['reason'];

//echo  $info;

function httpRequest($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    if ($output === FALSE){
        return "cURL Error: ". curl_error($ch);
    }
    return $output;
}

?>
 
