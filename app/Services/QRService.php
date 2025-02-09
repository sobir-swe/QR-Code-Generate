<?php

// app/Services/QRService.php
namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\QRCode as QRCodeModel;
use Illuminate\Support\Facades\Storage;

class QRService
{
    public function generate(string $text, array $options = []): string
    {
        try {
            $foregroundColor = $this->hexToRgb($options['foregroundColor'] ?? '#000000');
            $backgroundColor = $this->hexToRgb($options['backgroundColor'] ?? '#FFFFFF');

            $qrCode = QrCode::format('png')
                ->size($options['size'] ?? 300)
                ->errorCorrection($options['errorCorrection'] ?? 'H')
                ->color(...$foregroundColor)
                ->backgroundColor(...$backgroundColor)
                ->generate($text);

            if (auth()->check()) {
                QRCodeModel::create([
                    'user_id' => auth()->id(),
                    'content' => $text,
                    'options' => $options,
                    'type' => 'generated'
                ]);
            }

            return base64_encode($qrCode);
        } catch (\Exception $e) {
            \Log::error('QR Service error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function hexToRgb($hex) {
        $hex = str_replace('#', '', $hex);
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ];
    }

    public function read($file)
    {
        $qrcode = new \Zxing\QrReader($file);
        return $qrcode->text();
    }

    private function saveToDatabase($text, $userId, $options)
    {
        QRCodeModel::create([
            'user_id' => $userId,
            'content' => $text,
            'options' => json_encode($options),
            'type' => 'generated'
        ]);
    }
}
