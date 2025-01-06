<?php
function randomString($length)
{
    $result = "";
    $temp = array_merge(range('a', 'z'), range('A', 'Z'), range('0', '9'));
    for ($ind = 1; $ind <= $length; $ind++) {
        $randomInd = rand(0, count($temp) - 1);
        $result .= $temp[$randomInd];
    }
    return $result;
}

function getStatusClass($status)
{
    $statusClass = '';
    if ($status == 'Published') {
        $statusClass = 'success';
    } else if ($status == 'Draft') {
        $statusClass = 'secondary';
    } else if ($status == 'Pending') {
        $statusClass = 'warning';
    }
    return $statusClass;
}

function renderFilterStatusCheckbox($statusConfig, $selectedArr)
{
    $htmlStatus = '';
    foreach ($statusConfig as $key => $value) {
        $mergeStatus = (in_array($key, $selectedArr) ? 'checked' : '');
        $htmlStatus .= sprintf('
                  <label class="form-check">
                    <input class="form-check-input" name="status[]" type="checkbox" value="%s" %s />
                    <span class="form-check-label">%s</span>
                  </label>', $key, $mergeStatus, $value['text']);
    }

    return $htmlStatus;
}
function renderFilterCategoryCheckbox($categoriesArray, $categoriesAll)
{
    $htmlCategories = '';
    foreach ($categoriesArray as $key => $value) {
        $mergeCategories = (in_array($value['id'], $categoriesAll) ? 'checked' : '');
        $htmlCategories .= '<label class="form-check">
                    <input class="form-check-input" name="categories[]" type="checkbox" value="' . $value['id'] . '" ' . $mergeCategories . '/>
                    <span class="form-check-label">' . $value['name'] . '</span>
                    </label>';
    }
    return $htmlCategories;
}

function readJsonToArray($jsonFile)
{
    $content = file_get_contents($jsonFile);
    $arr = json_decode($content, true);
    return $arr;
}
function putCategorySelect($filePath, $selectedValue)
{
    $htmlCategories = '';
    $categoriesArray = readJsonToArray($filePath);
    foreach ($categoriesArray as $key => $value) {
        $selected = $value['id'] == $selectedValue ? 'selected' : '';
        $htmlCategories .= '<option value="' . $value['id'] . '" ' . $selected . '>' . $value['name'] . '</option>';
    }
    return $htmlCategories;
}
function putStatusSelect($STATUS_CONFIG, $valueNotPostCheck)
{
    $htmlStatus = '';
    foreach ($STATUS_CONFIG as $key => $value) {
        $mergeStatus = (isset($_POST['status']) && $_POST['status'] == $key ? 'selected' : '');
        if (!isset($_POST['status'])) {
            $mergeStatus = ($valueNotPostCheck == $key ? 'selected' : '');
        }
        $htmlStatus .= '<option value="' . $key . '" ' . $mergeStatus . '>' . $value['text'] . '</option>';
    }
    return $htmlStatus;
}
function highlightKeywords($str, $keywords)
{
    $keywordsArray = explode(' ', strip_tags(trim($keywords)));

    foreach ($keywordsArray as $keyword) {
        $str = preg_replace("/($keyword)([\s\.\,])/i", "<mark>$1</mark>$2", $str);
    }
    return $str;
}
function inRangeQuestionAndAnswer($string, $left, $right)
{
    $length = strlen(trim($string));
    if ($length < $left || $length > $right) {
        return false;
    }
    return true;
}

function checkEmpty($string)
{
    return (trim($string) == '');
}



// RENDER AREA
function renderLabel($name = '') {
    return sprintf('<label class="form-label required">%s</label>', $name);
}

function renderSelect($name, $htmlOptions) {
    return sprintf('
        <select
            class="form-control form-select"
            required="required"
            name="%s"
            aria-required="true">
            %s
        </select>', $name, $htmlOptions);
} 

function renderInput($name, $value = '', $type = 'text') {
    return sprintf('<input type="%s" class="form-control" name="%s" value="%s" />', $type, $name, $value);
}


function renderTextarea($name, $value = '') {
    return sprintf('<textarea class="form-control" rows="4" name="%s" cols="50">%s</textarea>', $name, $value);
}