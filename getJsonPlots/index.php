<?php
    // 文字コード設定
    header('Content-Type: application/json; charset=UTF-8');
    $csvFilePath = "../../../app/csv_data/beginner1/beginner1_take1.csv";

    // $csvFilePath =trim(filter_input(INPUT_POST,'csvFilePath'));

    // numが存在するかつnumが数字のみで構成されているか
    // if(isset($_POST["csvFilePath"]) || !file_exists($csvFilePath) ){
    if(file_exists($csvFilePath) ){

        // メイン処理
        $arr["status"] = "yes";
        $fp =fopen($csvFilePath,'r');
        $file =file($csvFilePath);

        $arr["meta-data"]= csvToArrayMetaData($fp,0,7);
        $arr["plots"] = csvToArrayPlots($fp,7,count($file));

    } else {
        // paramの値が不適ならstatusをnoにしてプログラム終了
        $arr["status"] = "no";
    }

    // 配列をjson形式にデコードして出力, 第二引数は、整形するためのオプション
    print json_encode($arr,JSON_UNESCAPED_SLASHES);

    function csvToArrayMetaData($fp,$first,$last)
    {
        $arr=array();
        for($i=$first;$i<$last;$i++){
            $line = fgetcsv($fp);
            if(!$line)
                continue;
            $arr[$line[0]] = $line[1];
        }
        $line = fgetcsv($fp);
        $arr["parts_name"] = $line;
        return $arr;
    }

    function csvToArrayPlots($fp,$first,$last)
    {
        $arr=array();
        for($i=$first;$i<$last;$i++){
            $line = fgetcsv($fp);
            if(!$line)
                break;
            array_push($arr,$line);
        }
        return $arr;
    }
?>