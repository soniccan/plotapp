<?php
    $fileName = "golfDB20220809.json";
    $filePath ="../../../app/".$fileName;
    $json = file_get_contents($filePath);
    // var_dump($json);
    $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    $dbArr = json_decode($json,true);
    $dbArrDataArr = $dbArr;
    $json_parts = file_get_contents("/home/deepsports/public_html/plotapp/plot-config.json");
    $json_parts = mb_convert_encoding($json_parts, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    $arr_parts = json_decode($json_parts,true);
    $parts = $arr_parts['parts'];
    $japaneseName = $arr_parts['japanese-name'];
?>
    <form  action="../youtubeJsonForm/formdata/" id="contactForm" method="post">
        <label class ="mb-3" for="data-select">どの動画を入力しますか？</label>
        <select class ="form-select mb-3" name="jsonIndex" id="data-select" required>
            <option value="">プレイヤーを選択</option>
            <?php foreach ($dbArrDataArr as $index =>$dbArrData): ?>
                <option value= <?= $index ?>>
                    <?= $index."  (".$dbArrData['youtube_id'].")" ?>
                </option>

            <?php endforeach;?>
        </select>
        <div class="mb-5">
            <?php foreach ($parts as $i =>$part):?>
                <input type="checkbox" id=<?= $part ?> name="parts_Body[]" value=<?=$part?> checked>
                <label for=<?=$part?>><?=$japaneseName[$i]?></label>
            <?php endforeach;?>
        </div>
        <input type="hidden" name="fileName" value=<?=$fileName?>>
        <button class ="btn btn-primary ml-5" type="submit">プロットする</button>
    </form>