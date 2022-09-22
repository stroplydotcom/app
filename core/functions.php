<?php

// WARNING! 
//You should not edit this file.
// All configurations are available in core/config.php file

class stroplyCore {

public function __construct($apiKey){

    $this->stripe = new \Stripe\StripeClient($apiKey);

    //Get products from Stripe API
    $this->product_list = json_encode($this->stripe->products->all(), JSON_FORCE_OBJECT);

    //Get prices from Stripe API
    $this->price = $this->stripe->prices->all();

    //Get products from JSON
    $this->products = json_decode($this->product_list, true);
}


public function getProducts(){
    return $this->products["data"];
}

public function getPrices(){
    return $this->price;
}

//Returns the number of items in the shopping cart
public function countProducts(){
    $countProducts = 0;
    if(isset($_SESSION["shoppingcart"]) && sizeof($_SESSION["shoppingcart"]) != 0){
        foreach($_SESSION["shoppingcart"] as $line){
            $countProducts = $countProducts + $line[0];
        }
    }
    return $countProducts;
}

//Returns the total cost of items in the shopping cart
public function getCartValue(){
    if(isset($_SESSION["shoppingcart"]) && sizeof($_SESSION["shoppingcart"]) != 0){
        $cartValue = (float)0.00;
        foreach($_SESSION["shoppingcart"] as $line){
            $cartValue += $line[0] * (float)$line[3];
        }
        return floatval($cartValue);
    }
    return 0;
}

//Generates the link for the customer to pay
public function generatePayment(){
    $litems = array();
    $litems_general = array();
    foreach($_SESSION["shoppingcart"] as $line_item){
        $litems["price"] = $line_item[1];
        $litems["quantity"] = $line_item[0];
        array_push($litems_general, $litems);
      }
    var_dump($litems_general);
    $url = $this->stripe->paymentLinks->create([
        'line_items' => [
          [
            $litems
          ],
        ],
        'allow_promotion_codes' =>  $GLOBALS['allowPromotionCodes'],
        'phone_number_collection' => ['enabled' => $GLOBALS['phoneNumberCollection']],
      ]);
      header("Location: " . $url["url"]);
      die();
}

//Get a price from a specific id
public function getPrice($id){
        foreach ($this->price["data"] as $price_unit) {
            if ($price_unit["product"] == $id) {
                if (strlen($price_unit["unit_amount"]) < 3) {
                    $priceToShow =  "0." . $price_unit["unit_amount"];
                } else {
                    if (strlen($price_unit["unit_amount"]) == 4){
                        $priceToShow = str_split($price_unit["unit_amount"], 1);
                        $priceToShow = $priceToShow[0]  . $priceToShow[1] . "." . $priceToShow[2]. $priceToShow[3]; 
                    } else {
                        $priceToShow = str_split($price_unit["unit_amount"], 1);
                        $priceToShow = $priceToShow[0] . "." . $priceToShow[1] . $priceToShow[2];
                    }
                }
                $id_price = $price_unit["id"];
            }
        }

        return [$priceToShow, $id_price];
}

public function addToCart(){

    $post_id = htmlentities($_POST['id'], ENT_QUOTES, 'UTF-8');
    $postQuantity = htmlentities($_POST['quantidade'], ENT_QUOTES, 'UTF-8');
    $postName = htmlentities($_POST['nome'], ENT_QUOTES, 'UTF-8');
    $post_price = htmlentities($_POST['price'], ENT_QUOTES, 'UTF-8');

    if (isset($_SESSION["shoppingcart"]) && sizeof($_SESSION["shoppingcart"]) != 0) {
        for($i = 0; $i < sizeof($_SESSION["shoppingcart"]); $i++){
            if(array_search($post_id, $_SESSION["shoppingcart"][$i])>0){
                $_SESSION["shoppingcart"][$i][0] = $_SESSION["shoppingcart"][$i][0] + $postQuantity;
            } else {
                array_push($_SESSION["shoppingcart"], [$postQuantity, $post_id, $postName, $post_price]);
                break;
            }
        }
    } else {
        $_SESSION["shoppingcart"] = [[$postQuantity, $post_id, $postName, $post_price]];
    }

}


public function removeFromCart(){

    $post_delete_id = htmlentities($_POST['delete_id'], ENT_QUOTES, 'UTF-8');
    for($i = 0; $i <= sizeof($_SESSION["shoppingcart"]); $i++){
        if (!isset($_SESSION["shoppingcart"][$i])){
            continue;
        }
        if(array_search($post_delete_id, $_SESSION["shoppingcart"][$i])>=0){
            unset($_SESSION["shoppingcart"][$i]);
        }
    }

}

}

?>