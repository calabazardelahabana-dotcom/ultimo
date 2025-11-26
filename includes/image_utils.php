<?php
// includes/image_utils.php - upload and thumbnails using GD
function image_save_upload($inputName, $tenant_id = 0, $maxFileSize = 5 * 1024 * 1024) {
    if (empty($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) return false;
    $f = $_FILES[$inputName];
    if ($f['size'] > $maxFileSize) return false;
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $f['tmp_name']);
    finfo_close($finfo);
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif'];
    if (!isset($allowed[$mime])) return false;
    $ext = $allowed[$mime];
    $dir = __DIR__ . "/../storage/uploads/{$tenant_id}";
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $name = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $target = $dir . '/' . $name;
    if (!move_uploaded_file($f['tmp_name'], $target)) return false;
    // create thumbnail
    $thumbDir = $dir . '/thumbs';
    if (!is_dir($thumbDir)) mkdir($thumbDir, 0755, true);
    $thumbPath = $thumbDir . '/' . $name;
    list($w,$h) = getimagesize($target);
    $nw = 300; $nh = intval($h * ($nw / $w));
    $thumb = imagecreatetruecolor($nw,$nh);
    switch ($mime) {
        case 'image/jpeg': $src = imagecreatefromjpeg($target); break;
        case 'image/png': $src = imagecreatefrompng($target); break;
        case 'image/gif': $src = imagecreatefromgif($target); break;
        default: return ['path'=>$target,'thumb'=>null,'name'=>$name];
    }
    imagecopyresampled($thumb, $src, 0,0,0,0,$nw,$nh,$w,$h);
    imagejpeg($thumb, $thumbPath, 85);
    imagedestroy($thumb); imagedestroy($src);
    return ['path'=>$target, 'thumb'=>$thumbPath, 'name'=>$name];
}