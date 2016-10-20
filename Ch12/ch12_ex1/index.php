<?php
// Start session management with a persistent cookie
//$lifetime = 60 * 60 * 24 * 14;    // 2 weeks in seconds
$lifetime = 60 * 60 * 24 * 365 *4; //seconds in 3 yrs
session_set_cookie_params($lifetime, '/');
session_start();

// Create a cart array if needed
if (empty($_SESSION['cart12'])) { $_SESSION['cart12'] = array(); }

// Create a table of products
$products = array();
$products['MMS-1754'] = array('name' => 'Flute', 'cost' => '149.50');
$products['MMS-6289'] = array('name' => 'Trumpet', 'cost' => '199.50');
$products['MMS-3408'] = array('name' => 'Clarinet', 'cost' => '299.50');

// Include cart functions
require_once('cart.php');

// Get the action to perform
$action = filter_input(INPUT_POST, 'action');
if ($action === NULL) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === NULL) {
        $action = 'show_add_item';
    }
}

// Add or update cart as needed
switch($action) {
    
    //add item to cart
    case 'add':
        $product_key = filter_input(INPUT_POST, 'productkey');
        $item_qty = filter_input(INPUT_POST, 'itemqty');
        add_item($product_key, $item_qty);
        include('cart_view.php');
        break;
    
    //update cart info
    case 'update':    
        $new_qty_list = filter_input(INPUT_POST, 'newqty', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        foreach($new_qty_list as $key => $qty) {
            if ($_SESSION['cart12'][$key]['qty'] != $qty) {
                update_item($key, $qty);
            }
        }
        include('cart_view.php');
        break;
    
    //display cart
    case 'show_cart':
        include('cart_view.php');
        break;
    
    //show add view
    case 'show_add_item':
        include('add_item_view.php');
        break;
    
    //empty cart
    case 'empty_cart':
        unset($_SESSION['cart12']);
        include('cart_view.php');
        break;
    
    //end session
    case 'end_session':
        //remove data
        $_SESSION = array();
        
        //clear id
        session_destroy();
        
        //name of session
        $name = session_name();
        
        //exppire time stamp
        $expire = strtotime('-1 year');
        
        //params of session id
        $params = session_get_cookie_params();
        
        //session path
        $path = $params['path'];
        
        //session domain
        $domain = $params['domain'];
        
        //secure default is false
        $secure = $params['secure'];
        
        //http only default is false
        $httponly = $params['httponly'];
        setcookie($action, '',  $expire, $path, $domain, $secure, $httponly);
        
        include('cart_view.php');
        break;
        
}
?>