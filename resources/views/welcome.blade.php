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
        width: 400px; /* 画像の幅を調整 */
        height: auto; /* アスペクト比を維持 */
        /* display: block; */
        margin-right: -100px;
        }
        #error {
            color: red;
            margin-top: 20px;
        }
        .square {
            width: 50px; /* 正方形の幅 */
            height: 50px; /* 正方形の高さ */
            background-color: #2496ed; /* Dockerの青色 */
            display: inline-block;
            margin: 1px; /* 正方形間の余白 */
        }
        .row {
            display: flex; /* 横並びにする */
            justify-content: center; /* 中央揃え */
        }
        .row.offset1 {
            margin-right: 50px; /* 左にずらす */
        }
        .square.offset-right {
        margin-left: 155px; /* 右にずらす */
        margin-top: -155px; /* 上にずらす */
        }
        .square.offset-right-top {
        margin-left: 211px; /* 右にずらす */
        margin-top: -51px; /* 上にずらす */
        }

        .button-container {
        text-align: left; /* ボタンを左に揃える */
        margin-left: 200px; /* 必要に応じて左にずらす量を調整 */
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

        async function triggerError() {
            try {
                const response = await fetch('/trigger-error');
                if (!response.ok) {
                    throw new Error(await response.text());
                }
            } catch (error) {
                document.getElementById('error').innerText = error.message; // エラーを表示
            }
        }

        function renderProgress() {
            const container = document.getElementById('progress');
            container.innerHTML = ''; // 既存の正方形をクリア

            if (progress >= 1) {
        // 1クリック目: 3つの正方形を合体した形
        const row1 = document.createElement('div');
        row1.className = 'row';
        const square1 = document.createElement('div');
        square1.className = 'square';
        row1.appendChild(square1);

        const row2 = document.createElement('div');
        row2.className = 'row offset1'; // 左にずらすクラスを追加
        const square2 = document.createElement('div');
        square2.className = 'square';
        const square3 = document.createElement('div');
        square3.className = 'square';
        row2.appendChild(square2);
        row2.appendChild(square3);

        container.appendChild(row1);
        container.appendChild(row2);
    }

    if (progress >= 2) {
        // 2クリック目: 右側に上下2つの正方形を追加
        const row1 = container.querySelector('.row'); // 1クリック目の上段を取得
        const square4 = document.createElement('div');
        square4.className = 'square';
        row1.appendChild(square4); // 上段に追加

        const row2 = container.querySelector('.row.offset1'); // 1クリック目の下段を取得
        const square5 = document.createElement('div');
        square5.className = 'square';
        row2.appendChild(square5); // 下段に追加
    }

    if (progress >= 3) {
        // 3クリック目: 右側にL字型の4つの正方形を追加
        const row1 = container.querySelector('.row'); // 1クリック目の上段を取得
        const square6 = document.createElement('div');
        square6.className = 'square';
        row1.appendChild(square6); // 上段に追加

        const row2 = container.querySelector('.row.offset1'); // 1クリック目の下段を取得
        const square7 = document.createElement('div');
        square7.className = 'square';
        row2.appendChild(square7); // 下段に追加

        // 新しい行を作成して3段目を追加
        const row3 = document.createElement('div');
        row3.className = 'row offset1'; // 左にずらすクラスを追加
        const square8 = document.createElement('div');
        square8.className = 'square offset-right'; // 右上にずらすクラスを追加
        row3.appendChild(square8);

        container.appendChild(row3); // 3段目をコンテナに追加
    
        // さらにもう1つの正方形を追加
        const row4 = document.createElement('div');
        row4.className = 'row'; // 新しい行を作成
        const square9 = document.createElement('div');
        square9.className = 'square offset-right-top'; // 右上にずらすクラスを追加
        row4.appendChild(square9);

        container.appendChild(row4); // 新しい行をコンテナに追加
    }

    if (progress >= 4) {
    // 4クリック目: 画像を正方形の真下に表示
    const imageRow = document.createElement('div');
    imageRow.className = 'row'; // 新しい行を作成
    const image = document.createElement('img');
    image.src = '/images/docker_stage_5_2.png'; // 画像のパスを指定　※/publicは不要
    image.style.marginTop = '1px'; // 上部に余白を追加
    imageRow.appendChild(image);

    container.appendChild(imageRow); // 画像の行をコンテナに追加
    }
}


    window.onload = loadProgress;
    </script>
    </head>

    <body>
    <div style="text-align: center; margin-top: 50px;">
        <h1>Docker Icon App</h1>
        <div class="button-container">
            <button onclick="updateProgress()">dockerアイコンを作る</button>
            <button onclick="triggerError()">エラーを発生させる</button> <!-- エラー発生ボタン -->
        </div>
        <br>
        <div id="progress"></div>
        <div id="error"></div> <!-- エラー表示用 -->
    </div>
</body>
</html>
