<?php

namespace osyou84\SlackNotification;

use osyou84\SlackNotification\Notification;

class SlackNotification implements Notification
{
    private const TARGET_WORKSPACE = "<!everyone>";
    private const TARGET_CHANNEL_ALL = "<!channel>";
    private const TARGET_ACTIVE_MEMBER = "<!here>";

    public $targets = [];

    /**
     * #generalに向けてコメントする
     *
     * @return self
     */
    public function targetWorkspace(): self
    {
        $this->targets[] = self::TARGET_WORKSPACE;

        return $this;
    }

    /**
     * チャンネル全員に通知する
     *
     * @param 
     * @return self
     */
    public function targetChannelAll(): self
    {
        $this->targets[] = self::TARGET_CHANNEL_ALL;

        return $this;
    }

    /**
     * アクティブなメンバーに通知する
     *
     * @return self
     */
    public function targetActive(): self
    {
        $this->targets[] = self::TARGET_ACTIVE_MEMBER;

        return $this;
    }

    /**
     * 配信対象をセットする
     *
     * @param array $targets
     * @return self
     */
    public function setTargets(array $targets): self
    {
        $add_member = [];
        foreach ($targets as $target) {
            $add_member[] = "<@{$target}>";
        }
        $this->targets = $add_member;

        return $this;
    }

    /**
     * スラックに通知を送る
     *
     * @param string $message
     * @param string $webhook_url
     * @return string
     */
    public function send(string $message, string $webhook_url): string
    {
        $request_data = $this->formatRequestData($message);
        $response = $this->request($request_data, $webhook_url);

        return $response;
    }

    /**
     * メッセージを整形する
     *
     * @param string $message
     * @return string
     */
    private function formatRequestData(string $message): string
    {
        $message_data = '';

        if (!empty($this->targets)) {
            foreach ($this->targets as $target) {
                $message_data .= "{$target}\n";
            }
        }
        $message_data .= $message;

        return 'payload=' . json_encode(['text' => $message_data]);
    }

    /**
     * 通知要求する
     *
     * @param string $$request_data
     * @param string $webhook_url
     * @return string
     */
    private function request(string $request_data, string $webhook_url): string
    {
        try {
            $options = [
                CURLOPT_URL => $webhook_url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $request_data,
                CURLOPT_RETURNTRANSFER => true,
            ];
            $curl = curl_init();
            curl_setopt_array($curl, $options);
            $res = curl_exec($curl);

            $errno = curl_errno($curl);
            $error = curl_error($curl);

            curl_close($curl);

            if (CURLE_OK !== $errno) {
                throw new \Exception($error, $errno);
            }
        } catch (\Exception $e) {
            error_log("error({$e->getCode()}): {$e->getMessage()}\n", 3, __DIR__ . "/../../logs/error.log");
            return $e->getMessage();
        }
        
        return $res;
    }
}