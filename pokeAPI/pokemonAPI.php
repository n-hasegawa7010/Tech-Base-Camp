<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pokemonAPI.css">
    <title>PokemonAPI_Test</title>
</head>

<body>

<header>
    <h1>ポケモン図鑑
        <?php
            $res_pika = getCacheContents("https://pokeapi.co/api/v2/pokemon/25/", "./cache/pokeAPI_caches_pikachu");
            $data_pika = json_decode($res_pika,true);
            echo "<img src={$data_pika['sprites']['front_default']} alt='ピカチュウ画像_表'";
        ?>
    </h1>
</header>
<?php

// file_get_contentsの結果をキャッシュしつつ返す
function getCacheContents($url, $cachePath, $cacheLimit = 86400) {
    if(file_exists($cachePath) && filemtime($cachePath) + $cacheLimit > time()) {
    // キャッシュ有効期間内なのでキャッシュの内容を返す
    return file_get_contents($cachePath);
} else {
    // キャッシュがないか、期限切れなので取得しなおす
    $data = file_get_contents($url);
    file_put_contents($cachePath, $data, LOCK_EX); // キャッシュに保存
    return $data;
}
}


// 取得結果をループさせてポケモンの名前を表示する
if(!isset($_POST["sel_page"])) {
    $sel_page = 1;
} else {
    $sel_page = $_POST["sel_page"];
}

if(!isset($_POST["sel_onepage"])) {
    $one_page = 10; // 1ページに表示するポケモンの数
} else {
    $one_page = $_POST["sel_onepage"];
}

$limit = 200; // 表示するポケモンの最大数
$page = $limit / $one_page; # ページ数を取得
$page = ceil($page); # 整数に直す。
$now_page = ($sel_page - 1) * $one_page; # OFFSET を取得 ページ数 -1 * 20

function getPokeAPIItems() {
    global $one_page;
    global $now_page;
    global $sel_page;
    $res = getCacheContents("https://pokeapi.co/api/v2/pokemon/?limit={$one_page}&offset={$now_page}", "./cache/pokeAPI_caches_{$one_page}_{$now_page}_{$sel_page}page");
    return json_decode($res,true);
}

echo "<div class='paging'>";
// echo $sel_page;
for($i = 1; $i <= $page; $i++) {
    echo "
    <form action='pokemonAPI.php' method='post'>
        <input type='hidden' name='sel_page' value='{$i}'>
        <input type='hidden' name='sel_onepage' value='{$one_page}'>
        <input type='submit' class='page_btn' value='{$i}' class='paging'>
    </form>
    ";
}
echo "</div>";

echo <<< _ONEPAGE_
<div class="select_onepage">
    <form action="pokemonAPI.php" method="post">
        <select name="sel_onepage">
            <option value="{$one_page}">現在 {$one_page}匹 表示</option>
            <option value="10">10匹 表示</option>
            <option value="20">20匹 表示</option>
            <option value="50">50匹 表示</option>
        </select>
        <input type="submit" value="変更">
    </form>
</div>
_ONEPAGE_;

/** PokeAPI のデータを取得する(id=1から10のポケモンのデータ) */
$data = getPokeAPIItems();
foreach($data['results'] as $key => $value){
    // 詳細のurl取得
    $res_detail = getCacheContents("{$value['url']}", "./cache/pokeAPI_caches_detail_{$value['name']}");
    $data_detail = json_decode($res_detail,true);

    // speciesのurl取得
    $url_species = "https://pokeapi.co/api/v2/pokemon-species/{$data_detail['id']}/";
    $response_species = file_get_contents($url_species);
    $data_species = json_decode($response_species, true);

    $res_species = getCacheContents("https://pokeapi.co/api/v2/pokemon-species/{$data_detail['id']}/", "./cache/pokeAPI_caches_species_{$data_detail['id']}");
    $data_species = json_decode($res_species,true);

    echo '<div class = "poke_data">';
        // 裏面のコンテンツ
        echo '<div class="back">';
            echo "<br>";
            echo '<div class = "poke_img">';
                echo "<img src={$data_detail['sprites']['front_default']} alt='ポケモン画像_表'"."<br>"; // デフォルト正面
                echo "<br>";
            echo '</div>';

            echo '<div class = "button">';
                echo '<input type = "button" class = "btnA"></p>';
                echo '<input type = "button" class = "btnB"></p>';
            echo '</div>';

            echo '<div class = "line">';
                echo '<p class="lineA"></p>';
                echo '<p class="lineB"></p>';
                echo '<p class="lineC"></p>';
                echo '<p class="lineD"></p>';
            echo '</div>';

            // ポケモン説明：なまえ、タイプ、おもさ、たかさ
            echo '<div class = "poke_ex">';
                // 名前
                echo "<p class = 'poke_name'>";
                    echo "No."."{$data_detail['id']}"."<br>"; // ポケモン_id
                    echo "{$data_species['names']['0']['name']}"; // 日本語のなまえ
                    // " ({$value['name']})". //英語のなまえ
                echo "</p>";

                // タイプ
                echo "<p>タイプ：";
                foreach($data_detail['types'] as $key2 => $poke_type){
                    switch ($poke_type['type']['name']) {
                        case "normal":
                            echo "ノーマル ";
                            break;
                        case "fire":
                            echo "ほのお ";
                            break;
                        case "water":
                            echo "みず ";
                            break;
                        case "grass":
                            echo "くさ ";
                            break;
                        case "flying":
                            echo "ひこう ";
                            break;
                        case "electric":
                            echo "でんき ";
                            break;
                        case "ice":
                            echo "こおり ";
                            break;
                        case "fighting":
                            echo "かくとう ";
                            break;
                        case "poison":
                            echo "どく ";
                            break;
                        case "ground":
                            echo "じめん ";
                            break;
                        case "psychic":
                            echo "エスパー ";
                            break;
                        case "bug":
                            echo "むし ";
                            break;
                        case "rock":
                            echo "いわ ";
                            break;
                        case "ghost":
                            echo "ゴースト ";
                            break;
                        case "dark":
                            echo "あく ";
                            break;
                        case "steel":
                            echo "はがね ";
                            break;
                        case "fairy":
                            echo "フェアリー ";
                            break;
                }
            }
                echo "</p>";

                // たかさ
                $height = $data_detail['height'] / 10;
                echo "<p>たかさ：{$height} m</p>";
                
                // おもさ
                $weight = $data_detail['weight'] / 10;
                echo "<p>おもさ：{$weight} kg</p>";
            echo '</div>';
        echo '</div>';

        // 表面のコンテンツ
        echo '<div class="front">';
            echo "<br>";
            echo '<div class = "poke_img">';
                echo "<img src={$data_detail['sprites']['back_default']} alt='ポケモン画像_裏'"."<br>"; // デフォルト正面
                echo "<br>";
            echo '</div>';

            echo '<div class = "Exp">';
                if(!isset($data_species['flavor_text_entries']['72']['flavor_text'])){
                    echo "Sorry Not Found.";
                }else{
                    echo "{$data_species['flavor_text_entries']['72']['flavor_text']}";
                }
            echo '</div>';

            echo '<div class = "battery">';
                echo '<div class = "pull"></div>';
                echo '<div class = "battery_box"></div>';
            echo '</div>';
        echo '</div>';
    echo '</div>';
}


?>

</body>

<footer>

</footer>

</html>