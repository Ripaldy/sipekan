<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPEKAN - Sistem Informasi Posyandu Kecamatan</title>
    <link rel="icon" type="image/svg+xml" href="/vite.svg" />
    
    @php
        $manifest = json_decode(file_get_contents(public_path('.vite/manifest.json')), true);
        $entrypoint = $manifest['index.html'] ?? null;
    @endphp
    
    @if($entrypoint && isset($entrypoint['css']))
        @foreach($entrypoint['css'] as $css)
            <link rel="stylesheet" href="/{{ $css }}">
        @endforeach
    @endif
</head>
<body>
    <div id="root"></div>
    
    @if($entrypoint && isset($entrypoint['file']))
        <script type="module" src="/{{ $entrypoint['file'] }}"></script>
    @endif
</body>
</html>
