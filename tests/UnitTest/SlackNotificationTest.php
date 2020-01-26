<?php

use PHPUnit\Framework\TestCase;
use osyou84\SlackNotification\SlackNotification;
use Dotenv\Dotenv;

class SlackNotificationTest extends TestCase
{
    /**
     * 配信ターゲットを#generalにする
     * 1. メソッドを呼ぶ前の$targetsが空である
     * 2. メソッドを読んだあとの$targetsが配列である
     * 3. $targetsに<!everyone>が含まれている
     *
     * @return void
     */
    public function testTargetWorkSpace()
    {
        $sn = new SlackNotification;

        $this->assertEmpty($sn->targets);
        $sn->targetWorkspace();

        $this->assertIsArray($sn->targets);
        $this->assertContains('<!everyone>', $sn->targets);
    }

    /**
     * 配信ターゲットをチャンネル全体にする
     * 1. メソッドを呼ぶ前の$targetsが空である
     * 2. メソッドを読んだあとの$targetsが配列である
     * 3. $targetsに<!channel>が含まれている
     *
     * @return void
     */
    public function testTargetChannelAll()
    {
        $sn = new SlackNotification;

        $this->assertEmpty($sn->targets);
        $sn->targetChannelAll();

        $this->assertIsArray($sn->targets);
        $this->assertContains('<!channel>', $sn->targets);
    }

    /**
     * 配信ターゲットをアクティブなメンバーにする
     * 1. メソッドを呼ぶ前の$targetsが空である
     * 2. メソッドを読んだあとの$targetsが配列である
     * 3. $targetsに<!channel>が含まれている
     *
     * @return void
     */
    public function testTargetActive()
    {
        $sn = new SlackNotification;

        $this->assertEmpty($sn->targets);
        $sn->targetActive();

        $this->assertIsArray($sn->targets);
        $this->assertContains('<!here>', $sn->targets);
    }

    /**
     * 配信ターゲットを指定したユーザーにする
     * 1. メソッドを呼ぶ前の$targetsが空である
     * 2. メソッドを読んだあとの$targetsが配列である
     * 3. $targetsの中身が全てstringである
     * 4. $targetsの中身に<@user_a> ~ <@user_c>が含まれている
     *
     * @return void
     */
    public function testSetTargets()
    {
        $sn = new SlackNotification;

        $this->assertEmpty($sn->targets);
        $user_ids = ['user_a', 'user_b', 'user_c'];

        $sn->setTargets($user_ids);
        $this->assertIsArray($sn->targets);
        $this->assertContainsOnly('string', $sn->targets);

        $this->assertContains('<@user_a>', $sn->targets);
        $this->assertContains('<@user_b>', $sn->targets);
        $this->assertContains('<@user_c>', $sn->targets);
    }

    /**
     * 配信テストする
     * 1. 通知を送って返却されたレスポンスがOKである
     *
     * @return void
     */
    public function testSend()
    {
        Dotenv::createImmutable(__DIR__ . '/../..')->load();

        $sn = new SlackNotification;
        $message = 'テストメッセージ';
        $webhook_url = getenv('TEST_WEBHOOK_URL');
        
        $response = $sn->send($message, $webhook_url);
        $this->assertSame($response, 'ok');
    }
}