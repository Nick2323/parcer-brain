<?php

echo '<html xmlns="http://www.w3.org/1999/xhtml" lang="ru"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style>
    body { 
     font-family: monospace;
	 font-size: 11px;
    }
    .letter-red { 
     color: #b61039;
    }
	.letter-green { 
     color: #39892f;
    }
	</style> </head><body>';

ini_set("max_execution_time", "600000000");

$mysqli = new mysqli("localhost", "root", "", "brain");
$mysqli->set_charset("utf8");
$mysqli->query("SET NAMES 'utf8'");
if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}
require('phpQuery.php');

//ПАРСИНГ БРЕЙНА В ТАБЛИЦЮ brain_base

for ($i=39000;$i<=40000;$i++) {
    $get_json__ = file_get_contents('http://brain.com.ua/product_list?page='.$i);
    $a_j = json_decode($get_json__, true);
    echo '--- PAGE --- '.$i.' --- <br />';
    foreach ($a_j['products'] as $prod__) {


         $sel1___ = $mysqli->query('SELECT 1 FROM brain_base where ProductID = '.$prod__['ProductID']);
        $res1___ = $sel1___->fetch_assoc();
        if ($res1___['1'] == 1) {
            //echo '<span class="letter-red">'.$prod__['ProductCode'].'</span><br />';
        }
        else {

            echo '<span class="letter-green">'.$prod__['ProductCode'].'</span><br />';
            $NameRu = $mysqli->real_escape_string(trim($prod__['NameRu']));
            $NameUa = $mysqli->real_escape_string(trim($prod__['NameUa']));
            $BriefDescriptionRu = $mysqli->real_escape_string(trim($prod__['BriefDescriptionRu']));
            $BriefDescriptionUa = $mysqli->real_escape_string(trim($prod__['BriefDescriptionUa']));
            $ins0___ = $mysqli->query("INSERT INTO brain_base
 ('', ".(int)$prod__['ProductID'].", '".$NameRu."', '".$NameUa."', '',  '".$prod__['Slug'].
                "', '".$prod__['SlugUa']."', '".$BriefDescriptionRu."', '".$BriefDescriptionUa."', ".(int)$prod__['ProductGroupID'].", '".$prod__['ProductCode']."', 0, now());");
        }
//        var_dump($NameRu);
//        echo "<br>";
//        var_dump($NameUa);
//        echo "<br>";
//        var_dump($BriefDescriptionRu);
//        echo "<br>";
//        var_dump($BriefDescriptionUa);
    }

}
echo ' <audio controls="controls" autoplay><source src="Lazer.mp3" type="audio/mpeg"></audio>';


//id<=82000 and id>=81000
//id<=52000 and id>=51000
//id<=81000 and id>=80000
//(id<=74000 and id>=73580)
////id<1 and id>=1000
//(id <= 3000 and id >= 1000)
//    (id <= 5000 and id >= 3000)
//        (id <= 7000 and id >= 5000)
//            (id <= 9000 and id >= 7000)
//                (id <= 12000 and id >= 9000)
//                (id <= 17000 and id >= 12000)
//                (id <= 20000 and id >= 17000)
//                (id <= 25000 and id >= 20000)
//ПАРСИНГ БРЕЙНА В ІНШІ ТАБЛИЦІ
$sel3___ = $mysqli->query('SELECT id FROM brain_base WHERE (id <= 25000 and id >= 20000)');
//$sel3___ = $mysqli->query('SELECT id FROM brain_base WHERE id=3022');

//(select brain_images.id_base from brain_images group by brain_images.id_base) and brain_base.id>95000 and brain_base.id<=100000');
//z``
//var_dump($sel3___);
while ($row = $sel3___->fetch_assoc()) {

    brain_insert($row['id'], $mysqli) . '<br />';
    //echo $row['id'].'<br />';
}
echo ' <audio controls="controls" autoplay><source src="Lazer.mp3" type="audio/mpeg"></audio>';


function brain_insert($ProductID, $m)
{

    $sel1___ = $m->query('select brain_base.Slug from brain_base where brain_base.id=' . $ProductID);
    $res1___ = $sel1___->fetch_assoc();
    "http://brain.com.ua/" . $res1___['Slug'] . " .html";
    $html_item = file_get_contents('http://brain.com.ua/' . $res1___['Slug'] . '.html');
    phpQuery::newDocument($html_item);
    $CatImg = pq('div.clear_fix ul.product_slider li');

    foreach ($CatImg as $CI) {
        $Item['Images_brain'][] = pq($CI)->find('img')->attr('src');

        $Item['Images_local'][] = copy___(pq($CI)->find('img')->attr('src'));
//        echo "<br>";
//        echo 1;
//        var_dump($Item['Images_local']);
//        var_dump($Item['Images_local']);


    }
    if (isset($Item['Images_brain'])) {
         $CountProductImages = count($Item['Images_brain']);
        for ($cnt_img = 0; $cnt_img < $CountProductImages; $cnt_img++) {
            $ItemI[$cnt_img]['Images_brain'] = $Item['Images_brain'][$cnt_img];
            $ItemI[$cnt_img]['Images_local'] = $Item['Images_local'][$cnt_img];
        }
        $Item['Images'] = $ItemI;
    }
    $DescItem = pq('.description_wrapper');

    $Item['FullDescription'] = $m->real_escape_string(trim(pq($DescItem)->html()));
    $Item['FullDescription'];
    $ItemSpec = pq('.table div.row');
    foreach ($ItemSpec as $IS) {

        $AttName[] = pq($IS)->find('div')->text();
        $AttValue[] = pq($IS)->find('.text_right')->text();
    }
    $CountAttributerReturn = count($AttName);
    for ($cnt_aa = 0; $cnt_aa < $CountAttributerReturn; $cnt_aa++) {
        $AttributerR[$cnt_aa]['name'] = $m->real_escape_string(trim($AttName[$cnt_aa]));
        $AttributerR[$cnt_aa]['value'] = $m->real_escape_string(trim($AttValue[$cnt_aa]));
    }
    $Item['Attributes'] = $AttributerR;

    $upd0___ = $m->query("UPDATE brain_base SET FullDescription = '" . $Item['FullDescription'] . "' WHERE id = " . $ProductID);

    foreach ($Item['Images'] as $II) {
        $ins0___ = $m->query("INSERT INTO brain_images VALUES ('', " . $ProductID . ", '" . $II['Images_brain'] . "', '" . $II['Images_local'] . "')");
    }
    foreach ($Item['Attributes'] as $IA) {/// puth in value in name product tovar
        $ins1___ = $m->query("INSERT INTO brain_attributes VALUES ('', " . $ProductID . ", '" . $IA['name'] . "', '" . $IA['value'] . "')");
    }
    $upd1___ = $m->query("UPDATE brain_base SET i = 1 WHERE id = " . $ProductID);
    return (1);

}


function copy___($filename)
{
    $md5_first = substr(md5($filename), 0, 2);// return name to simvol
    if (!is_dir('c:/xampp/htdocs/parcer/amages_brain/' . $md5_first . '/')) {
        mkdir('c:/xampp/htdocs/parcer/amages_brain/' . $md5_first . '/');
    }
    $new_name = 'c:/xampp/htdocs/parcer/amages_brain/' . $md5_first . '/' . md5($filename) . '.' . getExtension($filename);
    if (file_exists($new_name)) {// file now resurs
    } else {
        if (remote_file_exists($filename) == 1) {


            copy($filename, $new_name);
        } else {
            return 'amages_brain/no_image.jpg';
        }
    }
    return substr($new_name, 23);
}

function getExtension($filename)
{

    return end(explode(".", $filename));
}

function remote_file_exists($url)
{

    $headers = get_headers($url);
    if (preg_match("|200|", $headers[0])) {
        return 1;
    } else {
        return 0;
    }
}


$mysqli->close();
echo '</body></html>';
?>
