<?php
class DatabaseManager
{

    private $login_db; // ログイン用データベース
    private $current_team_db; // 現在開かれているチームのデータベース


    //データベースの起動
    public function OpenDB()
    {

        // login_dbの初期設定
        if ($this->login_db == null) {
            try {
                $this->login_db = new SQLite3("./database/login.db");
            } catch (Exception $e) {
                $errormessage = $e->getMessage();
                echo "$errormessage";
                echo "Login Databaseを開けませんでした";
                return false;
            }
        }

        // テーブルがあるか確認
        $result = $this->login_db->query("SELECT COUNT(*) FROM sqlite_master WHERE TYPE='table' AND name='login_table'");
        $cols = $result->fetchArray();

        // テーブルが存在しないとき
        if ($cols[0] == "0") {
            // テーブルの作成
            $this->login_db->query("CREATE TABLE login_table(
            team_name TEXT NOT NULL PRIMARY KEY,
            team_pass TEXT NOT NULL,
            admin_mail TEXT NOT NULL,
            admin_pass TEXT NOT NULL
            )");
        }

        return true;
    }

    // teamデータベースを開く
    public function OpenTeamDB()
    {
        session_start();

        if (isset($_SESSION['teamName'])) {
            $team_db_name = $_SESSION['teamName'];

            try {
                $this->current_team_db = new SQLite3("./database/$team_db_name.db");
            } catch (Exception $e) {
                $errormessage = $e->getMessage();
                echo "$errormessage";
                echo "Team Databaseを開けませんでした";
                return false;
            }

            // member_info,event_req,event_infoのテーブルがあるか確認し、なければ生成する
            // テーブルがあるか確認
            $result = $this->current_team_db->query("SELECT COUNT(*) FROM sqlite_master WHERE TYPE='table' AND name='member_info'");
            $cols = $result->fetchArray();

            // member_infoテーブルが存在しないとき
            if ($cols[0] == "0") {
                // テーブルの作成
                $this->current_team_db->query("CREATE TABLE member_info(
                member_id INTEGER PRIMARY KEY,
                member_name TEXT NOT NULL,
                member_num TEXT NOT NULL,
                member_age INTEGER NOT NULL,
                member_gen TEXT NOT NULL,
                member_mail TEXT,
                member_course TEXT,
                part TEXT,
                home TEXT,
                return_home TEXT,
                created_date TIMESTAMP NOT NULL DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')),
                update_date TIMESTAMP NOT NULL DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')),
                delete_flag INTEGER DEFAULT 0
            )");
            }


            // event_infoテーブルがあるか確認
            $result = $this->current_team_db->query("SELECT COUNT(*) FROM sqlite_master WHERE TYPE='table' AND name='event_info'");
            $cols = $result->fetchArray();

            // テーブルが存在しないとき
            if ($cols[0] == "0") {
                // テーブルの作成
                $this->current_team_db->query("CREATE TABLE event_req(
                event_id INTEGER PRIMARY KEY,
                event_name TEXT NOT NULL,
                event_date TEXT NOT NULL,
                created_date TIMESTAMP NOT NULL DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')),
                update_date TIMESTAMP NOT NULL DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')),
                delete_flag INTEGER DEFAULT 0
            )");
            }


            // event_reqテーブルがあるか確認
            $result = $this->current_team_db->query("SELECT COUNT(*) FROM sqlite_master WHERE TYPE='table' AND name='event_req'");
            $cols = $result->fetchArray();

            // テーブルが存在しないとき
            if ($cols[0] == "0") {
                // テーブルの作成
                $this->current_team_db->query("CREATE TABLE event_info(
                req_id INTEGER PRIMARY KEY,
                event_id INTEGER,
                member_id INTEGER,
                note TEXT,
                created_date TEXT NOT NULL DEFAULT (datetime(CURRENT_TIMESTAMP, 'localtime')),
                delete_flag INTEGER DEFAULT 0
            )");
            }

            return true;
        }

        return false;
    }

    // 新規チーム登録
    public function SetTeamData($team_name, $team_pass, $admin_mail, $admin_pass)
    {
        if ($this->OpenDB() == false) {
            echo "データベースを開けませんでした";
            return false;
        }
        // SQL作成、設定
        $prepare = $this->login_db->prepare("INSERT INTO login_table(team_name, team_pass, admin_mail, admin_pass)
                                                    VALUES(:team_name, :team_pass, :admin_mail, :admin_pass)");
        $prepare->bindValue(':team_name', $team_name, SQLITE3_TEXT);
        $prepare->bindValue(':team_pass', $team_pass, SQLITE3_TEXT);
        $prepare->bindValue(':admin_mail', $admin_mail, SQLITE3_TEXT);
        $prepare->bindValue(':admin_pass', $admin_pass, SQLITE3_TEXT);
        // SQL登録
        $result = $prepare->execute();

        if ($result == false) {
            echo "データ登録失敗";
            return false;
        }
        return true;
    }

    // ログイン判定
    public function CheckTeamLogin($team_name, $team_pass){
        if ($this->OpenDB() == false) {
            echo "データベースを開けませんでした";
            return false;
        }
        // ログイン判定
        $result = $this->login_db->query("SELECT EXISTS(SELECT *
        FROM login_table
        WHERE team_name = '$team_name'
        AND team_pass = '$team_pass');");
        // 一致するデータがあればtrueを返す
        if($result->fetchArray()[0] == "1"){
            return true;
        }
        return false;
    }

    // Adminログイン判定
    public function CheckAdminLogin($admin_mail, $admin_pass){
        if ($this->OpenDB() == false) {
            echo "データベースを開けませんでした";
            return false;
        }
        // ログイン判定
        session_start();

        //$teamname = $_SESSION['team_name'];

        $teamname = "test3";
        $result = $this->login_db->query("SELECT EXISTS(SELECT *
        FROM login_table
        WHERE team_name = '$teamname'
        AND admin_mail = '$admin_mail'
        AND admin_pass = '$admin_pass');");
        // 一致するデータがあればtrueを返す
        if($result->fetchArray()[0] == "1"){
            return true;
        }
        return false;
    }



    // イベント一覧取得 (二次元配列で返す)
    public function GetAllEventInfo()
    {
        if ($this->OpenTeamDB() == false) {
            echo "データベースを開けませんでした";
            return false;
        }

        $event_array = array();

        $result = $this->current_team_db->query("SELECT * FROM event_info 
        ORDER BY event_date ASC;");

        while ($cols = $result->fetchArray()) {
            array_push($event_array, $cols);
        }

        return $event_array;
    }


    // 部員情報一覧取得
    public function GetAllMemberInfo()
    {
        if ($this->OpenTeamDB() == false) {
            echo "データベースを開けませんでした";
            return false;
        }

        $member_array = array();

        $result = $this->current_team_db->query("SELECT * FROM member_info;");

        while ($cols = $result->fetchArray()) {
            array_push($member_array, $cols);
        }

        return $member_array;
    }



    // イベント作成


    // 部員情報登録


    // 参加申請


    // データ取得
    public function GetAllUserName()
    {
        if ($this->OpenDB() == false) {
            echo "データベースを開けませんでした";
            return false;
        }

        $name_array = array();

        $result = $this->db->query("SELECT * FROM user_table;");

        while ($cols = $result->fetchArray()) {
            array_push($name_array, $cols[0]);
        }

        return $name_array;
    }
}
