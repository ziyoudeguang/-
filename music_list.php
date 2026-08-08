<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// 音乐目录路径
$music_dir = __DIR__ . '/music/';

// 检查目录是否存在
if (!is_dir($music_dir)) {
    echo json_encode(['error' => '音乐目录不存在'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 扫描目录获取所有文件
$files = scandir($music_dir);

// 支持的音频格式
$supported_formats = ['mp3', 'flac', 'wav', 'm4a', 'ogg'];

$music_list = [];

foreach ($files as $file) {
    // 跳过 . 和 ..
    if ($file == '.' || $file == '..') continue;
    
    $file_path = $music_dir . $file;
    
    // 只处理文件，不处理子目录
    if (!is_file($file_path)) continue;
    
    // 获取文件扩展名
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    
    // 检查是否是支持的音频格式
    if (in_array($ext, $supported_formats)) {
        // 获取文件名（不含扩展名）
        $filename = pathinfo($file, PATHINFO_FILENAME);
        
        // 构建 URL（注意 URL 编码）
        $url = 'https://music.ziyoudeguang.cn/music/' . rawurlencode($file);
        
        $music_list[] = [
            'url' => $url,
            'title' => $filename,
            'artist' => '未知艺术家',
            'format' => strtoupper($ext),
            'filename' => $file
        ];
    }
}

// 按文件名排序
usort($music_list, function($a, $b) {
    return strcoll($a['title'], $b['title']);
});

echo json_encode([
    'total' => count($music_list),
    'music' => $music_list
], JSON_UNESCAPED_UNICODE);
?>