<?php
require_once 'config/dp.php';

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://one-api.ir/translate/?token=887361%3A666edcea72770&action=google&lang=en&q=%DA%86%D8%B7%D9%88%D8%B1%DB%8C%20%D8%A2%D8%B1%D9%85%D8%A7%D9%86',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
$result= (json_decode($response));


