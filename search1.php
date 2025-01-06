<?php
    $categoriesInfo = file_get_contents('./data/categories.json');
    $categoriesArray = json_decode($categoriesInfo, true);
    $FAQ = file_get_contents('./data/FAQs.json');
    $FAQarray = json_decode($FAQ, true);
    $FAQarrayFilter = $FAQarray;
    $search = $_GET['search'] ?? '';
    echo '<pre style="color: red;font-weight:bold">';
    print_r($_GET);
    echo '</pre>';
    if (!empty($search)) {
      $FAQarrayFilter = [];
      foreach ($FAQarray as $key => $value) {
        if (str_contains($value['question'], $search)) {
          array_push($FAQarrayFilter, $value);
        }
      }
    }
    $htmlSearch = '';
    foreach ($FAQarrayFilter as $key => $value) {
      $idVal = $value['id'];
      $quest = $value['question'];
      if (!empty($search)) {
        $quest = str_replace($search, "<mark>$search</mark>", $quest);
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
          <input class="form-check-input" type="checkbox" />
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
?>