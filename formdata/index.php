<?php
    include('../../../app/_parts/_header.php');

    require('../app/functions.php');

    // createToken();


    if($_SERVER['REQUEST_METHOD'] ==='POST')
    {
    //   validateToken();
        $movieName =trim(filter_input(INPUT_POST,'movieName'));
        $partsOfBody =filter_input(INPUT_POST,'partsOfBody',FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
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
    <div class="container-fluid ">
        <div class="row ">
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
                            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="4" aria-label="Slide 5"></button>
                            </div>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                <li class="mb-2">画面の大きさを調整してください。できる限り大きくしてください</li>
                                <img src="../app/tutorials/tutorial-1.gif" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                <li class="mb-2">表示されている順に部位をクリックして、座標を入力してください</li>
                                <img src="../app/tutorials/tutorial-2.gif" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                <li class="mb-2">全座標を入力できたら、<i class="fa-solid fa-check"></i>を押すかenterキーを押してください。自動で次のフレームに進みます</li>
                                <img src="../app/tutorials/tutorial-3.gif" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                <li class="mb-2">最後のフレームまで入力したら送信ボタンを押してください</li>
                                <img src="../app/tutorials/tutorial-4.gif" class="d-block w-100" alt="...">
                                </div>
                                <div class="carousel-item">
                                <li class="mb-2">入力を取り消したい場合は、<i class="fa-solid fa-rotate-left"></i>を押してください。</li>
                                <img src="../app/tutorials/tutorial-5.gif" class="d-block w-100" alt="...">
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-7">

             </div>
            <div class="col-2">
                <form class="text-right" action="../saveToJson/" method="post" name="formToCsv" style="text-align: right;">
                    <input type="hidden" name="movieName" value="<?= $movieName?>">
                    <?php foreach ($partsOfBody as $part):?>
                        <input type="hidden" name="partsOfBody[]" value="<?= h($part)?>">
                    <?php endforeach;?>
                    <input type="hidden" name="movieName" value="<?= $movieName?>">
                    <input id='coordinate_form' type="hidden" name="json_arr" value="">
                    <input id='fps_form' type="hidden" name="fps" value="">
                    <button id="send-arr" type="submit" class="btn btn-primary" >送信</button>
                </form>
            </div>
        </div>
    </div>



    <div id="target" class="">
        <table class="table table-light opacity-75 overflow-scroll position-absolute end-0 mt-2 me-2" style="z-index: 11; width: 240px;" >
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
        <!-- <div class="text-white position-absolute bg-blue" id="show-all"></div> -->
        <video id="mov" src="<?= $movieName ?>" width="100%"></video>
        <canvas id="canvas" style="position: absolute; z-index: 100;"></canvas>
    </div>
    <div class="progress rounded-0">
        <div id="progress-bar" class="progress-bar " role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"><span id="show-progression"></span></div>
    </div>
    <div class="container-sm bg-dark rounded" style="height: 46px; width: 700px;">
        <div class="row mt-2 pt-2 bg-dark rounded" >
            <div class="col d-flex align-items-center text-white border-end"><?= basename($movieName) ?></div>
            <div class="col-2 text-center border-end ps-0 pe-0">
                <div class="btn-group " role="group" aria-label="First group">
                    <button id="prev-frame" class="btn btn-dark"><i class="fa-solid fa-chevron-left"></i></button>
                    <button id="next-frame" class="btn btn-dark"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="col-2 d-flex align-items-center text-white justify-content-center border-end"><span id="show-frame" class=""></span>&nbsp;/&nbsp;<span id="show-max" class=""></span></div>
            <div class="col-2 d-flex align-items-center text-white justify-content-center border-end"><span id="show-time"></span>ms</div>
            <div class="col-2 text-center">
                <div class="btn-group" role="group" aria-label="Second group">
                    <button id="delete" class="btn btn-dark"><i class="fa-solid fa-rotate-left"></i></i></button>
                    <button id="submit" class="btn btn-dark"><i class="fa-solid fa-check"></i></button>
                </div>
            </div>
        </div>
    </div>












    <?php $partsOfBody_json = json_encode(($partsOfBody));?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>
        var data = '<?php echo $partsOfBody_json; ?>';
    </script>
    <script type="text/javascript" src="index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

<?php include('../../../app/_parts/_footer.php'); ?>