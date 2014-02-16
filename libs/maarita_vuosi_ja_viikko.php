<?php

if (isset($_GET['vuosi'])) {
    $vuosi_options = array("options" => array("min_range" => 1970, "max_range" => 2038));
    $valid_vuosi = filter_var($_GET['vuosi'], FILTER_VALIDATE_INT, $vuosi_options);

    if ($valid_vuosi) {
        $vuosi = $valid_vuosi;
    } else {
        header('Location: index.php');
        exit();
    }
} else {
    $vuosi = intval(date("Y"));
}

if (isset($_GET['viikko'])) {
    $viikko_options = array("options" => array("min_range" => 0, "max_range" => 53));
    $valid_viikko = filter_var($_GET['viikko'], FILTER_VALIDATE_INT, $viikko_options);

    if ($valid_viikko || $valid_viikko == 0) {
        $viikko = $valid_viikko;
    } else {
        header('Location: index.php');
        exit();
    }
} else {
    $viikko = intval(date("W"));
}

if ($viikko == 0) {
    $viikko = viikkojaVuodessa($vuosi-1);
    $vuosi--;
} else if ($viikko > viikkojaVuodessa($vuosi)) {
    $viikko = 1;
    $vuosi++;
}
?>
