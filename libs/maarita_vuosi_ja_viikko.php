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
    $viikko_options = array("options" => array("min_range" => 1, "max_range" => 52));
    $valid_viikko = filter_var($_GET['viikko'], FILTER_VALIDATE_INT, $viikko_options);

    if ($valid_viikko) {
        $viikko = $valid_viikko;
    } else {
        header('Location: index.php');
        exit();
    }
} else {
    $viikko = intval(date("W"));
}
?>
