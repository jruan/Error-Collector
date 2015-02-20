<?php

function upload(){
try {
    
    // Undefined | Multiple Files | $_FILES Corruption Attack
    // If this request falls under any of them, treat it invalid.
    if (
        !isset($_FILES['upfile']['error']) ||
        is_array($_FILES['upfile']['error'])
    ) {
        throw new RuntimeException('Invalid parameters.');
    }

    // Check $_FILES['upfile']['error'] value.
    switch ($_FILES['upfile']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Exceeded filesize limit.');
        default:
            throw new RuntimeException('Unknown errors.');
    }

    // You should also check filesize here. 
    if ($_FILES['upfile']['size'] > 1000000) {
        throw new RuntimeException('Exceeded filesize limit.');
    }

    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
    // Check MIME Type by yourself.
/*
    if (exif_imagetype($_FILES['upfile']['name']) != IMAGETYPE_GIF 
        AND exif_imagetype($_FILES['upfile']['name']) != IMAGETYPE_JPEG 
        AND exif_imagetype($_FILES['upfile']['name']) != IMAGETYPE_PNG) {
    echo "{";
    echo        "error: 'This is no photo..'\n";
    echo "}";
    exit(0);
   }
*/
    $ext = end(explode('.', $_FILES['upfile']['tmp_name']));
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $ext = array_search(
        $finfo->file($_FILES['upfile']['tmp_name']),
        array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
        ),
        true
    )) {
        throw new RuntimeException('Invalid file format.');
    } 
    // You should name it uniquely.
    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
    // On this example, obtain safe unique name from its binary data.
    $filename = sha1_file($_FILES['upfile']['tmp_name']).'.'.$ext;
    if (!move_uploaded_file(
        $_FILES['upfile']['tmp_name'],
	sprintf('./upload/%s',$filename)
    )) {
        throw new RuntimeException('Failed to move uploaded file.');
    }
    return $filename;
} catch (RuntimeException $e) {

    echo $e->getMessage();
    die();

}
}

?>
