<?php
header('Content-Type: text/plain; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// 歌词目录路径
$lrc_dir = __DIR__ . '/musiclrc/';

// 获取请求的文件名（原始文件名，未解码）
$raw_file = isset($_GET['file']) ? $_GET['file'] : '';

if (empty($raw_file)) {
    http_response_code(400);
    die('参数错误');
}

// 解码一次（浏览器会自动编码，我们只需要解码一次）
$file_name = urldecode($raw_file);

// 将音乐扩展名替换为 .lrc
$lrc_file_name = preg_replace('/\.(mp3|flac|m4a|ogg)$/i', '.lrc', $file_name);

// 安全处理：防止路径遍历
$lrc_file_name = basename($lrc_file_name);
$lrc_path = $lrc_dir . $lrc_file_name;

// 检查文件是否存在
if (!file_exists($lrc_path)) {
    http_response_code(404);
    die('歌词文件不存在');
}

// 读取并输出歌词
$content = file_get_contents($lrc_path);
if ($content === false) {
    http_response_code(500);
    die('无法读取歌词文件');
}

echo $content;
?>