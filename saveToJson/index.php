<?php

    $countHeaderLines =8;
    include("./saveToCsv.php");
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

    if(!file_exists("../../../app/json_data/".$parent_name)){
        mkdir("../../../app/json_data/".$parent_name,0777);
        chmod('../../../app/json_data/',0777);
    }

    $associative_array['is_file_exist']=true;
    $associative_array['name'] =$filename;
    $associative_array['date'] =date("Y-m-d H:i:s");
    $associative_array['fps'] = intval($fps);
    $associative_array['database'] =  "https://yamagiwalab.jp/~deepsports/database";
    $associative_array['created_by'] = $_SERVER['REMOTE_USER'];
    $associative_array['license'] = "The data is available on CC BY-BC-SA 4.0. https://creativecommons.org/licenses.by-nc-sa/4.0/.";
    $associative_array['describe'] = 'This is the plots data of human body parts.The date is arranged in sequential time in a row.For the details,please check our database site.';
    $line =new ArrayObject();
    foreach ($partsOfBody as $part) {
        $line->append($part."_x");
        $line->append($part."_y");
    }
    $associative_array['parts'] = $line;
    $line_arr =new ArrayObject();
    foreach ($arr as $frame) {
        $line =new ArrayObject();
        foreach ($frame as $part) {
            $line->append($part[0]);
            $line->append($part[1]);
        }
        // fputjson($fp,$line->getArrayCopy($line->Arr));
        $line_arr->append($line->getArrayCopy());
    }
    $associative_array['plots'] = $line_arr->getArrayCopy();
    $filePathJson = "../../../app/json_data/".$parent_name."/".$filename.".json";
    file_put_contents($filePathJson,json_encode($associative_array));


