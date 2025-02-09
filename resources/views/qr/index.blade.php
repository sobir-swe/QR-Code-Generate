<!-- resources/views/qr/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto px-4 py-8">
    <div class="max-w-xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">QR Code Generator</h1>

        <form id="qrForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Text or URL</label>
                <input type="text" name="text" class="w-full p-2 border rounded" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Size</label>
                    <input type="number" name="size" value="300" min="100" max="1000"
                           class="w-full p-2 border rounded">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Error Correction</label>
                    <select name="errorCorrection" class="w-full p-2 border rounded">
                        <option value="L">Low</option>
                        <option value="M">Medium</option>
                        <option value="Q">Quartile</option>
                        <option value="H">High</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Foreground Color</label>
                    <input type="color" name="foregroundColor" value="#000000"
                           class="w-full p-1 border rounded">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Background Color</label>
                    <input type="color" name="backgroundColor" value="#FFFFFF"
                           class="w-full p-1 border rounded">
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                Generate QR Code
            </button>
        </form>

        <div id="qrResult" class="mt-6 text-center hidden">
            <img id="qrImage" class="mx-auto" alt="QR Code">
            <button onclick="downloadQR()"
                    class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Download QR Code
            </button>
        </div>

        <div class="mt-8 pt-8 border-t">
            <h2 class="text-xl font-bold mb-4">Read QR Code</h2>
            <input type="file" id="qrFile" accept="image/*" class="w-full">
            <div id="qrReadResult" class="mt-4"></div>
        </div>
    </div>
</div>

<script>
    document.getElementById('qrForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const formObject = Object.fromEntries(formData);

        try {
            const response = await fetch('/qr/generate', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formObject)
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();

            if (data.error) {
                throw new Error(data.error);
            }

            document.getElementById('qrImage').src = 'data:image/png;base64,' + data.qrCode;
            document.getElementById('qrResult').classList.remove('hidden');
        } catch (error) {
            console.error('Error:', error);
            alert('Error generating QR code: ' + error.message);
        }
    });

    document.getElementById('qrFile').addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch('/qr/read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const data = await response.json();
            document.getElementById('qrReadResult').textContent = data.text;
        } catch (error) {
            alert('Error reading QR code');
        }
    });

    function downloadQR() {
        const link = document.createElement('a');
        link.download = 'qrcode.png';
        link.href = document.getElementById('qrImage').src;
        link.click();
    }
</script>
</body>
</html>
