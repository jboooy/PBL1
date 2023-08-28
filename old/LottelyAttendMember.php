#!/usr/bin/php
<?php 
/// データベース接続
/// データベース接続
$db = new SQLite3('./database/CC_test.sqlite3');
// デバッグ用
if($db == null){
    echo "接続に失敗しました。<br>";
    exit(0);
}else{
    echo "接続に成功しました";
}

$sql = "UPDATE member_req SET delete = 1";
$db->exec( $sql );

$sql = "SELECT * FROM member_req ORDER BY RANDOM() LIMIT 25";
$result = $db->query($sql);
while ($cols = $result->fetchArray()) { 
	$sql = "UPDATE member_req SET delete = 0 WHERE req_id = '$cols[0]'";
	$db->exec( $sql );
}

// データベースの接続解除
$db->close();
return true;
?>