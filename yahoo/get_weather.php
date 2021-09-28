<!-- 住所 -> 緯度、軽度 https://developer.yahoo.co.jp/webapi/map/openlocalplatform/v1/geocoder.html -->
<?php
class get_weather{

      $api_yahoo = "dj00aiZpPU9CYlNuZmNxaldldyZzPWNvbnN1bWVyc2VjcmV0Jng9OTY-";
      $user_town = "大阪府八尾市";

  function getloc(){
    $url_1 = file_get_contents('https://map.yahooapis.jp/geocode/V1/geoCoder?appid='. $api_yahoo. '&output=json&query='. $user_town);
     $response_1 = json_decode($url_1, true);
    $keido_ido = $response_1['Feature'][0]['Geometry']['Coordinates'];

    return $keido_ido

  }

  function get_weather($keido_ido){
    $msg = $city.' の 5分毎の降水確率<br>';

    // https://developer.yahoo.co.jp/webapi/map/openlocalplatform/v1/weather.html
    $url_2 = file_get_contents('https://map.yahooapis.jp/weather/V1/place?coordinates='. $keido_ido. '&appid='. $api_yahoo. '&output=json&interval=5');
    $response_2 = json_decode($url_2, true);
    $data = $response_2['Feature'][0]['Property']['WeatherList']['Weather'];
    $data_length = count($data);

    for ($i=0; $i<$data_length; $i++){
        $date = $response_2['Feature'][0]['Property']['WeatherList']['Weather'][$i]["Date"];
        $rainy = $response_2['Feature'][0]['Property']['WeatherList']['Weather'][$i]["Rainfall"];
        $hour = substr_replace(substr($date,8),":",2);
        $minute = substr($date,10);
        $msg = $msg.$hour."時".$minute."分　予測雨量: ".$rainy."mm/h <br>";
    }
    return $msg;
  }
}



 ?>


 <!--
<h3>5分ごとの天気予測</h3>

print 'user_town:'.$user_town.'<br>';


?>
京都市上京区　緯度　35  　経度 135
-->
