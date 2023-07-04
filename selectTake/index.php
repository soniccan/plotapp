<?php include('../../../app/_parts/_header.php'); ?>

<?php

require('../../../app/functions.php');

// createToken();
define('movieDir', '../app/_data/');
$movieAll=glob(movieDir."*",GLOB_ONLYDIR);

if($_SERVER['REQUEST_METHOD'] ==='POST')
{
//   validateToken();
    $personName =trim(filter_input(INPUT_POST,'personName'));
    $partsOfBody =filter_input(INPUT_POST,'parts_Body',FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    $takes=glob(movieDir.$personName."/"."*");
}else{
    header("Location: ../index.php");
    exit;
}
    echo $movieName;
    include('.././app/_parts/_header.php');
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
            <div class="col-xl-3">
                <div class="text-center text-white">

                    <p>あなたが選んだのは</p>
                    <ol class="list-group list-group-numbered text-center">
                        <?php foreach ($partsOfBody as $part):?>
                            <li class="list-group list-group-item bg-light "><?= h(basename($part))?></li>
                        <?php endforeach;?>
                    です。
                    </ol>

                    <form  action="../formdata/"  id="contactForm" method="post">
                        <!-- Email address input-->
                        <label class ="mb-3" for="data-select">どの動画を入力しますか？</label>

                            <select class ="form-select mb-3" name="movieName" id="data-select" required>
                                <option value="">プレイヤーを選択</option>

                                <?php foreach ($takes as $take):?>
                                    <option value= <?= h($take)?>>
                                        <?= h(basename($take))?>
                                    </option>
                                <?php endforeach;?>
                            </select>
                            <?php foreach ($partsOfBody as $part):?>
                                <input type="hidden" name="partsOfBody[]" value="<?= $part?>">
                            <?php endforeach;?>
                            <button class ="btn btn-primary ml-5" type="submit">プロットする</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</header>
<?php include('../../../app/_parts/_footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->

        <!-- * *                               SB Forms JS                               * *-->
        <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
        <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
