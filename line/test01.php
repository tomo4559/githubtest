<?php
$accessToken = 'fNPGknPY8lJOKpY70fYgivKu/s8RAy1kvQKP8ZQuBquVvQpQu7kp1qxBgz9OkbOkOZ84/6YxlMwCR+BcG0C4aztwwsQihcXpUgLym15ik8FUS7+ah3CIjL/htIoNat040Q9VK+4Lx/hl8R1l9SqBWAdB04t89/1O/w1cDnyilFU=';
$jsonString = file_get_contents('php://input'); error_log($jsonString);
$jsonObj = json_decode($jsonString);
$message = $jsonObj->{"events"}[0]->{"message"};
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};


// 送られてきたメッセージの中身からレスポンスのタイプを選択
if ($message->{"text"} == 'テスト') {
  // 確認ダイアログタイプ
  $messageData = [
    'type' => 'template',
    'altText' => '確認ダイアログ',
    'template' => [ 'type' => 'confirm', 'text' => '元気ですかー？',
    'actions' => [
      [ 'type' => 'message', 'label' => 'replyToken', 'text' => $replyToken ],
      [ 'type' => 'message', 'label' => 'jsonObj', 'text' => 'jsonObj' ],
    ]
  ]
];
} elseif ($message->{"text"} == 'ボタン') {
  // ボタンタイプ
  $messageData = [
    'type' => 'template',
    'altText' => 'ボタン',
    'template' => [
      'type' => 'buttons',
      'title' => 'タイトルです',
      'text' => '選択してね',
      'actions' => [
        [
          'type' => 'postback',
          'label' => 'webhookにpost送信',
          'data' => 'value'
        ],
        [
          'type' => 'uri',
          'label' => 'googleへ移動',
          'uri' => 'https://google.com'
        ]
      ]
    ]
  ];
} elseif ($message->{"text"} == '天気') {

  $weather = new get_weather();
  $txtmsg =  $weather->get_weather();

  $messageData = [ 'type' => 'text', 'text' => $txtmsg];
}
else {
  // それ以外は送られてきたテキストをオウム返し
  $messageData = [ 'type' => 'text', 'text' => $message->{"text"} ];
};
$response = [ 'replyToken' => $replyToken, 'messages' => [$messageData] ];
error_log(json_encode($response));
$ch = curl_init('https://api.line.me/v2/bot/message/reply');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json; charser=UTF-8', 'Authorization: Bearer ' . $accessToken ));
$result = curl_exec($ch); error_log($result);
curl_close($ch);


class get_weather{
  function get_weather(){
    $api_yahoo = "dj00aiZpPU9CYlNuZmNxaldldyZzPWNvbnN1bWVyc2VjcmV0Jng9OTY-";
    $user_town = "大阪府八尾市";
    $url_1 = file_get_contents('https://map.yahooapis.jp/geocode/V1/geoCoder?appid='. $api_yahoo. '&output=json&query='. $user_town);
    $response_1 = json_decode($url_1, true);
    $keido_ido = $response_1['Feature'][0]['Geometry']['Coordinates'];
    $city = $response_1['Feature'][0]['Name'];

    $msg = $city."\n5分毎の降水確率\n";

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
      $msg = $msg.$hour."時".$minute."分 : ".$rainy."mm/h\n";
    }
    return $msg;
  }
}



?>
