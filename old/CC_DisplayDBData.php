<!-- 表示するデータをDBから取ってくる -->
<!-- PHPの部分だけ後で移植 -->
<html>
<head><title>PHP TEST</title></head>
<body>
    <?php
    // チーム名の取得はまた考える
    $db = new SQLite3('./database/CC_test2.sqlite3');
    // ---- ここはデバッグ用 ----
    if($db == null){
        echo "接続に失敗しました。<br>";
    }else{
        echo "接続に成功しました。<br>";
    }
    // ---- ここはデバッグ用 ----

    // event_info table に接続
    $stmt = $db->prepare("SELECT * FROM event_info");

    // SQL実行
    $res = $stmt->execute();

    $data;

    while ($data = $res->fetchArray()) {
        // var_dump($data);
        if( $data[delete_flag] == 0 ){
            echo $data[event_name], " - ", $data[event_date];
        }
    }

    // データベースの接続解除
    $db->close();
    ?>
</body>
</html>
