<?php
$tempFileArray = $_FILES['fname']['tmp_name'];
define('movieDir','../../app/_data/');
$postedDir =basename(filter_input(INPUT_POST,'dirName',FILTER_DEFAULT));

if(!file_exists(movieDir.$postedDir))
{
    mkdir(movieDir.$postedDir,0777);
    chmod(movieDir.$postedDir,0777);
}


foreach ($tempFileArray as $i => $tempFile) {
    $fileName = basename($_FILES['fname']['name'][$i]);
    $filePath = movieDir.$postedDir.'/'.$fileName;

    if (is_uploaded_file($tempFile)) {
        if ( move_uploaded_file($tempFile , $filePath )) {
            echo $fileName . "をアップロードしました。";
    
        } else {
            echo "ファイルをアップロードできません。";
        }
    } else {
        echo "ファイルが選択されていません。";
    } 
}

?>