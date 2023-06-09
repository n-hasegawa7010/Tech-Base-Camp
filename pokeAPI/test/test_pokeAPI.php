<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./test_pokeAPI.css">
    <title>PokemonAPI_Test</title>
</head>

<body>

<header>
    <h1>ポケモン図鑑</h1>
</header>
<?php

// 取得結果をループさせてポケモンの名前を表示する
function view_poke(){
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

    $limit = 1; // 表示するポケモンの最大数
    $page = $limit / $one_page; # ページ数を取得
    $page = ceil($page); # 整数に直す。
    $now_page = ($sel_page - 1) * $one_page; # OFFSET を取得 ページ数 -1 * 20

    echo "<div class='paging'>";
    for($i = 1; $i <= $page; $i++) {
        echo "
        <form action='pokemonAPI.php' method='post'>
            <input type='hidden' name='sel_page' value='{$i}'>
            <input type='submit' class='page_btn' value='{$i}' class='paging'>
        </form>
        ";
    }
    echo "</div>";

    echo '
    <div class="select_onepage">
        <form action="pokemonAPI.php" method="post">
            <select name="sel_onepage">
                <option value="10">-</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
            <input type="submit" value="変更">
        </form>
    </div>
    ';

    /** PokeAPI のデータを取得する(id=1から10のポケモンのデータ) */
    $url = "https://pokeapi.co/api/v2/pokemon/?limit=1&offset=0";
    $response = file_get_contents($url);
    // レスポンスデータは JSON 形式なので、デコードして連想配列にする
    $data = json_decode($response, true);
    foreach($data['results'] as $key => $value){
        // 詳細のurl取得
        $response_detail = file_get_contents($value['url']);
        $data_detail = json_decode($response_detail, true);

        // speciesのurl取得
        $url_species = "https://pokeapi.co/api/v2/pokemon-species/1/";
        $response_species = file_get_contents($url_species);
        $data_species = json_decode($response_species, true);

        echo '<div class = "poke_data">';
            // 裏面のコンテンツ
            // echo '<div class="back">';
            //     echo "<br>";
            //     echo '<div class = "poke_img">';
            //         echo "<img src={$data_detail['sprites']['front_default']} alt='ポケモン画像_表'>"."<br>"; // デフォルト正面
            //         echo "<br>";
            //     echo '</div>';

                echo <<< _TEST_
                <div class = "checkbox">
                    <div class="back">
                    <br>
                    <div class = "poke_img">
                        <img src={$data_detail['sprites']['front_default']} alt="ポケモン画像_表"><br>
                    </div>

                    <input type="checkbox" name="push_btn" id="check_btn">
                    <label for="check_btn" class="btnA"><span></span></label>
                    <label for="check_btn" class="btnB"><span></span></label>
                    
                    <div class = "line">
                        <p class="lineA"></p>
                        <p class="lineB"></p>
                        <p class="lineC"></p>
                        <p class="lineD"></p>
                    </div>

                    <div class = "poke_ex">
                        <p class = 'poke_name'>
                            No.{$data_detail['id']}<br>
                            {$data_species['names']['0']['name']}
                        </p>
                _TEST_;

                echo "<p class = 'poke_type'>タイプ：";
                foreach($data_detail['types'] as $key2 => $poke_type){
                    echo $poke_type['type']['name']." ";
                }
                echo "</p>";

                echo <<< _TEST_
                    <p class = "poke_height">
                        たかさ：{$data_detail['height']} m
                    </p>
                    
                    <p class = "poke_weight">
                        おもさ：{$data_detail['weight']} kg</p>
                    </p>
                </div>
                
                
                _TEST_;

                // echo '<div class = "button">';
                //     echo '<input type = "button" class = "btnA"></p>';
                //     echo '<input type = "button" class = "btnB"></p>';
                // echo '</div>';

                // echo '<div class = "line">';
                //     echo '<p class="lineA"></p>';
                //     echo '<p class="lineB"></p>';
                //     echo '<p class="lineC"></p>';
                //     echo '<p class="lineD"></p>';
                // echo '</div>';

                // ポケモン説明：なまえ、タイプ、おもさ、たかさ
                // echo '<div class = "poke_ex">';
                    // // 名前
                    // echo "<p class = 'poke_name'>";
                    //     echo "No."."{$data_detail['id']}"."<br>"; // ポケモン_id
                    //     echo "{$data_species['names']['0']['name']}"; // 日本語のなまえ
                    //     // " ({$value['name']})". //英語のなまえ
                    // echo "</p>";

                    // タイプ
                    // echo "<p>タイプ：";
                    // foreach($data_detail['types'] as $key2 => $poke_type){
                    //     echo $poke_type['type']['name']." ";
                    // }
                    // echo "</p>";

                    // たかさ
            //         echo "<p>たかさ：".$data_detail['height']." m</p>";
                    
            //         // おもさ
            //         echo "<p>おもさ：".$data_detail['weight']." kg</p>";
            //     echo '</div>';
            // echo '</div>';

            // 表面のコンテンツ
            echo '<div class="front">';
                echo "<br>";
                echo '<div class = "poke_img">';
                    echo "<img src={$data_detail['sprites']['back_default']} alt='ポケモン画像_裏'"."<br>"; // デフォルト正面
                    echo "<br>";
                echo '</div>';

                echo '<div class = "Exp">';
                    echo "{$data_species['flavor_text_entries']['22']['flavor_text']}";
                echo '</div>';
            echo '</div>';
        echo '</div>';
    }
}
view_poke();


?>

</body>

<footer>

</footer>

</html>