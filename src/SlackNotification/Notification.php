<?php

namespace osyou84\SlackNotification;

interface Notification
{
    public function targetWorkspace();

    public function targetChannelAll();

    public function targetActive();

    public function setTargets(array $targets);

    public function send(string $message, string $webhook_url);
}