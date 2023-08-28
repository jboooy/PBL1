<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8"/>
    <?php
$team_name = $_POST['team_name'];
$team_pass = $_POST['team_pass'];
$admin_mail = $_POST['admin_mail'];
$admin_pass = $_POST['admin_pass'];
try {
    $db = new SQLite3("./database/cc_team.db");
} catch (PDOException $e) {
    $msg = $e->getMessage();
}

//フォームに入力されたチーム名がすでに登録されていないかチェック
$sql = "SELECT * FROM team WHERE team_name = :team_name";
$stmt = $dbh->prepare($sql);
$stmt->bindValue(':team_name', $team_name);
$result = $stmt->execute();


if ($rows = $result->fetchArray()){
if ($rows['team_name'] == $team_name) {
    $msg = '同じチーム名が存在します。';
    $link = '<a href="XXX.php">戻る</a>';
} else {
    //登録されていなければinsert 
    $sql = "INSERT INTO team(team_name, team_pass, admin_mail, admin_pass) VALUES (:team_name, :team_pass, :admin_mail, :admin_pass)";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':team_name', $team_name);
    $stmt->bindValue(':team_pass', $team_pass);
    $stmt->bindValue(':admin_mail', $admin_mail);
    $stmt->bindValue(':admin_pass', $admin_pass);
    $stmt->execute();
    $msg = 'チームの作成が完了しました。メールを送信します。';
    $link = '<a href="XXX.php">ログインページ</a>';

    //メールの送信
    $send_from = "iyosappori.dev@gmail.com";
    $send_to = $admin_mail;
    $subject = "Club Connectよりチーム登録完了のお知らせ";
    $header = "From: $send_from\n"; 
    $header .= "Cc: $send_from\n";
    $sendmail_param = "-f$send_from";
    $message = "チーム登録が完了しました。\n以下の内容で登録しました。\n\n";

    mb_language( 'Japanese' );
    mb_internal_encoding( 'UTF-8' );
    mb_send_mail( $send_to, $subject, $message, $header, $sendmail_param );
}
}else{
  $msg = "エラー";
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