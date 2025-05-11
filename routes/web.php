<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/progress', function () {
    // 進捗データを取得
    $progress = json_decode(Storage::get('progress.json', '{}'), true);
    return response()->json($progress);
});

Route::post('/progress', function (Request $request) {
    // 進捗データを更新
    $progress = $request->input('progress', 0);
    Storage::put('progress.json', json_encode(['progress' => $progress]));
    return response()->json(['status' => 'success']);
});