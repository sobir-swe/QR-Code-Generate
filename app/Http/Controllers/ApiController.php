<?php

namespace App\Http\Controllers;

use App\Services\QRService;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    private $qrService;

    public function __construct(QRService $qrService)
    {
        $this->qrService = $qrService;
    }

    public function generate(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validated = $request->validate([
                'text' => 'required|string',
                'size' => 'nullable|integer|min:100|max:1000',
                'errorCorrection' => 'nullable|in:L,M,Q,H',
                'foregroundColor' => 'nullable|string',
                'backgroundColor' => 'nullable|string',
            ]);

            \Log::info('QR generation request:', $validated);

            $qrCode = $this->qrService->generate($validated['text'], $validated);

            return response()->json(['qrCode' => $qrCode]);
        } catch (\Exception $e) {
            \Log::error('QR generation error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function read(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => 'required|image'
        ]);

        $text = $this->qrService->read($request->file('file')->path());

        return response()->json(['text' => $text]);
    }
}
