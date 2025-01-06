<?php
require_once 'functions.php';
require_once 'define.php';
$answer = '';
$question = '';
$idVal = '';
$CurrentStatus = '';
$errorStatus = '';
$errorCategory = '';
$errorQuestion = '';
$errorAnswer = '';
if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $categoriesArray = readJsonToArray('./data/categories.json');
  $FAQarray = readJsonToArray('./data/FAQs.json');
  foreach ($FAQarray as $key => $value) {
    if ($value['id'] == $id) {
      $question = $value['question'];
      $answer = $value['answer'];
      $currentStatus = $value['status'];
      $idVal = $value['categoryId'];
      break;
    }
  }
  if (isset($_POST['category_id']) && isset($_POST['status']) && isset($_POST['question']) && isset($_POST['answer'])) {
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $idVal = $_POST['category_id'];
    $currentStatus = $_POST['status'];
    // 10 - 255
    if (checkEmpty($question)) {
      $errorQuestion .= '<strong class="text-danger">Question can not be empty</strong>';
    }
    else {
      if (!inRangeQuestionAndAnswer($question, 10, 255)) {
        $errorQuestion .= '<strong class="text-danger">Question has invalid length</strong>';
      }
    }
    // 25 - 500
    if (checkEmpty($answer)) {
      $errorAnswer .= '<strong class="text-danger">Answer can not be empty</strong>';
    }
    else {
      if (!inRangeQuestionAndAnswer($answer, 10, 500)) {
        $errorAnswer .= '<strong class="text-danger">Answer has invalid length</strong>';
      }
    }
    if ($errorAnswer == '' && $errorCategory == '' && $errorQuestion == '' && $errorStatus == '') {
      $indexEdit = -1;
      foreach ($FAQarray as $key => $value) {
        if ($value['id'] == $id) {
          $indexEdit = $key;
          break;
        }
      }
      $FAQarray[$indexEdit]['question'] = $question;
      $FAQarray[$indexEdit]['answer'] = $answer;
      $FAQarray[$indexEdit]['status'] = $currentStatus;
      $FAQarray[$indexEdit]['categoryId'] = intval($idVal);
      $newInfo = json_encode($FAQarray, JSON_PRETTY_PRINT);
      if (file_put_contents('./data/FAQS.json', $newInfo)) {
        $errorStatus = '';
        $errorCategory = '';
        $errorQuestion = '';
        $errorAnswer = '';
        $indexEdit = -1;
        header('Location: index.php');
        exit();
      }
    }
  }
  $htmlCategories = putCategorySelect('./data/categories.json', $idVal);
  // have a list of <option></option> of category in HTML
  $htmlStatus = putStatusSelect(STATUS_CONFIG, $currentStatus);
  // have a list of <option></option> of status in HTML

  // include name , what it has and its errors
  // category , status, question, answer
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

  $htmlAll = '';
  $htmlAll .= '<div class="mb-3 col-12">'.$formElements[0]['label'].$formElements[0]['element'].$formElements[0]['error'].'</div>';
  $htmlAll .= '<div class="mb-3 col-12">'.$formElements[1]['label'].$formElements[1]['element'].$formElements[1]['error'].'</div>';
  $htmlAll .= '<div class="mb-3 col-12">'.$formElements[2]['label'].$formElements[2]['element'].$formElements[2]['error'].'</div>';
  $htmlAll .= '<div class="mb-3 col-12">'.$formElements[3]['label'].$formElements[3]['element'].$formElements[3]['error'].'</div>';

}
?>

<!-- PAYMENT - PUBLISHED -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FAQs Management</title>
  <link rel="stylesheet" href="./assets/tabler.min.css" />
  <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#cancel-button').click(function() {
        window.location = 'index.php';
      });
    });
  </script>
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
    <h1 class="text-center">Edit FAQ</h1>
    <form action="#" method="post" name="editForm">
      <!-- question, answer, status, cateid -->
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <div class="card mb-2">
            <div class="card-body">
              <div class="row">
                <!-- <div class="mb-3 col-12">
                  <label for="category_id" class="form-label required">Category</label>
                  <select
                    class="form-control form-select"
                    required="required"
                    id="category_id"
                    name="category_id"
                    aria-required="true">
                    <?php
                    echo $htmlCategories;
                    ?>
                  </select>
                  <?php
                  echo $errorCategory;
                  ?>
                </div>
                <div class="mb-3 col-12">
                  <label for="status" class="form-label required">Status</label>
                  <select
                    class="form-control form-select"
                    required="required"
                    id="status"
                    name="status"
                    aria-required="true">
                    <?php
                    echo $htmlStatus;
                    ?>
                  </select>
                  <?php
                    echo $errorStatus;
                  ?>
                </div>
                <div class="mb-3 col-12">
                  <label for="question" class="form-label required">Question</label>
                  <input type="text" class="form-control" name="question" id="question" value="<?php echo $question; ?>" />
                  <?php
                    echo $errorQuestion;
                  ?>
                </div>
                <div class="mb-3 col-12">
                  <label for="answer" class="form-label required">Answer</label>
                  <textarea
                    class="form-control"
                    rows="4"
                    required="required"
                    name="answer"
                    cols="50"
                    id="answer"
                    value="answer"><?php echo $answer; ?></textarea>
                    <?php
                      echo $errorAnswer;
                    ?>
                </div> -->
                <?php echo $htmlAll; ?>
              </div>
              <input type="submit" class="btn btn-primary" name="submit" value="Save" />
              <a type="button" class="btn" href="index.php">Cancel</a>
              <!-- <input type="button" class="btn" value="Cancel" name="cancel" id="cancel-button"/> -->
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <script src="./assets/tabler.min.js"></script>
</body>

</html>