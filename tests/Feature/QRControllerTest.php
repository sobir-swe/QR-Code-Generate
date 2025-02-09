<?php

namespace Tests\Feature;

use App\Services\QRService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Mockery;

class   QRControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_qr_successfully()
    {
        $qrServiceMock = Mockery::mock(QRService::class);
        $this->app->instance(QRService::class, $qrServiceMock);

        $qrServiceMock->shouldReceive('generate')
            ->once()
            ->andReturn(base64_encode('dummy_qr_code'));

        $response = $this->postJson('/qr/generate', [
            'text' => 'Test QR Code',
            'size' => 300,
            'errorCorrection' => 'H'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['qrCode']);
    }

    public function test_read_qr_successfully()
    {
        $qrServiceMock = Mockery::mock(QRService::class);
        $this->app->instance(QRService::class, $qrServiceMock);

        $qrServiceMock->shouldReceive('read')
            ->once()
            ->andReturn('Scanned QR Code Content');

        $file = UploadedFile::fake()->image('qr.png');

        $response = $this->postJson('/qr/read', [
            'file' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson(['text' => 'Scanned QR Code Content']);
    }

    public function test_read_qr_validation_error()
    {
        $response = $this->postJson('/qr/read', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }
}
