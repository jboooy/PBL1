<!-- 部員情報登録 -->
<html>
<head><title>PHP TEST</title></head>
<body>
    <?php
    // できてないこと
    // チーム名をどうやって取ってくるか
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
    $sql = 'SELECT count(*) FROM sqlite_master WHERE type="table" AND name="member_info"';

    // テーブルが存在しない場合のみテーブル作成
    if (!$db->querySingle($sql)) {
        // テーブル追加
        $sql = "CREATE TABLE member_info(
            member_id INTEGER PRIMARY KEY,
            member_name TEXT NOT NULL,
            member_num TEXT NOT NULL,
            member_age INTEGER NOT NULL,
            member_gen TEXT NOT NULL,
            member_mail TEXT,
            part TEXT,
            home TEXT,
            return_home TEXT,
            created_date TIMESTAMP NOT NULL DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')),
            update_date TIMESTAMP NOT NULL DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')),
            delete_flag INTEGER DEFAULT 0
        )";
        $res = $db->exec($sql);
    }

    // テストデータ(実際はSELECT?で持ってくる)
    // member_id : 自動
    $member_name = "testname";
    $member_num = "0520999z";
    $member_age = "20";
    $member_gen = "male";
    $member_mail = "test@mail.co.jp";
    $part = "a";
    $home = "b";
    $return_home = "yes";

    // (3)SQL作成&プリペアドステートメントの準備
    $stmt = $db->prepare("INSERT INTO member_info(
	    member_name, member_num, member_age, member_gen, member_mail, part, home, return_home
    ) VALUES (
	    :member_name, :member_num, :member_age, :member_gen, :member_mail, :part, :home, :return_home
    )");

    //
    $stmt->bindValue( ':member_name', $member_name, SQLITE3_TEXT);
    $stmt->bindValue( ':member_num', $member_num, SQLITE3_TEXT);
    $stmt->bindValue( ':member_age', $member_age, SQLITE3_INTEGER);
    $stmt->bindValue( ':member_gen', $member_gen, SQLITE3_TEXT);
    $stmt->bindValue( ':member_mail', $member_mail, SQLITE3_TEXT);
    $stmt->bindValue( ':part', $part, SQLITE3_TEXT);
    $stmt->bindValue( ':home', $home, SQLITE3_TEXT);
    $stmt->bindValue( ':return_home', $return_home, SQLITE3_TEXT);

    // データの登録を実行
    $res = $stmt->execute();

    // データベースの接続解除
    $db->close();
    echo "登録成功。";
    ?>

    <!-- 戻るボタンの作成？ -->
</body>
</html>
