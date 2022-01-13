<?php
    define('API_KEY','TELEGRAM_TOKEN');


    function bot($method,$datas=[]){
        $url = "https://api.telegram.org/bot".API_KEY."/".$method;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
        $res = curl_exec($ch);
        if(curl_error($ch)){
            var_dump(curl_error($ch));
        }else{
            return json_decode($res);
        }
    }

    $update = json_decode(file_get_contents('php://input'));
    $message = $update->message;
    $cid = $message->chat->id;
    $name = $message->chat->first_name;
    $tx = $message->text;
    date_default_timezone_set('Asia/Tashkent');
    $hozir = date(" G:i ");    



$city_name = $tx;
$pogoda_api = 'OpenWeather_TOKEN';
$pogoda_url = 'http://api.openweathermap.org/data/2.5/weather?q='.$city_name.'&appid='.$pogoda_api;
$pogoda_data = json_decode(file_get_contents($pogoda_url), true);

$temp = round($pogoda_data['main']['temp'] - 273.15)." Â°C";
$havo = $pogoda_data['weather'][0]['main'];
$nam = $pogoda_data['main']['humidity']." %";
$wind = round($pogoda_data['wind']['speed']);

 //Checking
if ($temp == "-273 Â°C") {
    $temp = 'topilmadi';
}
if ($nam == " %") {
    $nam = "topilmadi";
}
// Start
if($tx == "/start"){
    bot('sendMessage',[
        'chat_id' => $cid,
        'text' => "*Assalomu alaykum, $name!*\n\nQaysi hududni ob-havo malumotlarini ko'ramiz?",
        'parse_mode' => 'markdown'
    ]);
}elseif ($tx == $city_name) 
    {
        if ($havo == "Clouds") 
            {
                $havo = "Bulutli Havo";
                $havo_photo = '[.](https://telegra.ph/file/b3a00d7c379f2d33e82fb.jpg)';
            }
        elseif ($havo == "Clear") 
            {
                $havo = "Ochiq";
                $havo_photo = '[.](https://telegra.ph/file/a5025719a2045e3fcc388.jpg)';
            }
        elseif ($havo == "Rain") 
            {
                $havo = "Yomg'ir";
                $havo_photo = '[.](https://telegra.ph/file/168e5d9642a38e4673676.jpg)';
            }
        elseif ($havo == "Snow") 
            {
                $havo = "Qor";
                $havo_photo = '[.](https://telegra.ph/file/be179000233b77b6163cc.jpg)';
            }
        elseif (($havo == "Haze") || ($havo == "Mist") || ($havo == "Smoke") || ($havo == "Fog") || ($havo == "Cape") || ($havo == "Brume")) 
            {
                $havo = "Tuman";
                $havo_photo = '[.](https://telegra.ph/file/f9d606f414f910c1b097f.jpg)';
            }
        elseif($havo == "")
        {
            $havo = "topilmadi";
            $havo_photo = '[.](https://telegra.ph/file/38b47b11724a1ed6b7b6f.png)';
        }

        if ($wind == " m/s tezlikka yetishi mumkin") {
            $wind = "Yo'q";
        }else {
            $wind = $wind." m/s tezlikka yetishi mumkin";
        }
            bot('sendMessage', [
                'chat_id' => $cid,
                'text' => "*$city_name - $hozir*\n
*Harorat:  *$temp
*Havo - *$havo
*Namlik - *$nam
*Shamol - *$wind  $havo_photo",
                    'parse_mode' => 'markdown',
                ]);
    }
?>
