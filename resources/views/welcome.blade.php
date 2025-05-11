<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Docker Icon App</title>
    <style>
        img {
            width: 100px; /* 画像の幅を小さく設定 */
            height: auto; /* アスペクト比を維持 */
            margin: 5px; /* 画像間の余白 */
        }
    </style>
    <script>
        let progress = 0;

        async function updateProgress() {
            if (progress < 4) {
                progress++;
                await fetch('/progress', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ progress })
                });
                renderProgress();
            }
        }

        async function loadProgress() {
            const response = await fetch('/progress');
            const data = await response.json();
            progress = data.progress || 0;
            renderProgress();
        }

        function renderProgress() {
            const stages = [
                '<img src="/images/docker_stage_1.png" alt="Stage 1">',
                '<img src="/images/docker_stage_2.png" alt="Stage 2">',
                '<img src="/images/docker_stage_3.png" alt="Stage 3">',
                '<img src="/images/docker_stage_4.png" alt="Stage 4">'
            ];
            let output = '';
            for (let i = 0; i < progress; i++) {
                output += stages[i]; // クリック数に応じて画像を追加
            }
            document.getElementById('progress').innerHTML = output || '未開始';
        }

        window.onload = loadProgress;
    </script>
    </head>

    <body>
    <div style="text-align: center; margin-top: 50px;">
        <h1>Docker Icon App</h1>
        <button onclick="updateProgress()">クリック</button>
        <div id="progress">未開始</div>
    </div>
</body>
</html>
