<?php

require_once 'sdbh.php';


$days = $_REQUEST['days'];
$request = $_REQUEST;

$num1 = filter_input(INPUT_POST, 'days', FILTER_VALIDATE_INT);

$sir_arr = array();

$db_connection = new sdbh();
$services = unserialize($db_connection->mselect_rows('a25_settings', ['set_key' => 'services'], 0, 1, 'id')[0]['set_value']);
$product_info = $db_connection->get_info('a25_products', $_REQUEST['product']);
$ass_arr = array_keys($services);

foreach ($product_info as $product) {
    $final_sum = 0;
    if (!$product['TARIFF']) {
        $final_sum = $days * $product['PRICE'];
    } else {
        $tariff_arr = unserialize($product['TARIFF']);
        end($tariff_arr);
        if ($days >= key($tariff_arr)) {
            $final_sum = $days * $tariff_arr[key($tariff_arr)];
        } else {
            reset($tariff_arr);
            while (current($tariff_arr)) {
                if (key($tariff_arr) > $days) {
                    prev($tariff_arr);
                    $final_sum = $days * $tariff_arr[key($tariff_arr)];
                    break;
                }
                next($tariff_arr);
            }
        }
    }
    if (isset($_REQUEST['service'])) {
        foreach ($_REQUEST['service'] as $key => $a) {
            $index = array_search($key, array_keys($ass_arr));
            $value = $ass_arr[$index];
            $sir_arr[] = $value;
        }
        $flippedArray = array_flip($sir_arr);
        $result = array_intersect_key($services, $flippedArray);
        $sum = 0;
        foreach ($result as $value) {
            $sum += $value;
        }
        $final_sum = $final_sum + $sum * $days;
    }
    echo json_encode(array('success' => $final_sum));
}







