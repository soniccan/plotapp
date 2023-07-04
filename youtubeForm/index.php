<?php
    require('../app/functions.php');
    define('movieDir', './app/_data/');
    $movieAll=glob(movieDir."*",GLOB_ONLYDIR);
    include('../../../app/_parts/_header.php');
    $json_parts = file_get_contents("/home/deepsports/public_html/plotapp/plot-config.json");
    $json_parts = mb_convert_encoding($json_parts, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    $arr_parts = json_decode($json_parts,true);
    $parts = $arr_parts['parts'];
    $japaneseName = $arr_parts['japanese-name'];
?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" type="text/css" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<header class="masthead">
<div class="container position-relative">
                <div class="row justify-content-center">
                    <div class="col-xl-6">
                        <div class="text-center text-white">
                            <h1>PlotApp</h1>
                            <h5 class="mb-5">それぞれを入力してください</h5>
                            <form action="./formdata/" method="post" style="text-align:center;margin:0 auto;">
                                <table style="text-align:center;margin:0 auto;">
                                    <thead class="text-center text-white">
                                        <tr>
                                        <th>youtubeID</th>
                                        <th>開始時間</th>
                                        <th>フレーム入力数</th>
                                        </tr>
                                        <tr>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <td><input type="text" name="youtubeID"></td>
                                        <td><input type="text" name="startTime"></td>
                                        <td><input type="text" name="maxFrame"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="./selectedID.php">GolfDBなどの保管されたデータの方はこちら</a>
                                <div class="mb-5">
                                    <?php foreach ($parts as $i =>$part):?>
                                        <input type="checkbox" id=<?= $part ?> name="parts_Body[]" value=<?=$part?> checked>
                                        <label for=<?=$part?>><?=$japaneseName[$i]?></label>
                                    <?php endforeach;?>
                                </div>
                                <input type="submit" name="send" value="送信">
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
</header>


<?php include('../../app/_parts/_footer.php'); ?>