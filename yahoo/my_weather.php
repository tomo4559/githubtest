<!-- 住所 -> 緯度、軽度 https://developer.yahoo.co.jp/webapi/map/openlocalplatform/v1/geocoder.html -->

<h3>5分ごとの天気予測</h3>
<?php
  include('get_weather.php');

  $weather = new get_weather();

  print  $weather->get_weather();

?>
<!--
京都市上京区　緯度　35  　経度 135
-->
