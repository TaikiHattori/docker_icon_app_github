<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRFトークンを追加 -->
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    
    <title>Docker Icon App</title>
    <style>
        img {
            width: 100px; /* 画像の幅を小さく設定 */
            height: auto; /* アスペクト比を維持 */
            margin: 5px; /* 画像間の余白 */
        }
        #error {
            color: red;
            margin-top: 20px;
        }
    </style>
    <script>
        let progress = 0;

        async function updateProgress() {
    if (progress < 4) {
        progress++;
        // CSRFトークンを取得
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); 
        

        await fetch('/progress', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // CSRFトークンをヘッダーに追加
            },
            body: JSON.stringify({ progress })
        });
        renderProgress();
    }
}

async function loadProgress() {
        try {
            // リロード時に進捗をリセット
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // CSRFトークンを取得
            await fetch('/reset-progress', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken // CSRFトークンをヘッダーに追加
                }
            });

            // サーバーから進捗を取得
            const response = await fetch('/progress');
            const data = await response.json();
            progress = data.progress || 0;
            renderProgress();
        } catch (error) {
            // document.getElementById('error').innerText = '進捗データの読み込みに失敗しました。';
        }
    }

        // async function triggerError() {
        //     try {
        //         const response = await fetch('/trigger-error');
        //         if (!response.ok) {
        //             throw new Error(await response.text());
        //         }
        //     } catch (error) {
        //         document.getElementById('error').innerText = error.message; // エラーを表示
        //     }
        // }

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
        <button onclick="triggerError()">エラーを発生させる</button> <!-- エラー発生ボタン -->
        <br>
        <div id="progress"></div>
        <div id="error"></div> <!-- エラー表示用 -->
    </div>
</body>
</html>
