<?php

require_once(__DIR__ . '/../vendor/autoload.php');

echo "webhook_urlを入力してください:";
$webhook_url = trim(fgets(STDIN));

echo "配信対象を選択してください\n1:チャンネル全て\n2:アクティブユーザー全て:";
$sn = new osyou84\SlackNotification\SlackNotification;
while (1) {
    $num = trim(fgets(STDIN));
    if ($num === '1') {
        $sn->targetChannelAll();
        break;
    } else if ($num === '2') {
        $sn->targetActive();
        break;
    } else {
        echo "1 or 2 \n";
    }
}

echo "messageを入力してください:";
$message = trim(fgets(STDIN));

$res = $sn->send($message, $webhook_url);
if ($res === 'ok') {
    echo "配信しました\n";
} else {
    echo "配信失敗しました\n";
}

exit();