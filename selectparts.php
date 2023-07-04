<?php
    require('./app/functions.php');
    define('movieDir', './app/_data/');
    $movieAll=glob(movieDir."*",GLOB_ONLYDIR);
    include('../../app/_parts/_header.php');
?>
<div style="padding:10px auto;">
    <form action="../selectTake/" method="post" style="text-align:center;margin:0 auto;">
        <label for="data-select">どのプレイヤーと部位を入力しますか</label>
        <select name="personName" id="data-select" required>
            <option value="">プレイヤーを選択</option>

            <?php foreach ($movieAll as $movie):?>
                <option value= <?= h(basename($movie))?>>
                    <?= h(basename($movie))?>
                </option>
            <?php endforeach;?>
        </select>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">

$(function() {

  $('button#add').click(function(){

  var tr_form = '' +
  '<tr>' +
    '<td><input type="text" name="parts_Body[]"></td>' +
  '</tr>';

  $(tr_form).appendTo($('table > tbody'));

});
    $('button#delete').click(function(){
        $('tr:last').remove();
    });
});
</script>
    <table style="text-align:center;margin:0 auto;">
        <thead>
            <tr>
            <th>体の部位</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td><input type="text" name="parts_Body[]"></td>
            </tr>
        </tbody>
    </table>
    <button id="add" type="button">追加</button>
    <button id="delete" type="button">削除</button>
    <input type="submit" name="send" value="送信">
</form>
</div>
<?php include('../../app/_parts/_footer.php'); ?>