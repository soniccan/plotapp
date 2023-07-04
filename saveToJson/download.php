<?php

$file_name ='beginner1_take1.csv';
const UPLOADS_DIR = '../../../app/csv_data/';

$file_path = $_GET['fn'] ?? '';
print $file_path;
if (!is_readable($file_path)) die('File read error.');

$media_type = (new finfo())->file($file_path, FILEINFO_MIME_TYPE) ?? 'application/octet-stream';

header('Content-Type: ' . $media_type);
header('X-Content-Type-Options: nosniff');
header('Content-Length: ' . filesize($file_path));
header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
header('Connection: close');

while (ob_get_level()) ob_end_clean();
readfile($file_path);

