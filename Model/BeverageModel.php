<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class BeverageModel extends Database
{
    public function getBeverages($limit)
    {
        return $this->select("SELECT * FROM beverages ORDER BY id ASC LIMIT ?", ["i", $limit]);
    }
    public function getId($id, $limit)
    {
        return $this->select("SELECT quantity FROM beverages WHERE id = $id ORDER BY id ASC LIMIT ?", ["i", $limit]);
    }
    public function putCoin()
    {
        return $this->update("UPDATE coins SET quantity = quantity + 1 WHERE id = 1");
    }
    public function deleteCoin()
    {
        return $this->update("UPDATE coins SET quantity = quantity - 2 WHERE id = 1");
    }
    public function selectCoin()
    {
        return $this->select("SELECT quantity FROM coins WHERE id = 1");
    }
    public function putInventory($id)
    {
        return $this->update("UPDATE beverages SET quantity = quantity - 1 WHERE id = $id");
    }
}