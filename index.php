<?php
$APPID = 'wx27e6d3d061ea113a';
$APPSECRET = '60970dbc83d4d8ac5cebf35268565b2c';

$host = 'localhost';
$user = 'app';
$pwd = 'Tcl#123';
$mysqli = null;

function initDataConnector() {
    if(isset($mysqli)) return;

    global $mysqli, $host, $user, $pwd;
    $mysqli = new mysqli($host, $user, $pwd);
    if (!$mysqli){
        die('Could not connect: ' . mysqli_error());
    }
    $mysqli->select_db('avatar');
}

switch ($_SERVER['REQUEST_URI']) {
    case "/api/wx/token":
        echo file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$APPSECRET");
        return;
        break;

    case (preg_match("/^\/s\/(.*)/", $_SERVER['REQUEST_URI'], $matches) ?  true : false):
        if(isset($matches[1])) {
            handleShortCut($matches[1]);
        }
        break;

    default:
        $path = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'];
        try {
            $mdfile = $path . ".md"; 
            if (file_exists($mdfile)) {
                #$content = file_get_contents($mdfile);
                $template = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/template/markdown.tpl");
                $template = str_replace("{{URL}}", $_SERVER['REQUEST_URI'] . ".md", $template);
                echo $template;
                return;
            }
        } catch(Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
        break;
}
phpinfo();
// http_response_code(404);

function getPoemIdByPhraseId($mysqli, $phraseId){
    
    while($r=mysqli_fetch_assoc($result)){
        return $r["poem_id"];
        break;
    }
}

function handleShortCut($tag) {
    global $mysqli;
    initDataConnector();
    $sql = "select * from shortlink where tag='$tag'";
    $result = $mysqli->query($sql);
    while($r = mysqli_fetch_assoc($result)){
        $url = $r['link'];
        print($url);
        header("Location: $url", true, 301);
        exit();
    }
}
?>
