<!-- イベント削除 -->
<!-- イベント詳細ページで確認dialogは出す -->
<!-- submitでここに飛ばす -->
<html>
<head><title>PHP TEST</title></head>
<body>
    <?php
    // チーム名、イベントidの取得はまた考える

    // データベース取得
    $db = new SQLite3('./database/CC_test2.sqlite3');
    // ---- ここはデバッグ用 ----
    if($db == null){
        echo "接続に失敗しました。<br>";
    }else{
        echo "接続に成功しました";
    }
    // ---- ここはデバッグ用 ----

    $event_id = 1;      // 削除するイベントのid　取得方法は考える
    $delete_flag = 1;   // 論理削除フラグを削除状態に
    $update_date = date('Y-m-d H:i:s');
    echo $update_date;

    // (3)SQL作成&プリペアドステートメントの準備
    $stmt = $db->prepare("UPDATE event_info
        SET
            delete_flag = :delete_flag,
            update_date = :update_date
        WHERE
            event_id = :event_id
    ");

    $stmt->bindValue( ':event_id', $event_id, SQLITE3_INTEGER);
    $stmt->bindValue( ':delete_flag', $delete_flag, SQLITE3_INTEGER);
    $stmt->bindValue( ':update_date', $update_date, SQLITE3_TEXT);

    // データの登録を実行
    $res = $stmt->execute();

    // 更新したデータのカラム数を取得
    var_dump($db->changes()); // int(1)

    // データベースの接続解除
    $db->close();
    echo "登録成功。";
    ?>
</body>
</html>
