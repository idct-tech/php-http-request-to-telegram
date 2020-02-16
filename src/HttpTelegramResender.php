<?php

namespace IDCT\HttpTelegramResender;

use IDCT\TelegramSender\Bot;
use IDCT\TelegramSender\PrivateChannel;
use IDCT\TelegramSender\TelegramSender;

class HttpTelegramResender
{
    protected $projects;

    public function loadProjects()
    {
        $this->projects = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'projects.json'), true);
    }

    public function run()
    {
        foreach ($this->projects as $projectCandidate) {
            if (substr($_SERVER['REQUEST_URI'], 0, strlen($projectCandidate['route'])) == $projectCandidate['route']) {
                $input = $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI'] . "\r\n" . file_get_contents("php://input");
                $sender = new TelegramSender();
                $bot = new Bot($projectCandidate['bot_id'], $projectCandidate['bot_secret']);
                $channel = new PrivateChannel($projectCandidate['channel_id']);
                $sender->sendMessage($bot, $channel, $input, null, true, false);
                if ($projectCandidate['response']) {
                    $responseObject = new $projectCandidate['response'];
                    $responseObject->run();
                }
                break;
            }
        }
    }
}
