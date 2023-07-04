<?php
    require("../app/functions.php");
    $countHeaderLines =8;

    if($_SERVER['REQUEST_METHOD'] ==='POST')
    {
        $partsOfBody =filter_input(INPUT_POST,'partsOfBody',FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $movieName =trim(filter_input(INPUT_POST,'movieName'));
        $json_arr =trim(filter_input(INPUT_POST,'json_arr'));
        $fps =trim(filter_input(INPUT_POST,'fps'));
        $arr =json_decode($json_arr);

    }else{
        header("Location: ../index.php");
        exit;
    }
    $filename = pathinfo($movieName,PATHINFO_FILENAME);
    $parent_name = basename(dirname($movieName));

    if(!file_exists("../../../app/csv_data/".$parent_name)){
        mkdir("../../../app/csv_data/".$parent_name,0777);
    }

    $filePathCsv = "../../../app/csv_data/".$parent_name."/".$filename.".csv";
    $fp = fopen($filePathCsv, 'w');
    $line =new ArrayObject();
    foreach ($partsOfBody as $part) {
        $line->append($part."_x");
        $line->append($part."_y");
    }
    $err = writeHeader($fp,$filename,$fps,count($arr));
    fputcsv($fp,$line->getArrayCopy());



    foreach ($arr as $frame) {
        $line =new ArrayObject();
        foreach ($frame as $part) {
            $line->append($part[0]);
            $line->append($part[1]);
        }
        fputcsv($fp,$line->getArrayCopy());
    }
    function writeHeader($fp,$filename,$fps,$length){
        fputcsv($fp,['Name',$filename]);
        fputcsv($fp,['Date',date("Y-m-d H:i:s")]);
        fputcsv($fp,['FPS',$fps]);
        fputcsv($fp,['NumberOfPlots',$length]);
        // fputcsv($fp,['Created By',$_SERVER['PHP_AUTH_USER']]);
        fputcsv($fp,['Database','https://yamagiwalab.jp/~deepsports/database']);
        fputcsv($fp,['License','The data is available under CC BY-NC-SA 4.0. https://creativecommons.org/licenses/by-nc-sa/4.0/.']);
        fputcsv($fp,['Describe','This is the plots data of human body parts.The date is arranged in sequential time in a row.For the details,please check our database site.']);
        return true;
    }
    echo h($filename)."の入力を完了しました。ありがとうございます。データはサーバーに保管されました。\n";
    ?>
<div>
    <a href="../index.php">一番最初に戻る</a>
    <a href="../youtubeForm/selectedID.php">GolfDBに戻る</a>
    <a href=<?= "download.php/?fn=".$filePathCsv ?> >今のデータをcsvでダウンロードする。</a>

</div>


