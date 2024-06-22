<?php

require_once 'config/dp.php';

include 'telegram.php';


$telegram = new Telegram(bot_token:'7229559191:AAHL6YExf63e3Q63iSxvEPuOlkqQGJfQsJw');


$result = $telegram->getData();
$chat_id = $telegram->ChatID();
$text = $telegram -> text();




// $content = array('chat_id' => $chat_id, 'text' => $text);
// $telegram->sendMessage($content);

$myCommends = false;


if($text == '/start'){
    $myCommends = true;
    $option = array( 
        //First row
        array($telegram->buildInlineKeyBoardButton("بزن بریم" , url: '' , callback_data:'/home') ), 
        //Second row 
       );
    $keyb = $telegram->buildInlineKeyBoard($option, $onetime=false);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "به ربات ترجمه خوش آمدی ");
    $telegram->sendmessage($content);
    }


if($text == "/home"){
    $myCommends = true;
    $option = array( 
        //First row
    
        //Second row 
        array($telegram->buildInlineKeyBoardButton("گوگل 🇺🇸", url:'' , callback_data:'/google' ), $telegram->buildInlineKeyBoardButton("مایکروسافت 🇺🇸" , url:'' , callback_data:'/microsoft')),


        //Third row
        );
    $keyb = $telegram->buildInlineKeyBoard($option, $onetime=false);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "موتور جست و جوی خود را انتخاب کنید" ,'message_id' => $result['callback_query']['message']['message_id']);
    $telegram->editMessageText($content);
    }



    if($text == "/google"){
        $myCommends = true;

        $query = "INSERT INTO translate_requast SET chat_id=?, action=? ,updated_at=?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $chat_id);
        $stmt->bindValue(2, 'google');
        $stmt->bindValue(3, time());
        $stmt->execute();


        $option = array( 
            //First row
            array($telegram->buildInlineKeyBoardButton("ترجمه  به اینگلیسی" , url:'' , callback_data:'/en') , $telegram->buildInlineKeyBoardButton("ترجمه به فارسی" , url:'' , callback_data:'/fa') ),
          
            );
        $keyb = $telegram->buildInlineKeyBoard($option, $onetime=false);
    
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb ,'text' => 'حالت ترجمه خود را انتخاب کنید ', 'message_id' => $result['callback_query']['message']['message_id']);
    $telegram->editMessageText($content);
    }


if($text == "/microsoft"){
    $myCommends = true;

    $query = "INSERT INTO translate_requast SET chat_id=?, action=? ,updated_at=?";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $chat_id);
    $stmt->bindValue(2, 'microsoft');
    $stmt->bindValue(3, time());
    $stmt->execute();


    $option = array(
        //First row
        array($telegram->buildInlineKeyBoardButton("ترجمه  به اینگلیسی" , url:'' , callback_data:'/en') , $telegram->buildInlineKeyBoardButton("ترجمه به فارسی" , url:'' , callback_data:'/fa') ),

    );
    $keyb = $telegram->buildInlineKeyBoard($option, $onetime=false);

    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb ,'text' => 'حالت ترجمه خود را انتخاب کنید ', 'message_id' => $result['callback_query']['message']['message_id']);
    $telegram->editMessageText($content);
}

    if($text == "/fa"){
        $myCommends = true;

        $query = "UPDATE translate_requast SET lang=?, updated_at=? WHERE chat_id=? ORDER BY updated_at DESC LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, "fa");
        $stmt->bindValue(2, time());
        $stmt->bindValue(3, $chat_id);
        $stmt->execute();


    
        $content = array('chat_id' => $chat_id, 'reply_markup' => [] ,'text' => 'حالا متنی که می خواهید ترجمه بشه رو وارد کنید تا برات ترجمه کند', 'message_id' => $result['callback_query']['message']['message_id']);
    $telegram->editMessageText($content);
    }

if($text == "/en"){
    $myCommends = true;

    $query = "UPDATE translate_requast SET lang=?, updated_at=? WHERE chat_id=? ORDER BY updated_at DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, "en");
    $stmt->bindValue(2, time());
    $stmt->bindValue(3, $chat_id);
    $stmt->execute();



    $content = array('chat_id' => $chat_id, 'reply_markup' => [] ,'text' => 'حالا متنی که می خواهید ترجمه بشه رو وارد کنید تا برات ترجمه کند', 'message_id' => $result['callback_query']['message']['message_id']);
    $telegram->editMessageText($content);
}

if(!$myCommends){

    $query = "SELECT * FROM translate_requast WHERE chat_id=? ORDER BY updated_at DESC LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(1, $chat_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    if($stmt->rowCount() && isset($result->lang)){

        $query = "UPDATE translate_requast SET q=?, updated_at=? WHERE chat_id=? ORDER BY updated_at DESC LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $text);
        $stmt->bindValue(2, time());
        $stmt->bindValue(3, $chat_id);
        $stmt->execute();

       $translate_Token =  translateAPI("887361:666edcea72770", $result->action, $result->lang , $text );


        $content = array('chat_id' => $chat_id, 'text' => $translate_Token);
        $telegram->sendMessage($content);


        $option = array(
            //First row

            //Second row
            array($telegram->buildInlineKeyBoardButton("گوگل 🇺🇸", url:'' , callback_data:'/google' ), $telegram->buildInlineKeyBoardButton("مایکروسافت 🇺🇸" , url:'' , callback_data:'/microsoft')),

          
            //Third row
        );
        $keyb = $telegram->buildInlineKeyBoard($option, $onetime=false);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "موتور جست و جوی خود را انتخاب کنید");
        $telegram->sendMessage($content);


    }else{
        $content = array('chat_id' => $chat_id, 'text' => 'دستور را اشتبا وارد کردید' ,);
        $telegram->sendMessage($content);
    }


}


 function translateAPI($token , $action , $lang , $query){
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://one-api.ir/translate/?token=$token&action=$action&lang=$lang&q=". urlencode($query),
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
    
        if($result->status == 200 )  return $result->result;
        else var_dump($result); die();  
    
    }




// if($text == "/me" || $text == "اطلاعات شخصی"){
//     $myCommends = true;
//     $option = array( 
//     array($telegram->buildInlineKeyBoardButton("بازگشت" , url:'' , callback_data:'/home') ), 
//     );
//     $MyResult = $telegram->getme()['result']['first_name'];
//     $content = array('chat_id' => $chat_id, 'text' => $MyResult , 'message_id' => $result['callback_query']['message']['message_id']);
// $telegram->editMessageText($content);
// }






