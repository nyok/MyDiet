<?php

require_once('../backend/settings.php');

/* Pages */

/* Header */
function page__block_header() {
    echo '<!DOCTYPE html>
    <html>
    <head>
    <title>'.text__appname.'</title>
    <link rel="shortcut icon" type="image/x-icon" href="'.URL.'/favicon.ico">
    <meta name="robots" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="'.URL.'/css/style.css?'.Rand().'">
    </head>
    <body>';
}

/* Footer */
function page__block_footer() {
    echo '<div class="footer indent">' . Date('Y') . '</div>';
    echo '</body></html>';
}

/* Menu */
function page__block_menu() {
    echo '<div class="menu">';
        echo '<div class="indent">';
            echo ($_SERVER['REQUEST_URI'] == "/") ? '<span class="title">'.randomFoodEmoji().' '.text__appname.'</span>' : '<span class="title">'.randomFoodEmoji().' <a class="thin-black" href="'.URL.'">'.text__appname.'</a></span>';
        echo '</div>';
    echo '</div>';
}

/* Index page */
function page__index() {
    echo '<div class="page indent">';
        $today = date("Y-m-d H:i:s");
        echo '<h1>'.text__today.' '.formatDate($today, 'day').'</h1>';
        $weight = round(getWeight($today),2);
        echo '<h2>'.text__current_weight.': ' . $weight . ' '.text__kg.'</h2>';
        echo '<h2>'.text__calendar.'</h2>';
        if(!existTodayMeal()) {
            echo '<div class="meal-today">
            <p><a href="'.URL.'/days/'.formatDate($today, 'Y/M/D').'/">'.formatDate($today, 'day').'</a></p>
            <p>'.text__not_data.'</p>
            </div>';
        };
        block__results_week();
    echo '</div>';
}

/* Day page */
function page__days_day($id) {
    $day = $id;
    $weight = round(getWeight($day),2);
    $meal = getInfo($day);
    $results = getInfoResults($day, null)[0];
    echo '<div class="page indent">';
    echo '<h1>'.formatDate($day, 'day').'</h1>';
    echo '<h2>'.$weight.' '.text__kg.'</h2>';
        echo '<div class="meal">';
            echo '<table class="meal-table">';
                echo '<tr>';
                    echo '<th class="l"></th>';
                    echo '<th class="l" width="250"></th>';
                    echo '<th class="l" width="86">'.text__portion.'</th>';
                    echo '<th class="r" width="86">'.text__proteins.'</th>';
                    echo '<th class="r" width="86">'.text__fats.'</th>';
                    echo '<th class="r" width="86">'.text__carbohydrates.'</th>';
                    echo '<th class="r" width="86">'.text__calories.'</th>';
                echo '</tr>';

            foreach ($meal as $p) {
                echo '<tr>';
                echo '<td>'.formatDate($p['datetime'],'time').'</td>';
                echo '<td>'.$p['title'].'</td>';
                echo '<td>'.$p['portion'].'</td>';
                echo '<td class="r">'.$p['proteins'].'</td>';
                echo '<td class="r">'.$p['fats'].'</td>';
                echo '<td class="r">'.$p['carbohydrates'].'</td>';
                echo '<td class="r">'.$p['calories'].'</td>';
                echo '</tr>';
            }

            $max_proteins = $results['max_proteins'];
            $max_fats = $results['max_fats'];
            $max_carbohydrates = $results['max_carbohydrates'];
            $max_calories = $results['max_calories'];

            $total_proteins = $results['total_proteins'];
            $total_fats = $results['total_fats'];
            $total_carbohydrates = $results['total_carbohydrates'];
            $total_calories = $results['total_calories'];

            $balance_proteins = $results['balance_proteins'];
            $balance_fats = $results['balance_fats'];
            $balance_carbohydrates = $results['balance_carbohydrates'];
            $balance_calories = $results['balance_calories'];

            echo '<tr class="max">';
                echo '<td>'.text__maximum.'</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td class="r">'.$max_proteins.'</td>';
                echo '<td class="r">'.$max_fats.'</td>';
                echo '<td class="r">'.$max_carbohydrates.'</td>';
                echo '<td class="r">'.$max_calories.'</td>';
            echo '</tr>';

            echo '<tr class="total">';
                echo '<td>'.text__today.'</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td class="r">'.$total_proteins.'</td>';
                echo '<td class="r">'.$total_fats.'</td>';
                echo '<td class="r">'.$total_carbohydrates.'</td>';
                echo '<td class="r">'.$total_calories.'</td>';
            echo '</tr>';

            echo '<tr class="balance">';
                echo '<td>'.text__left.'</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td class="r">'.printGraph($max_proteins, $total_proteins, $balance_proteins).'</td>';
                echo '<td class="r">'.printGraph($max_fats, $total_fats, $balance_fats).'</td>';
                echo '<td class="r">'.printGraph($max_carbohydrates, $total_carbohydrates, $balance_carbohydrates).'</td>';
                echo '<td class="r">'.printGraph($max_calories, $total_calories, $balance_calories).'</td>';
            echo '</tr>';

            echo '</table>';

        echo '</div>';
    echo '</div>';
}

/* Block results week */
function block__results_week() {
    $day = date("Y/m/d");
    $week = date("Y/m/d", strtotime($day."-7 day"));
    $results = getInfoResults($week, $day);
    echo '<div class="meal">';
        echo '<table class="meal-table">';
            echo '<tr>';
                echo '<th class="l"></th>';
                echo '<th class="r" width="86">'.text__proteins.'</th>';
                echo '<th class="r" width="86">'.text__fats.'</th>';
                echo '<th class="r" width="86">'.text__carbohydrates.'</th>';
                echo '<th class="r" width="86">'.text__calories.'</th>';
            echo '</tr>';

            foreach ($results as $r) {
                $href = $r['href'];
                $max_proteins = $r['max_proteins'];
                $max_fats = $r['max_fats'];
                $max_carbohydrates = $r['max_carbohydrates'];
                $max_calories = $r['max_calories'];

                $total_proteins = $r['total_proteins'];
                $total_fats = $r['total_fats'];
                $total_carbohydrates = $r['total_carbohydrates'];
                $total_calories = $r['total_calories'];

                $balance_proteins = $r['balance_proteins'];
                $balance_fats = $r['balance_fats'];
                $balance_carbohydrates = $r['balance_carbohydrates'];
                $balance_calories = $r['balance_calories'];

                echo '<tr class="">';
                    echo '<td><a href="'.$href.'">'.$r['date'].'</a></td>';
                    echo '<td class="r">'.printGraph($max_proteins, $total_proteins, $balance_proteins).'</td>';
                    echo '<td class="r">'.printGraph($max_fats, $total_fats, $balance_fats).'</td>';
                    echo '<td class="r">'.printGraph($max_carbohydrates, $total_carbohydrates, $balance_carbohydrates).'</td>';
                    echo '<td class="r">'.printGraph($max_calories, $total_calories, $balance_calories).'</td>';
                echo '</tr>';
            }
        echo '</table>';
    echo '</div>';
}
/* Scripts */

/* Random food emoji */
function randomFoodEmoji() {
    $num = mt_rand(344,373);
    return '&#x1F'.$num.';';
}

/* Formating date */
function formatDate($date, $format) {
    $time = strtotime($date);
    if(LANGUAGE == 'ru') {
        $month_name = array( 1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля', 5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа', 9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря');
        $month = $month_name[ date( 'n', $time ) ];
    } else {
        $month = date( 'F', $time );
    }
    $month_number = date( 'm', $time );
    $day_with_zero = date( 'd', $time );
    $day = date( 'j', $time );
    $year = date( 'Y', $time );
    $hour = date( 'H', $time );
    $minute = date( 'i', $time );
    $iso8601 = date( 'c', $time );
    switch ($format) {
        case 'Y/M/D':
            $date = $year."/".$month_number."/".$day_with_zero;
        break;
        case 'Y-M-D':
            $date = $year."-".$month_number."-".$day;
        break;
        case 'iso8601':
            $date = $iso8601;
        break;
        case 'day':
            $date = (date('Y') != $year) ? "$day $month $year" : "$day $month";
        break;
        case 'time':
            $date = $hour . ':' . $minute;
        break;
        default:
            $date = "$day $month $year";
        break;
    }
    return $date;
}

/* Formating numbers */
function formatNum($num, $round) {
    $rounded = round($num, $round);
    return $rounded;
}

/* Getting the nutritional value based on the portion */
function proportionality($whole, $part) {
    return $whole * $part / 100;
}

/* Print result for balance row */
function printGraph($max, $sum, $balance) {
    $width = ($max > $sum) ? round($sum/$max*100,0) : '100';
    $color = ($width > 90) ? 'background-color:#ff9999;' : 'background-color:#999;' ;
    $balance = ($balance < 0) ? '<span style="color:#F00">'.$balance.'</span>' : '<span>'.$balance.'<span>';
    return $balance.'
    <div style="'.$color.' height:2px; margin-top:4px;">
    <div style="background-color:#DDD; height:2px; width:'.$width.'%; display:block"></div>
    </div>';
}

/* Getting weight for date */
function getWeight($day) {
    $query =
    'SELECT * FROM `weight`
    WHERE `weight`.`date` <= DATE_FORMAT("'.$day.'", "%y/%m/%d")
    ORDER by `weight`.`date` DESC
    LIMIT 1';
    $mysqli = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);
    mysqli_set_charset($mysqli, 'utf8mb4');
    $result = mysqli_query($mysqli, $query);
    $arr = NULL;
    if(!$result || mysqli_num_rows($result) == 0) { return 0; }
    else {
        $r = $result->fetch_array(MYSQLI_ASSOC);
        $id = $r['id'];
        $weight = $r['weight'];
        if($id) { $arr = $weight; }
    }
    return $arr;
}

/* Getting info for day */
function getInfo($day) {
    $query =
    'SELECT * FROM `meal`
    LEFT JOIN `products` ON (`meal`.`product_id` = `products`.`id`)
    WHERE DATE(`meal`.`datetime`) = DATE("'.$day.'") ORDER BY `meal`.`datetime` ASC';
    $mysqli = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);
    mysqli_set_charset($mysqli, 'utf8mb4');
    $result = mysqli_query($mysqli, $query);
    $arr = array();
    if(!$result || mysqli_num_rows($result) == 0) { /*return NULL;*/ }
    else {
        while($r = $result->fetch_array(MYSQLI_ASSOC)) {
            $id = $r['id'];
            $datetime = $r['datetime'];
            $title = $r['title'];
            $portion = $r['portion'];
            $proteins = formatNum(proportionality($r['proteins'], $portion),2);
            $fats = formatNum(proportionality($r['fats'], $portion),2);
            $carbohydrates = formatNum(proportionality($r['carbohydrates'], $portion),2);
            $calories = formatNum(proportionality($r['calories'], $portion),0);
            if($id) {
                $arr[] = array(
                    'id' => $id,
                    'datetime' => $datetime,
                    'title' => $title,
                    'portion' => $portion,
                    'proteins' => $proteins,
                    'fats' => $fats,
                    'carbohydrates' => $carbohydrates,
                    'calories' => $calories
                    );
            }
        }
    }
    return $arr;
}

/* Getting results info for day */
function getInfoResults($start_day, $end_day) {
    if($end_day) {
        $query = 'SELECT * FROM `results` WHERE `results`.`date` >= DATE("'.$start_day.'") AND `results`.`date` <= DATE("'.$end_day.'") ORDER BY `results`.`date` DESC';
    } else {
        $query = 'SELECT * FROM `results` WHERE `results`.`date` = DATE("'.$start_day.'")';
    }
    $mysqli = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);
    mysqli_set_charset($mysqli, 'utf8mb4');
    $result = mysqli_query($mysqli, $query);
    $arr = array();
    if(!$result || mysqli_num_rows($result) == 0) { /*return NULL;*/ }
    else {
        while($r = $result->fetch_array(MYSQLI_ASSOC)) {
            $id = $r['id'];
            $date = formatDate($r['date'], 'day');
            $href = URL.'/days/'.formatDate($r['date'], 'Y/M/D').'/';
            $max_proteins = formatNum($r['max_proteins'],2);
            $max_fats = formatNum($r['max_fats'],2);
            $max_carbohydrates = formatNum($r['max_carbohydrates'],2);
            $max_calories = formatNum($r['max_calories'],0);
            $total_proteins = formatNum($r['total_proteins'],2);
            $total_fats = formatNum($r['total_fats'],2);
            $total_carbohydrates = formatNum($r['total_carbohydrates'],2);
            $total_calories = formatNum($r['total_calories'],0);
            $balance_proteins = formatNum($r['balance_proteins'],2);
            $balance_fats = formatNum($r['balance_fats'],2);
            $balance_carbohydrates = formatNum($r['balance_carbohydrates'],2);
            $balance_calories = formatNum($r['balance_calories'],0);
            if($id) {
                $arr[] = array(
                    'date' => $date,
                    'href' => $href,
                    'max_proteins' => $max_proteins,
                    'max_fats' => $max_fats,
                    'max_carbohydrates' => $max_carbohydrates,
                    'max_calories' => $max_calories,
                    'total_proteins' => $total_proteins,
                    'total_fats' => $total_fats,
                    'total_carbohydrates' => $total_carbohydrates,
                    'total_calories' => $total_calories,
                    'balance_proteins' => $balance_proteins,
                    'balance_fats' => $balance_fats,
                    'balance_carbohydrates' => $balance_carbohydrates,
                    'balance_calories' => $balance_calories
                    );
            }
        }
    }
    return $arr;
}

/* Checking a record exists in a table "Meal" today */
function existTodayMeal() {
    $query = 'SELECT DATE(`meal`.`datetime`) as `date` FROM `meal` WHERE DATE(`meal`.`datetime`) = DATE(NOW()) LIMIT 1';
    $mysqli = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);
    mysqli_set_charset($mysqli, 'utf8mb4');
    $result = mysqli_query($mysqli, $query);
    $arr = array();
    if(!$result || mysqli_num_rows($result) == 0) { return false; } else { return true; }
}
