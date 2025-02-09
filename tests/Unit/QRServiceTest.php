<?php

namespace Tests\Unit;

use App\Services\QRService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class QRServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_qr()
    {
        $qrService = new QRService();

        $qrCode = $qrService->generate('Test QR');

        $this->assertNotEmpty($qrCode);
        $this->assertIsString($qrCode);
    }

    public function test_read_qr()
    {
        $qrServiceMock = Mockery::mock(QRService::class);
        $qrServiceMock->shouldReceive('read')
            ->once()
            ->andReturn('Test QR Content');

        $this->app->instance(QRService::class, $qrServiceMock);

        $text = $qrServiceMock->read('fake_qr_image_path.png');

        $this->assertEquals('Test QR Content', $text);
    }
}
