<?php
require_once 'functions.php';
?>
<?php
require_once 'define.php';
require_once 'functions.php';
$categoriesInfo = file_get_contents('./data/categories.json');
$categoriesArray = json_decode($categoriesInfo, true);
$FAQ = file_get_contents('./data/FAQs.json');
$FAQarray = json_decode($FAQ, true);
$FAQarrayFilter = $FAQarray;
$search = $_GET['search'] ?? '';
$categoriesAll = $_GET['categories'] ?? [];
$statusAll = $_GET['status'] ?? [];
if (!empty($search)) {
  foreach ($FAQarrayFilter as $key => $value) {
    if (!str_contains(strtolower($value['question']), strtolower($search))) {
      unset($FAQarrayFilter[$key]);
    }
  }
}
if (!empty($statusAll) && !empty($FAQarrayFilter)) {
  foreach ($FAQarrayFilter as $key => $value) {
    if (!in_array(strtolower($value['status']), $statusAll)) {
      unset($FAQarrayFilter[$key]);
    }
  }
}
if (!empty($categoriesAll) && !empty($FAQarrayFilter)) {
  foreach ($FAQarrayFilter as $key => $value) {
    if (!in_array($value['categoryId'], $categoriesAll)) {
      unset($FAQarrayFilter[$key]);
    }
  }
}
$htmlSearch = '';
foreach ($FAQarrayFilter as $key => $value) {
  $idVal = $value['id'];
  $quest = $value['question'];
  if (!empty($search)) {
    $tmp = $quest;
    $quest = highlightKeywords($tmp, $search);
  }
  $answer = $value['answer'];
  $status = ucfirst($value['status']);
  $categories = '';
  $statusClass = getStatusClass($status);
  foreach ($categoriesArray as $key2 => $value2) {
    if ($value2['id'] == $value['categoryId']) {
      $categories = $value2['name'];
      break;
    }
  }
  $htmlSearch .= '
    <tr>
      <td class="w-1">
        <input class="form-check-input" type="checkbox" name="ids[]" value="' . $idVal . '" />
      </td>
      <td>' . $idVal . '</td>
      <td>' . $quest . '</td>
      <td>' . $categories . '</td>
      <td>
        <span class="badge bg-' . $statusClass . ' text-' . $statusClass . '-fg">' . $status . '</span>
      </td>
      <td class="text-nowrap">
        <a href="edit.php?id=' . $idVal . '" class="btn btn-sm btn-primary">Edit</a>
        <a href="delete.php?id=' . $idVal . '" class="btn btn-sm btn-danger" type="button">Delete</a>
      </td>
    </tr>';
}
$htmlStatus = renderFilterStatusCheckbox(STATUS_CONFIG, $statusAll);
$htmlCategories = renderFilterCategoryCheckbox($categoriesArray, $categoriesAll);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>FAQs Management</title>
  <link rel="stylesheet" href="./assets/tabler.min.css" />
  <link rel="stylesheet" href="./assets/STYLES.css" />
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
  <div class="container py-3">
    <h1 class="text-center">FAQs</h1>
    <form action="index.php" class="mb-2" method="get" name="searchForm">
      <div class="card card-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="card mb-2">
              <div class="card-header">
                <h4 class="card-title">Filter By Status</h4>
              </div>
              <div class="card-body">
                <!-- <label class="form-check">
                  <input class="form-check-input" name="status[]" type="checkbox" value="published" <?php echo (in_array("published", $statusAll) ? 'checked' : ''); ?>/>
                  <span class="form-check-label"> Published </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" name="status[]" type="checkbox" value="pending" <?php echo (in_array("pending", $statusAll) ? 'checked' : ''); ?>/>
                  <span class="form-check-label"> Pending </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" name="status[]" type="checkbox" value="draft" <?php echo (in_array("draft", $statusAll) ? 'checked' : ''); ?>/>
                  <span class="form-check-label">Draft</span>
                </label> -->
                <?php
                echo $htmlStatus;
                ?>
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Filter By Category</h4>
              </div>
              <div class="card-body">
                <!-- <label class="form-check">
                  <input class="form-check-input" name="categories[]" type="checkbox" value="1" <?php echo (in_array(1, $categoriesAll) ? 'checked' : ''); ?>/>
                  <span class="form-check-label"> SHIPPING </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" name="categories[]" type="checkbox" value="2" <?php echo (in_array(2, $categoriesAll) ? 'checked' : ''); ?>/>
                  <span class="form-check-label"> PAYMENT </span>
                </label>
                <label class="form-check">
                  <input class="form-check-input" name="categories[]" type="checkbox" value="3" <?php echo (in_array(3, $categoriesAll) ? 'checked' : ''); ?>/>
                  <span class="form-check-label"> ORDER & RETURNS </span>
                </label> -->
                <?php
                echo $htmlCategories;
                ?>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-1">
              <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search ..." value="<?php echo $search; ?>" />
                <input class="btn" type="submit" value="Submit" />
                <a class="btn" href="index.php">Clear</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>

    <form action="multi-delete.php" method="POST">
      <div class="card">
        <div class="card-header">
          <div class="w-100 justify-content-between d-flex flex-wrap align-items-center gap-1">
            <div class="d-flex align-items-center gap-1">
              <button type="submit" class="btn btn-danger">Bulk Delete</button>
            </div>
            <div>
              <a href="add.php" class="btn btn-primary">Create New</a>
            </div>
          </div>
        </div>
        <div class="card-table">
          <div class="table-responsive table-has-actions table-has-filter">
            <table class="table table-vcenter card-table table-striped">
              <thead>
                <tr>
                  <th>
                    <input class="form-check-input" type="checkbox" />
                  </th>
                  <th>ID</th>
                  <th>Question</th>
                  <th>Category</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php echo $htmlSearch; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </form>

  </div>
  <script src="./assets/tabler.min.js"></script>
</body>

</html>