<?php

session_start();

/* START OF CART CLASS */
class Cart{
	/* DECLARING VARIABLES & CONSTRUCTOR */
	private $cart_name;
	private $cart_price;
	private $cart_qty;

	private $products = [
		[ "name" => "Sledgehammer", "price" => 125.75 ],
		[ "name" => "Axe", "price" => 190.50 ],
		[ "name" => "Bandsaw", "price" => 562.131 ],
		[ "name" => "Chisel", "price" => 12.9 ],
		[ "name" => "Hacksaw", "price" => 18.45 ]
	];
	
	public function __construct() {
	    $cart_name = "";
		$cart_price = 0.00;
		$cart_qty = 0;

		$products = array();
	    $cart = array();
	}
	/* DECLARING VARIABLES & CONSTRUCTOR */

	/* CART FUNCTIONS */
	public function addCart(Cart $cart, $cartIndex){
    	$cart->setCartName($cart->getProducts()[$cartIndex]['name']);
	    $cart->setCartPrice($cart->getProducts()[$cartIndex]['price']);
	    $cart->setCartQty(1);

		if (isset($_SESSION['cart'])) {
			$index = 0;
			$isTrue = false;
			foreach ($_SESSION['cart'] as $cartItem) {
				if ($cartItem->getCartName() == $cart->getCartName()) {
					$_SESSION['cart'][$index]->setCartQty($cartItem->getCartQty()+1);
					$isTrue = true;
				}

				$index++;
			}
			if (!$isTrue) { array_push($_SESSION['cart'], $cart); }

	    }else{
	    	$_SESSION['cart'] = array($cart);
	    }
		header( "Location: cart.php" );
	}

	public function rvmCart($index){
		if (($_SESSION['cart'][$index]->getCartQty()-1) <= 0) {
			unset($_SESSION['cart'][$index]);
			$rvmArray = $_SESSION['cart'];
			$_SESSION['cart'] = array();

			foreach ($rvmArray as $rvmItem) {
				array_push($_SESSION['cart'], $rvmItem);
			}
		}else{
			$_SESSION['cart'][$index]->setCartQty($_SESSION['cart'][$index]->getCartQty()-1);
		}
		header( "Location: cart.php" );
	}

	public function cartTotal(){
		$total = 0;
		foreach ($_SESSION['cart'] as $item) {
			$total += $item->getCartPrice()*$item->getCartQty();
		}
		return $total;
	}
	/* CART FUNCTIONS */


	/* GETTERS & SETTERES */
	public function getProducts(){
		return $this->products;
	}

	public function setCartName($cart_name){
		$this->cart_name = $cart_name;
	}
	public function getCartName(){
		return $this->cart_name;
	}
	public function setCartPrice($cart_price){
		$this->cart_price = number_format($cart_price,2);
	}
	public function getCartPrice(){
		return $this->cart_price;
	}

	public function setCartQty($cart_qty){
		$this->cart_qty = (int)$cart_qty;
	}
	public function getCartQty(){
		return $this->cart_qty;
	}
	/* GETTERS & SETTERES*/

}
/* END OF CART CLASS */

/* EVENT HANDLERS */
if (isset($_POST['addCart'])) { $cart = new Cart(); $cart->addCart($cart, $_POST['addCart']); 	
}
if (isset($_POST['rvmCart'])) { $cart = new Cart(); $cart->rvmCart($_POST['rvmCart'], $_POST['cartIndex']); }
/* EVENT HANDLERS */
?>

<!DOCTYPE html>
<html>
	<head>
		<title>EzyVet -Shopping Cart</title>

		<!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <!-- Bootstrap CSS file -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">


	</head>

	<body>
		<div class="bg-light m-3 p-5">

			<div class="h1" id="Header" align="center">
				<div align="center"><i class="h1 m-3 fas fa-shopping-cart"></i>Simple Shopping Cart </div>
				<hr /><div class="h2">Developed by Camal Warsame</div>
			</div>

			<div id="Cart" class="p-5 bg-secondary mt-5">
				<div class="h3 p-2 text-white"><i class="m-3 fas fa-shopping-cart"></i> Cart</div>

				<div id="CarttResults" class="m-2 rounded" align="center">
					<form method='POST' style='display: flex;'>
						<?php if (isset($_SESSION['cart'])) { $cartIndex = 0; 
						foreach ($_SESSION['cart'] as $cartItem) { ?>
							<div class='h5 bg-light p-2 m-2 r-5' style='flex: 1;'><?php echo $cartItem->getCartName(); ?>
								<hr/>
								<span class='text-danger'>	&#163;<?php echo $cartItem->getCartPrice(); ?> x <?php echo $cartItem->getCartQty(); ?><br> = 	&#163;<?php echo number_format($cartItem->getCartPrice()*$cartItem->getCartQty(),2); ?></span><br>
								<input type="hidden" name="cartIndex" value="<?php echo $cartIndex[$cartIndex][0]; ?>" /><button name="rvmCart" class='btn btn-primary m-2' value="<?php echo $cartIndex; ?>"><i class="fas fa-trash-alt"></i></button>
							</div>
						<?php $cartIndex++; } } ?>
					</form>
					<h3 class="m-2"><?php $cart = new Cart(); if (isset($_SESSION['cart'])) { echo "Total Cost: &#163;".number_format($cart->cartTotal(),2); } ?></h3>
				</div>
			</div>

			<div id="Products" class="p-5 bg-secondary mt-5">
				<div class="h3 p-2 text-white"><i class="m-3 fas fa-list"></i> All Products</div>

				<div id="ProductResults" class="m-2 rounded" align="center">
					<form method='POST' style='display: flex;'>
						<?php $cart = new Cart(); $productIndex = 0;
						foreach ($cart->getProducts() as $product) { ?>
							<div class='h5 bg-light p-2 m-2 r-5' style='flex: 1;'><?php echo $product['name']; ?>
								<hr/>
								<span class='text-danger'>	&#163;<?php echo $product['price']; ?></span><br>
								<button name="addCart" class='btn btn-primary m-2' value="<?php echo $productIndex; ?>"><i class="fas fa-plus"></i></button>
							</div>
						<?php $productIndex++; } ?>
					</form>
				</div>
			</div>


		</div>
	</body>

</html>