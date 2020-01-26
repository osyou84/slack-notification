# Slack Notification
Slack NotificationはSlackのincoming-webhookを利用して通知を送りたいときに利用できます。  
composerでインストールしてご利用ください。

# Requirement
* php 7.2.0

# Installation
```
 $ composer require osyou84/slack-notification
```

# Usage
## 配信ターゲットを指定する
### ワークスペース全てに通知を送る
```php
$sn = new SlackNotification;
$sn->targetWorkspace()
   ->send('メッセージ', 'WEBHOOK URL');
``` 
### チャンネル全体に通知を送る
```php
$sn = new SlackNotification;
$sn->targetChannelAll()
   ->send('メッセージ', 'WEBHOOK URL');
``` 
### チャンネル内でアクティブなメンバーに通知を送る
```php
$sn = new SlackNotification;
$sn->targetActive()
   ->send('メッセージ', 'WEBHOOK URL');
``` 
### チャンネル内の指定したユーザーに
```php
$sn = new SlackNotification;
$sn->setTargets(['USER_ID1', 'USER_ID2', '...'])
   ->send('メッセージ', 'WEBHOOK URL');
``` 

# DEMO
## コマンド
```
 $ php example/example.php
```
## example.phpの実行方法
1. Slackアプリから通知を送りたいチャンネルにて、incoming-webhookアプリを追加してWebhook URLを発行する
2. git clone https://github.com/osyou84/slack-notification.git
3. トップディレクトリにて `$ php example/example.php`を実行し、Webhook URL・対象・メッセージを入力する

# Author
* 斉藤 尚也

# Lisence
Slack Notification is under [MIT license](https://en.wikipedia.org/wiki/MIT_License).