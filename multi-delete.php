<?php
require_once 'functions.php';
if (isset($_POST['clearAllIDs'])) {
    $FAQarray = readJsonToArray('./data/FAQs.json');
    foreach ($FAQarray as $key => $value) {
        unset($FAQarray[$key]);
    }
    $FAQarray = array_values($FAQarray);
    $convertBack = json_encode($FAQarray, JSON_PRETTY_PRINT);
    if (file_put_contents('./data/FAQs.json', $convertBack)) {
        header('Location: index.php');
        exit();
    }
}
else if (isset($_POST['ids'])) {
    $FAQarray = readJsonToArray('./data/FAQs.json');
    foreach ($FAQarray as $key => $value) {
        if (in_array($value['id'], $_POST['ids'])) {
            unset($FAQarray[$key]);
        }
    }
    $FAQarray = array_values($FAQarray);
    $convertBack = json_encode($FAQarray, JSON_PRETTY_PRINT);
    if (file_put_contents('./data/FAQs.json', $convertBack)) {
        header('Location: index.php');
        exit();
    }
}
