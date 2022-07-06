<?php
/*
    BeverageController.php - logic for updating beverage database and sending responses
*/
require_once PROJECT_ROOT_PATH . "/Controller/Api/BaseController.php";

class BeverageController extends BaseController {
  /**
   * "index.php/inventory" Endpoint 
   */
  public function inventory() {
    $strErrorDesc = '';
    $requestMethod = $_SERVER[ "REQUEST_METHOD" ];
    $arrQueryStringParams = $this->getQueryStringParams();
      //print_r($_GET);

    //Get array of remaining item quantities
    if ( strtoupper( $requestMethod ) == 'GET' ) {
      try {
        $beverageModel = new BeverageModel();

        $intLimit = 3;
        
        //Get beverage quantity based on ID
        if ( isset( $arrQueryStringParams[ 'id' ] ) ) {
          $id = $arrQueryStringParams[ 'id' ];
            
          $arrBeverages = $beverageModel->getId( $id, $intLimit );
        //Get all beverage quantities
        } else
          $arrBeverages = $beverageModel->getBeverages( $intLimit );        
          
        $quantity = array_column($arrBeverages, 'quantity');
          
        $responseData = json_encode($quantity);

        $responseCode = 'HTTP/1.1 200 OK';  
        $xcoins = "";
        $xinventory = "";
      } catch ( Error $e ) {
        $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    }

    //Vend a beverage 
    else if ( strtoupper( $requestMethod ) == 'PUT' && isset( $arrQueryStringParams[ 'id' ] )) {
      try {
        $beverageModel = new BeverageModel();

        $intLimit = 3;
        //Vend beverage based on ID
        if ( isset( $arrQueryStringParams[ 'id' ] ) ) {
          $id = $arrQueryStringParams[ 'id' ];
          $arrBeverageQuantity = $beverageModel->getId( $id, $intLimit );
          $arrCoinsQuantity = $beverageModel->selectCoin();
          if ( $arrBeverageQuantity[ 0 ][ 'quantity' ] > 0 && $arrCoinsQuantity[ 0 ][ 'quantity' ] > 1 ) {
            $beverageModel->deleteCoin();
            $beverageModel->putInventory($id);
            $arrBeverageQuantity = $beverageModel->getId( $id, $intLimit );  
            $arrCoinsQuantity = $beverageModel->selectCoin();  
            $xcoins = 'X-Coins:' . $arrCoinsQuantity[0]["quantity"];
            $xinventory = 'X-Inventory-Remaining:' . $arrBeverageQuantity[0]["quantity"];
            $responseData = json_encode(array("quantity"=>1));
            $responseCode = 'HTTP/1.1 200 OK';
          }
          //If out of stock
          else if ( $arrBeverageQuantity[ 0 ][ 'quantity' ] == 0 ) {
            $responseData ='';
            $xcoins = 'X-Coins:' . $arrCoinsQuantity[ 0 ]['quantity'];  
            $xinventory = "";
            $responseCode = 'HTTP/1.1 404 Not Found';
          } 
          //If not enough coins
          else if ( $arrCoinsQuantity[ 0 ][ 'quantity' ] <= 1 ) {
              
            $responseData ='';
            $xcoins = 'X-Coins:' . $arrCoinsQuantity[ 0 ]['quantity'] . '|2'; 
            $xinventory = "";
            $responseCode = 'HTTP/1.1 403 Forbidden';
          }
          
          else {
            $responseData ='';
            $xcoins = ""; 
            $xinventory = ""; 
            $responseCode = 'HTTP/1.1 400 Bad Request';
          }
    

        }
      } catch ( Error $e ) {
        $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
    }

    // send output
    if ( !$strErrorDesc ) {
        $this->sendOutput(
          $responseData,
          array( 'Content-Type: application/json', $responseCode, $xcoins, $xinventory )
        );
    }
    else {
      $this->sendOutput( json_encode( array( 'error' => $strErrorDesc ) ),
        array( 'Content-Type: application/json', $strErrorHeader )
      );
    }
  }

  //Put and Delete coins (index.php endpoint)
  public function coins() {

    $strErrorDesc = '';
    $requestMethod = $_SERVER[ "REQUEST_METHOD" ];
    //Add a coin
    if ( strtoupper( $requestMethod ) == 'PUT' ) {
      try {
        $beverageModel = new BeverageModel();
        $arrCoins = $beverageModel->putCoin();
        $coinQuantity = $beverageModel->selectCoin();
        $responseData = json_encode( $arrCoins );
      } catch ( Error $e ) {
        $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    }
      //Remove coins when Vending
      else if ( strtoupper( $requestMethod ) == 'DELETE' ) {
      try {
        $beverageModel = new BeverageModel();
        $arrCoins = $beverageModel->selectCoin();
        $responseData = json_encode( $arrCoins );
      } catch ( Error $e ) {
        $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
        $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
      }
    } else {
      $strErrorDesc = 'Method not supported';
      $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
    }

    // send output
    if ( !$strErrorDesc && strtoupper( $requestMethod ) == 'PUT' ) {
        $coinsAccepted = 'X-Coins:' . $coinQuantity[ 0 ][ "quantity" ];
      $this->sendOutput(
        $responseData,
        array( 'Content-Type: application/json', 'HTTP/1.1 204 OK', $coinsAccepted )
      );
    } else if ( !$strErrorDesc && strtoupper( $requestMethod ) == 'DELETE' ) {
      if ( ( $arrCoins[ 0 ][ "quantity" ] ) > 1 )
        $coinsReturned = 'X-Coins:' . ( $arrCoins[ 0 ][ "quantity" ] - 2 );
      else
        $coinsReturned = 'X-Coins:0';
      $this->sendOutput(
        $responseData,
        array( 'Content-Type: application/json', 'HTTP/1.1 204 OK', $coinsReturned )
      );
    } else {
      $this->sendOutput( json_encode( array( 'error' => $strErrorDesc ) ),
        array( 'Content-Type: application/json', $strErrorHeader )
      );
    }
  }
}