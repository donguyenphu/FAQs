<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $FAQcontent = file_get_contents('./data/FAQs.json');
    $FAQarray = json_decode($FAQcontent, true);
    foreach ($FAQarray as $key => $value) {
        if ($value['id'] == $id) {
            unset($FAQarray[$key]);
            $FAQarray = array_values($FAQarray);
            $newInfo = json_encode($FAQarray, JSON_PRETTY_PRINT);
            if (file_put_contents('./data/FAQS.json', $newInfo)) {
                break;
            }
        }
    }
    header('Location: index.php');
    exit();
}
?>
