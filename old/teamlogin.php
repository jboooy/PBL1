<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8"/>
    <?php
$team_name = $_POST['team_name'];
try {
    $db = new SQLite3("./database/cc_team.db");
} catch (PDOException $e) {
    $msg = $e->getMessage();
}

$sql = "SELECT * FROM team WHERE team_name=:team_name";
$stmt = $db->prepare($sql);
$stmt->bindValue(':team_name', $team_name);
$result = $stmt->execute();

if($rows = $result->fetchArray()) {
    if($rows["team_pass"] ==  $_POST['team_pass']) {
        $msg = 'ログインしました。';
        $link = '<a href="XXX.html">ホーム</a>';
    }else {
        $msg = 'チーム名もしくはパスワードが間違っています。';
        $link = '<a href="XXX.php">戻る</a>';
    }
}else {
    $msg = 'ログインできませんでした。';
}

?>
  </head>
  <body>
    <?php
    echo $msg;
    echo $link;
    ?>
  </body>
</html>