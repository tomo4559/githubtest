<!-- 住所 -> 緯度、軽度 https://developer.yahoo.co.jp/webapi/map/openlocalplatform/v1/geocoder.html -->

<h3>5分ごとの天気予測</h3>
<?php
$api_yahoo = "dj00aiZpPU9CYlNuZmNxaldldyZzPWNvbnN1bWVyc2VjcmV0Jng9OTY-";
$user_town = $_POST["input_data"];

$url_1 = file_get_contents('https://map.yahooapis.jp/geocode/V1/geoCoder?appid='. $api_yahoo. '&output=json&query='. $user_town);
$response_1 = json_decode($url_1, true);
$keido_ido = $response_1['Feature'][0]['Geometry']['Coordinates'];

$city = $response_1['Feature'][0]['Name'];
print '<h2> '.$city.' の 5分毎の降水確率</h2>';

// https://developer.yahoo.co.jp/webapi/map/openlocalplatform/v1/weather.html
$url_2 = file_get_contents('https://map.yahooapis.jp/weather/V1/place?coordinates='. $keido_ido. '&appid='. $api_yahoo. '&output=json&interval=5');
$response_2 = json_decode($url_2, true);
$data = $response_2['Feature'][0]['Property']['WeatherList']['Weather'];
$data_length = count($data);
print "<ul>";

for ($i=0; $i<$data_length; $i++){
    //print $i;
    $date = $response_2['Feature'][0]['Property']['WeatherList']['Weather'][$i]["Date"];
    $rainy = $response_2['Feature'][0]['Property']['WeatherList']['Weather'][$i]["Rainfall"];
    print "<li>";
    //print "日時: ".substr_replace($date,":",2);   結果 0255 、分かりにくい
    $hour = substr_replace(substr($date,8),":",2);
    $minute = substr($date,10);
    print $hour."時".$minute."分　";
    print "予測雨量: ".$rainy."mm/h <br>";
    print "</li>";
}
print "</ul>";
?>
<!--
京都市上京区　緯度　35  　経度 135
-->
