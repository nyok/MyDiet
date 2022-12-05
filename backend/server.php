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
        echo '<ul class="calendar">';
        echo '<li><a href="'.URL.'/days/'.formatDate($today, 'Y/M/D').'/">'.formatDate($today, 'day').'</a></li>';
        $days = getCalendarDay();
        foreach ($days as $day) {
            echo '<li><a href="'.URL.'/days/'.formatDate($day, 'Y/M/D').'/">'.formatDate($day, 'day').'</a></li>';
        }
        echo '</ul>';

    echo '</div>';
}

/* Day page */
function page__days_post($id) {
    $day = $id;
    echo '<div class="page indent">';
    echo '<h1>'.formatDate($day, 'day').'</h1>';
    $weight = round(getWeight($day),2);
    echo '<h2>'.$weight.' '.text__kg.'</h2>';
    $meal = getInfo($day);
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

            $sum_proteins = 0;
            $sum_fats = 0;
            $sum_carbohydrates = 0;
            $sum_calories = 0;

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
                $sum_proteins += $p['proteins'];
                $sum_fats += $p['fats'];
                $sum_carbohydrates += $p['carbohydrates'];
                $sum_calories += $p['calories'];
            }

            $max_proteins = formatNum(($weight*2),1);
            $max_fats = formatNum(($weight*1.5),1);
            $max_carbohydrates = formatNum(($weight*2),1);
            $max_calories = 1500;

            $total_proteins = formatNum($sum_proteins,1);
            $total_fats = formatNum($sum_fats,1);
            $total_carbohydrates = formatNum($sum_carbohydrates,1);
            $total_calories = formatNum($sum_calories,0);

            $balance_proteins = formatNum($max_proteins - $total_proteins);
            $balance_fats = formatNum($max_fats - $total_fats);
            $balance_carbohydrates = formatNum($max_carbohydrates - $total_carbohydrates);
            $balance_calories = formatNum(1500 - $total_calories);

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
                echo '<td class="r">'.$sum_proteins.'</td>';
                echo '<td class="r">'.$sum_fats.'</td>';
                echo '<td class="r">'.$sum_carbohydrates.'</td>';
                echo '<td class="r">'.$sum_calories.'</td>';
            echo '</tr>';

            echo '<tr class="balance">';
                echo '<td>'.text__left.'</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td class="r">'.printGraph($max_proteins,$sum_proteins,$balance_proteins).'</td>';
                echo '<td class="r">'.printGraph($max_fats,$sum_fats,$balance_fats).'</td>';
                echo '<td class="r">'.printGraph($max_carbohydrates,$sum_carbohydrates,$balance_carbohydrates).'</td>';
                echo '<td class="r">'.printGraph($max_calories,$sum_calories,$balance_calories).'</td>';
            echo '</tr>';

            echo '</table>';

        echo '</div>';
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
	    $month = $month_name[ date( 'n',$time ) ];
    } else {
        $month = date( 'F', $time );
    }
	$month_number = date( 'm',$time );
	$day_with_zero = date( 'd',$time );
    $day = date( 'j',$time );
	$year = date( 'Y',$time );
	$hour = date( 'H',$time );
	$minute = date( 'i',$time );
	$iso8601 = date( 'c',$time );
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
function formatNum($num, $round='2') {
    $number = number_format($num,$round,'.', '');
    $rounded = (!is_int($number)) ? round($number, 1) : round($number);
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
    'SELECT * FROM `weight` WHERE DATE(`weight`.`datetime`) <= "'.$day.'"
    ORDER by `weight`.`datetime` DESC
    LIMIT 1';
    $mysqli = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);
    mysqli_set_charset($mysqli, 'utf8mb4');
    $result = mysqli_query($mysqli, $query);
    $arr = NULL;
    if(!$result || mysqli_num_rows($result) == 0) { /*return NULL;*/ }
    else {
        while($r = $result->fetch_array(MYSQLI_ASSOC)) {
            $id = $r['id'];
            $weight = $r['weight'];
            if($id) {
                $arr = $weight;
            }
        }
    }
    return $arr;
}

/* Getting info for day */
function getInfo($day) {
    $query =
    'SELECT * FROM `meal`
    LEFT JOIN `products` ON (`meal`.`product_id` = `products`.`id`)
    WHERE DATE(`meal`.`datetime`) = "'.$day.'"
    ORDER BY `meal`.`datetime` ASC';

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
            $proteins = formatNum(proportionality($r['proteins'],$portion),1);
            $fats = formatNum(proportionality($r['fats'],$portion),1);
            $carbohydrates = formatNum(proportionality($r['carbohydrates'],$portion),1);
            $calories = formatNum(proportionality($r['calories'],$portion),0);
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

/* Getting a list of diet days */
function getCalendarDay() {
    $query =
    'SELECT DATE(`meal`.`datetime`) as `date` FROM `meal`
    WHERE DATE(`meal`.`datetime`) != DATE(NOW())
    GROUP BY DATE(`meal`.`datetime`)
    ORDER BY DATE(`meal`.`datetime`) DESC';

    $mysqli = mysqli_connect(DBSERVER, DBUSERNAME, DBPASSWORD, DBNAME);
    mysqli_set_charset($mysqli, 'utf8mb4');
    $result = mysqli_query($mysqli, $query);
    $arr = array();
    if(!$result || mysqli_num_rows($result) == 0) { /*return NULL;*/ }
    else {
        while($r = $result->fetch_array(MYSQLI_ASSOC)) {
            $date = $r['date'];
            if($date) {
                $arr[] = $date;
            }
        }
    }
    return $arr;
}
