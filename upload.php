<?php

define('IMG_URL',   '/upload_imgs/');
define('IMG_PATH',  '.'.IMG_URL);

$img_name = upload_img();
// debug($img_name);

echo $img_name;
// print_r($img_name);

function upload_img()
{
    $img_name = null;
    if (isset($_FILES['imageFile']['error']) && is_int($_FILES['imageFile']['error'])) {
        try {
            // image type check
            $type = @exif_imagetype($_FILES['imageFile']['tmp_name']);
            if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) {
                throw new RuntimeException('画像形式が未対応です');
            }
    
            // save image after change name
            $img_name = time().image_type_to_extension($type);
            $path = IMG_PATH.$img_name;
            if (!move_uploaded_file($_FILES['imageFile']['tmp_name'], $path)) {
                throw new RuntimeException('ファイル保存時にエラーが発生しました');
            }
            chmod($path, 0644);
            $msg = ['green', 'ファイルは正常にアップロードされました'];
    
        } catch (RuntimeException $e) {
            return ['red', $e->getMessage()];
        }
    }
    
    return IMG_URL.$img_name;
}

function debug($value)
{
    if (empty($value)) {
        return false;
    }
    if (is_array($value) or is_object($value)) {
        echo "<br>debug::<pre>"; print_r($value); echo "</pre>";
    } else {
        echo "<br>debug::".$value;
    }
    return true;
}

