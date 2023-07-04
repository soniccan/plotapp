<?php
    require('../../../app/functions.php');
    include('../../../app/_parts/_header.php');
?>
<head>
    <script> 
    var verifyCallback = function(response) { //コールバック関数の定義
        //#warning の p 要素のテキストを空にf
        document.getElementById("warning").textContent = '';
        //#send の button 要素の disabled 属性を解除
        document.getElementById("send").disabled = false;
    };
    var expiredCallback = function() { //コールバック関数の定義
        //#warning の p 要素のテキストに文字列を設定
        document.getElementById("warning").textContent = '送信するにはチェックを・・・';
        //#send の button 要素に disabled 属性を設定
        document.getElementById("send").disabled = true;
    };
    </script>
</head>
<div>
    <form action="./uploading/" method="post" enctype="multipart/form-data">
        <input type="file" name="fname[]" webkitdirectory>
        <input type="text/plain" name="dirName" >
        <div class="g-recaptcha" data-sitekey="6Lc7AHEgAAAAAD2gVed8WPf_1GaVZ5Wn-2AT8st3" data-callback="verifyCallback" data-expired-callback="expiredCallback"></div>
        <p id="warning">送信するにはチェックを入れてください。</p>
        <button id="send" type="submit" disabled>アップロード</button>
    </form>
</div>

<?php include('../app/_parts/_footer.php'); ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
