#!/usr/bin/php
<?php 
/// データベース接続
$db = new SQLite3('./database/CC_test.sqlite3');
// デバッグ用
if($db == null){
    echo "接続に失敗しました。<br>";
    exit(0);
}else{
    echo "接続に成功しました";
}

$event_name = $db->querySingle("SELECT event_name FROM event_info");
$event_date = $db->querySingle("SELECT event_date FROM event_info");

$from = "Adminのメールアドレス";  
$header = "From: $from"; 
$sendmail_param = "-f$from"; //エラーメールの戻り先 
$subject = "イベント追加のお知らせ"; 
$body = "イベント追加のお知らせです。\nイベント名:$event_name\n開催日時:$event_date\n"; 

$result = $db->query('SELECT * FROM member_info');
while ($cols = $result->fetchArray()){
    $email = $cols[11];
    if( mb_send_mail($email, $subject, $body, $header, $sendmail_param) ){
      //メール送信に成功しました。
    }else{ 
      echo "メール送信に失敗しました。";
      return false;
    }
}

// データベースの接続解除
$db->close();
return true;
?>