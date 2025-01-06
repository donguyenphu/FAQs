<?php
// require FAQManagement
// khởi tạo đối tượng FAQManagement(duong dan den file json)
/*
  form: category, status, question, answer
  Kiem tra form duoc submit hay chua
    -b1: lay du lieu $_POST
    -b2: Tao ra array chua tat ca thong tin mot faq: id, question, answer, status, categoryId
    -b3: Them noi dung moi vao FAQs.json
      3.1. Doc du lieu tu FAQs.json -> array
      3.2. Them phan tu moi (b2) vao array
      3.3. Chuyen array -> json
      3.4. Ghi noi dung json vao file FAQs.json
    -b4: chuyen huong ve index.php
  (-b4: trong index, render)
*/

// du lieu hop le
require_once 'functions.php';
require_once 'define.php';
$categoryId = '';
$status = '';
$question = '';
$answer = '';
$errorStatus = '';
$errorCategory = '';
$errorQuestion = '';
$errorAnswer = '';
if (isset($_POST['category_id']) && isset($_POST['status']) && isset($_POST['question']) && isset($_POST['answer'])) {
  $categoryId = intval($_POST['category_id']);
  $status = $_POST['status'];
  $question = $_POST['question'];
  $answer = $_POST['answer'];
  $newFaq = [
    'id' => randomString(5),
    'question' => $question,
    'answer' => $answer,
    'status' => $status,
    'categoryId' => $categoryId,
  ];
  // 10 - 255
  if (checkEmpty($question)) {
    $errorQuestion = 'empty';
  } else if (!inRangeQuestionAndAnswer($question, 10, 255)) {
    $errorQuestion = 'length';
  }
  // 25 - 500
  if (checkEmpty($answer)) {
    $errorAnswer .= 'empty';
  } else {
    if (!inRangeQuestionAndAnswer($answer, 10, 500)) {
      $errorAnswer .= 'length';
    }
  }


  if ($errorAnswer == '' && $errorQuestion == '' && $errorCategory == '' && $errorStatus == '') {
    // tạo ra một đối tượng FAQ
    // gọi phương thức createItem(đối tượng FAQ)

    $FAQarray = readJsonToArray('./data/FAQs.json');
    array_push($FAQarray, $newFaq);
    $newInfo = json_encode($FAQarray, JSON_PRETTY_PRINT);
    if (file_put_contents('./data/FAQS.json', $newInfo)) {
      $errorAnswer = '';
      $errorQuestion = '';
      $errorCategory = '';
      $errorStatus = '';
      header('Location: index.php');
      exit();
    }
  } else {
    if (isset(ERROR_MESSAGE_CONFIG[$errorAnswer])) $errorAnswer = '<strong class="text-danger">Question ' . ERROR_MESSAGE_CONFIG[$errorAnswer] . '</strong>';
    if (isset(ERROR_MESSAGE_CONFIG[$errorQuestion])) $errorQuestion = '<strong class="text-danger">Answer ' . ERROR_MESSAGE_CONFIG[$errorQuestion] . '</strong>';
  }
}

$htmlCategories = putCategorySelect('./data/categories.json', $categoryId);
// have a list of <option></option> of category in HTML
$htmlStatus = putStatusSelect(STATUS_CONFIG, $status);
// have a list of <option></option> of status in HTML


// template starts
$formElements = [
  [
    'label' => renderLabel('Category'),
    'element' => renderSelect('category_id', $htmlCategories),
    'error' => $errorCategory
  ],
  [
    'label' => renderLabel('Status'),
    'element' => renderSelect('status', $htmlStatus),
    'error' => $errorStatus
  ],
  [
    'label' => renderLabel('Question'),
    'element' => renderInput('question', $question),
    'error' => $errorQuestion
  ],
  [
    'label' => renderLabel('Answer'),
    'element' => renderTextarea('answer', $answer),
    'error' => $errorAnswer
  ]
];
$htmlForm = '';
foreach ($formElements as $key => $value) {
  $htmlForm .= sprintf('<div class="mb-3 col-12">%s %s %s</div>', $value['label'], $value['element'], $value['error']);
  // $value['label']
  // $value['element']
  // $value['error']
}

// template ends


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FAQs Management</title>
  <link rel="stylesheet" href="./assets/tabler.min.css" />

  <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto,
        Helvetica Neue, sans-serif;
    }

    body {
      font-feature-settings: 'cv03', 'cv04', 'cv11';
    }
  </style>
</head>

<body>
  <div class="container-fluid py-3">
    <h1 class="text-center">Create FAQ</h1>
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <form action="" method="POST">
          <div class="card mb-2">
            <div class="card-body">
              <div class="row">
                <?= $htmlForm ?>
              </div>
              <button type="submit" class="btn btn-primary">Save</button>
              <button type="button" class="btn">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="./assets/tabler.min.js"></script>
</body>

</html>