
<?php
    require('../../app/functions.php');
    define('movieDir', './app/_data/');
    $partsOfBody =filter_input(INPUT_POST,'parts_Body',FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $movieAll=glob(movieDir."*",GLOB_ONLYDIR);
    include('../../app/_parts/_header.php');
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
    <body>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container position-relative">
                <div class="row justify-content-center">
                    <div class="col-xl-6">
                        <div class="text-center text-white">
                            <!-- Page heading-->
                            <h1>PlotApp</h1>
                            <h5 class="mb-5">プロットしたいムービーを選んでください。</h5>
                            <form  action="selectTake/"  id="contactForm" method="post">
                                <!-- Email address input-->
                                <label class ="mb-3" for="data-select">どのプレイヤーと部位を入力しますか？</label>

                                    <select class ="form-select mb-3" name="personName" id="data-select" required>
                                        <option value="">プレイヤーを選択</option>

                                        <?php foreach ($movieAll as $movie):?>
                                            <option value= <?= h(basename($movie))?>>
                                                <?= h(basename($movie))?>
                                            </option>
                                        <?php endforeach;?>
                                    </select>

                                <div class="mb-5">
                                    <?php foreach ($parts as $i =>$part):?>
                                        <input type="checkbox" id=<?= $part ?> name="parts_Body[]" value=<?=$part?> checked>
                                        <label for=<?=$part?>><?=$japaneseName[$i]?></label>
                                    <?php endforeach;?>
                                </div>
                                <button class ="btn btn-primary btn-lg ml-5" type="submit">選択画面へ行く</button>
                            </form>
                            <div class="row">
                                <a href="./selectparts.php">自分で部位を選択する人はこちら</a>
                                <a href="./uploadDir">動画をアップロードしたい人はこちら</a>
                                <a href="./youtubeForm">Youtube動画をプロットしたい人はこちら</a>
                                <a href="./youtubeForm/selectedID.php">GolfDBなどの保管されたデータの方はこちら</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <section class="features-icons bg-light text-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                            <div class="features-icons-icon d-flex"><i class="bi-window m-auto text-primary"></i></div>
                            <h3>About</h3>

                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                            <div class="features-icons-icon d-flex"><i class="bi-layers m-auto text-primary"></i></div>
                            <h3>Database</h3>

                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="features-icons-item mx-auto mb-0 mb-lg-3">
                            <div class="features-icons-icon d-flex"><i class="bi-terminal m-auto text-primary"></i></div>
                            <h3>DeepSports</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Image Showcases-->
        <section class="showcase">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image: url('assets/img/bg-showcase-1.jpg')"></div>
                    <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                        <h2>About</h2>
                        <p class="lead mb-0">このアプリは、身体の特徴点を人の手で入力してより正しい値で実験データを取ることを目的としたアプリです。開発者にコンタクトをとると、自分の動画を載せることができ、自分の実験に必要なデータをとることができます。
                        </p>
                    </div>
                </div>
                <div class="row g-0">
                    <div class="col-lg-6 text-white showcase-img" style="background-image: url('assets/img/bg-showcase-2.jpg')"></div>
                    <div class="col-lg-6 my-auto showcase-text">
                        <h2>Database</h2>
                        <p class="lead mb-0">今までに入力されたデータは特定の規約の元公開されています。<br><a href="/~deepsports/database/">こちら</a>のページをご確認ください。</p>
                    </div>
                </div>
                <div class="row g-0">
                    <div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image: url('assets/img/bg-showcase-3.jpg')"></div>
                    <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                        <h2>DeepSports</h2>
                        <p class="lead mb-0">Deepsportsはスポーツをデータサイエンスの視点から紐解いていくことを目的とした研究プロジェクトです。<br>こちらのページで成果が見れます。</p>
                    </div>
                </div>
            </div>
        </section>
        <?php include("../../app/_parts/_footer.php")?>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
        <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
    </body>





