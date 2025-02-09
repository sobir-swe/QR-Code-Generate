<?php

namespace App\Http\Controllers;

use App\Services\QRService;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

class TelegramController extends Controller
{
    private $qrService;
    private $telegram;

    public function __construct(QRService $qrService)
    {
        $this->qrService = $qrService;
        $this->telegram = new BotApi(config('services.telegram.bot_token'));
    }

    public function handle()
    {
        $update = json_decode(file_get_contents('php://input'), true);
        $update = new Update($update);

        if ($message = $update->getMessage()) {
            if ($text = $message->getText()) {
                $qrCode = $this->qrService->generate($text);
                $this->telegram->sendPhoto(
                    $message->getChat()->getId(),
                    $qrCode
                );
            } elseif ($photo = $message->getPhoto()) {
                $file = $this->telegram->getFile($photo[count($photo) - 1]->getFileId());
                $filePath = $file->getFilePath();

                try {
                    $text = $this->qrService->read($filePath);
                    $this->telegram->sendMessage(
                        $message->getChat()->getId(),
                        "QR koddan o'qilgan matn: " . $text
                    );
                } catch (\Exception $e) {
                    $this->telegram->sendMessage(
                        $message->getChat()->getId(),
                        "QR kodni o'qishda xatolik yuz berdi"
                    );
                }
            }
        }
    }
}
