<?php
require_once 'FAQ.php';
class FAQManagement {
    // private property: $items = [] -> là một mảng chứa nhiều đối tượng FAQ
    // private property: $categories = [['id' => 1, 'name': 'SHIPPING', ....]]
    // private property: $jsonFile
    private array $items;
    private array $categories;
    private $jsonFileName;
    public function __construct($fileName)
    {
        $this -> jsonFileName =  $fileName;
        $jsonContent = file_get_contents($fileName);
        $arrJSON = json_decode($jsonContent, true);
        foreach ($arrJSON as $key => $value) {
            $newElm = new FAQ();
            array_push($this -> items, $newElm); 
        }
    }
    /*
    constructor -> ($jsonFileName) {
        $this->jsonFile = $jsonFileName;
        đọc file json -> array 
        duyệt qua array tạo ra các đối tượng FAQ và thêm vào $items

        foreach ($data as $item) {
            $faq = new FAQ($item['id'], $item['question', .... ]);
            array_push($this->items, $faq);
        }
    }
    */

    // 1 private property -> 2 method (set & get)
    
    /*
    METHOD
    - public getItems -> lấy danh sách
    - public createItem(đối tượng FAQ)
    - public getItem($id) -> trả về một đối tượng FAQ
    - public updateItem($id, đối tượng FAQ)
        $this->items[$index] = đối tượng FAQ;
    - public deleteItem($id)
    - public multiDelete($arrId)
    - private saveToFile();
    */
}



