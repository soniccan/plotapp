<?php
    include('../../../../app/_parts/_header.php');

    require('../../app/functions.php');

    // createToken();


    if($_SERVER['REQUEST_METHOD'] ==='POST')
    {
    //   validateToken();
        $jsonIndex = trim(filter_input(INPUT_POST,'jsonIndex'));

        if($jsonIndex == NULL)
        {
            $youtubeID =trim(filter_input(INPUT_POST,'youtubeID'));
            $startTime =trim(filter_input(INPUT_POST,'startTime'));
            $maxFrame =trim(filter_input(INPUT_POST,'maxFrame'));
            $partsOfBody =filter_input(INPUT_POST,'parts_Body',FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        }else{
            $fileName =filter_input(INPUT_POST,'fileName');
            $json = file_get_contents("../../../../app/".$fileName);

            $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            $dbArr = json_decode($json,true);
            $dbDataArr = $dbArr['data'];

            $youtubeID = $dbDataArr[$jsonIndex]['youtube_id'];
            $startTime = $dbDataArr[$jsonIndex]['events'][0]/30.;
            $maxFrame = $dbDataArr[$jsonIndex]['events'][9] - $dbDataArr[$jsonIndex]['events'][0];
            $partsOfBody =filter_input(INPUT_POST,'parts_Body',FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        }

    }
    else{
        header("Location: ../index.php");
        exit;
    }

?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/d28f3c92ce.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Button trigger modal -->
            <div class="col-3">
                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fa-solid fa-question"></i> 初めての方へ</button>

                <!-- Modal -->
                <div class="modal" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">操作説明</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <li>画面の大きさを調整してください。できる限り大きくしてください</li>
                                <li>表示されている順に部位をクリックして、座標を入力してください</li>
                                <li>全座標を入力できたら、<i class="fa-solid fa-check"></i>を押すかenterキーを押してください。自動で次のフレームに進みます</li>
                                <li>最後のフレームまで入力したら送信ボタンを押してください</li>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7">

             </div>
            <div class="col-2">
                <form class="text-right" style="text-align: right;" action="../../saveToJson/" method="post" name="formToCsv" onclick="checkFinished()">
                <input type="hidden" name="movieName" value=<?= "/youtube/".$youtubeID."_".$startTime?>>
                    <?php foreach ($partsOfBody as $part):?>
                        <input type="hidden" name="partsOfBody[]" value="<?= $part?>">
                    <?php endforeach;?>
                    <input id='coordinate_form' type="hidden" name="json_arr" value="">
                    <input id='fps_form' type="hidden" name="fps" value="">
                    <button class="btn btn-primary" id="send_arr" type="submit">送信</button>
                </form>
            </div>
        </div>
    </div>


    <div id="target" style="aspect-ratio: 16/9;">
        <!-- <video id="mov" src="<?= $movieName ?>" width="100%" max-height="80%"></video> -->
        <iframe id="player"  style="position: relative; width: 100%; height: 100%; z-index: 10;" src="https://www.youtube.com/embed/?enablejsapi=1&controls=0&disablekb=1&mute=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        <canvas id="canvas" style="position: absolute; z-index: -1;"></canvas>
    </div>
    <div class="progress rounded-0">
        <div id="progress-bar" class="progress-bar " role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><span id="show-progression"></span></div>
    </div>
    <div class="container-fluid">
        <div class="row align-items-center">
        <div class="col">
            <button id="start" class="btn btn-primary">始める</button>
        </div>
        <div class="col d-flex justify-content-between align-items-center bg-dark rounded" style="width: 300px;">
            <div class="btn-group " role="group" aria-label="First group">
                <button id="prev-frame" class="btn btn-dark"><i class="fa-solid fa-chevron-left"></i></button>
                <button id="next-frame" class="btn btn-dark"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
            <div class="d-flex align-items-center text-white" style="height: 40px; width: 140px;">
                &emsp;
                <span id="show-frame" class=""></span>&nbsp;/&nbsp;<span id="show-max" class=""></span>&emsp;
                <span id="show-time"></span>ms&emsp;
            </div>
            <div class="btn-group " role="group" aria-label="Second group">
                <button id="delete" class="btn btn-dark"><i class="fa-solid fa-rotate-left"></i></button>
                <button id="submit" class="btn btn-dark"><i class="fa-solid fa-check"></i></button>
            </div>
        </div>
        <div class="col"></div>
        </div>
    </div>




    <!-- <p id="stats"><span id="show-frame"></span>/<span id="show-max"></span>&emsp;&emsp;<span id="show-time"></span>ms</p> -->
    <!-- <div id="show-all"></div> -->
    <table class="table table-light overflowscroll mt-2 me-2" style="z-index: 11; width: 240px;" >
        <thead>
            <tr>
                <th style="width: 10%">#</th>
                <th>part</th>
                <th style="width: 45%">point</th>
            </tr>
        </thead>
        <tbody id="show-all">

        </tbody>
    </table>



    <!-- <form action="../../saveToJson/" method="post" name="formToCsv" onclick="checkFinished()">

        <input type="hidden" name="movieName" value="<?= "/youtube/".$youtubeID?>">
        <?php foreach ($partsOfBody as $part):?>
            <input type="hidden" name="partsOfBody[]" value="<?= $part?>">
        <?php endforeach;?>
        <input id='coordinate_form' type="hidden" name="json_arr" value="">
        <input id='fps_form' type="hidden" name="fps" value="">
        <p>最後のフレームまで入力したら送信ボタンを押してください。 </p>
        <button id="send_arr" type="submit">送信</button>
    </form> -->


    <?php $partsOfBody_json = json_encode(($partsOfBody));?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>
        var data = '<?php echo $partsOfBody_json; ?>';
        let startTime = '<?=$startTime?>';
        let youtubeID = '<?=$youtubeID?>';
        let maxFrame = '<?=$maxFrame?>';
    </script>
    <script type="text/javascript" src="./index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

<?php include('../../../app/_parts/_footer.php'); ?>