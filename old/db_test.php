<?php
require 'DatabaseManager.php';
$db = new DatabaseManager();

if(!isset($_SESSION)){ session_start(); }
$_SESSION['teamName'] = "test3";

$db->SetTeamData("test3","airu3","fasdfasf@mail3","adpass3");

if($db->CheckTeamLogin("test3","airu3")){
    echo "true";
}else{
    echo "false";
}

if($db->CheckAdminLogin("fasdfasf@mail3","adpass3")){
    echo "Login Success";
}else{
    echo "Login Fail";
}

if($db -> CheckTeamLogin("test3", "airu3")){
    echo "team login sccess";
}else{
    echo "team login fail";
}



if($result = $db -> SetMemberData("test","1111","course","budsfa","afj;sf","fasdfa","fasdfa","fasdf","fkasd;")){
    echo "メンバー追加成功";
}

if($result = $db->GetAllMemberInfo()){
    print_r($result);
}

if($result = $db -> CreateEvent("2021-11-1", "test")){
    echo "イベント登録完了";
}


if($result = $db->GetAllEventInfo()){
    print_r($result);
}

if($result = $db -> CreateEventRequest(1,"1111","ハローみなさま")){
    echo "イベントリクエストdone";
}

if($result = $db -> GetAllEventReqInfo(1)){
    print_r($result);
}

if($result = $db -> LotteryMember(1)){
    print_r($result);
}
