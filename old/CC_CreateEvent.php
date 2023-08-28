<!-- イベント作成 -->
<html>
<head><title>PHP TEST</title></head>
<body>
    test
    <?php
    // できてないこと
    // SELECTタグから持ってくるように変更
    // SELECT未入力だったときの判定
    // id系の自動設定

    // データベース接続
    $db = new SQLite3('./database/CC_test2.sqlite3');
    // ---- ここはデバッグ用 ----
    if($db == null){
        echo "接続に失敗しました。<br>";
    }else{
        echo "接続に成功しました。<br>";
    }
    // ---- ここはデバッグ用 ----

    // event_infoテーブルの存在確認
    $sql = 'SELECT count(*) FROM sqlite_master WHERE type="table" AND name="event_info"';

    // テーブルが存在しない場合のみテーブル作成
    if (!$db->querySingle($sql)) {
        // テーブル追加
        $sql = "CREATE TABLE event_info(
            event_id INTEGER PRIMARY KEY,
            event_name TEXT NOT NULL,
            event_date TEXT NOT NULL,
            created_date TEXT NOT NULL DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')),
            update_date TEXT NOT NULL DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')),
            reg_member_id INTEGER NOT NULL,
            delete_flag INTEGER DEFAULT 0
        )";
        $res = $db->exec($sql);
    }

    // テストデータ(実際はSELECT?で持ってくる)
    // $event_id : 主キーのため自動で格納されるはず
    $event_name = "test_event"; // SELECT
    $event_date = "2000/7/20";  // SELECT
    // $created_date = ? // : デフォルト設定済みのためスキップ可
    // $update_date = datetime(CURRENT_TIMESTAMP, 'localtime');
    $reg_member_id = "1111";     // SELECT
    // delete_flag : デフォルト設定済み

    // (3)SQL作成&プリペアドステートメントの準備
    $stmt = $db->prepare("INSERT INTO event_info(
	    event_name, event_date, reg_member_id
    ) VALUES (
	    :event_name, :event_date, :reg_member_id
    )");


    $stmt->bindValue( ':event_date', $event_date, SQLITE3_TEXT);
    $stmt->bindValue( ':event_name', $event_name, SQLITE3_TEXT);
    // $stmt->bindValue( ':update_date', $update_date, SQLITE3_TIMESTAMP);
    $stmt->bindValue( ':reg_member_id', $reg_member_id, SQLITE3_INTEGER);

    // データの登録を実行
    $res = $stmt->execute();

    // データベースの接続解除
    $db->close();
    echo "登録成功。";
    ?>
</body>
</html>
