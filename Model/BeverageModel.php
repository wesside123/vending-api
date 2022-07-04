<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

//define our database queries
class BeverageModel extends Database
{
    //Get all beverage quantities
    public function getBeverages($limit)
    {
        return $this->select("SELECT quantity FROM beverages ORDER BY id ASC LIMIT ?", ["i", $limit]);
    }
    //Get beverage quantity by beverage ID (0,1,2)
    public function getId($id, $limit)
    {
        return $this->select("SELECT quantity FROM beverages WHERE id = $id ORDER BY id ASC LIMIT ?", ["i", $limit]);
    }
    //Add a coin
    public function putCoin()
    {
        return $this->update("UPDATE coins SET quantity = quantity + 1 WHERE id = 1");
    }
    //Remove 2 coins
    public function deleteCoin()
    {
        return $this->update("UPDATE coins SET quantity = quantity - 2 WHERE id = 1");
    }
    //Get quantity of coins
    public function selectCoin()
    {
        return $this->select("SELECT quantity FROM coins WHERE id = 1");
    }
    //Update beverage quantity based on ID
    public function putInventory($id)
    {
        return $this->update("UPDATE beverages SET quantity = quantity - 1 WHERE id = $id");
    }
}