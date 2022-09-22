<?php
//You should not edit this part of the file unless you know what you are doing.
session_start();
require_once('stripe-php/init.php');
require('core/config.php');
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addCart'])) {
    $stroply->addToCart();
} else if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['deleteItem'])){
    $stroply->removeFromCart();
} else if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['genLink'])){
    $stroply->generatePayment();
}
///////////////////////////////////


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php print $GLOBALS['storeName']; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="assets/img/apple-icon.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.ico">

    <link href="http://fonts.cdnfonts.com/css/lato" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/stroply.css">
    <link rel="stylesheet" href="assets/css/custom.css">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
</head>

<body>
    <!-- Start Top Nav -->
    <nav class="navbar menu-bar-contacts navbar-light d-lg-block" id="navbar-top">
        <div class="container text-light">
            <div class="w-100 d-flex justify-content-between">
                <div>
                    <?php if (strlen($GLOBALS['storeEmail']) > 0) { ?>
                        <i class="fa fa-envelope mx-2"></i>
                        <a class="navbar-sm-brand text-light text-decoration-none" href="mailto:<?php print $GLOBALS['storeEmail']; ?>"><?php print $GLOBALS['storeEmail']; ?></a>
                    <?php } ?>
                    <?php if (strlen($GLOBALS['storePhoneNumber']) > 0) { ?>
                        <i class="fa fa-phone mx-2"></i>
                        <a class="navbar-sm-brand text-light text-decoration-none" href="tel:<?php print $GLOBALS['storePhoneNumber']; ?>"><?php print $GLOBALS['storePhoneNumber']; ?></a>
                    <?php } ?>
                </div>
                <div>
                    <?php if (strlen($GLOBALS['socialMediaFacebook']) > 0) { ?>
                        <a class="text-light" href="<?php print $GLOBALS['socialMediaFacebook']; ?>" target="_blank" rel="sponsored"><i class="fab fa-facebook-f fa-sm fa-fw me-2"></i></a>
                    <?php } ?>
                    <?php if (strlen($GLOBALS['socialMediaInstagram']) > 0) { ?>
                        <a class="text-light" href="<?php print $GLOBALS['socialMediaInstagram']; ?>" target="_blank"><i class="fab fa-instagram fa-sm fa-fw me-2"></i></a>
                    <?php } ?>
                    <?php if (strlen($GLOBALS['socialMediaTwitter']) > 0) { ?>
                        <a class="text-light" href="<?php print $GLOBALS['socialMediaTwitter']; ?>" target="_blank"><i class="fab fa-twitter fa-sm fa-fw me-2"></i></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </nav>
    <!-- Close Top Nav -->


    <!-- Header -->
    <nav class="navbar navbar-light shadow menu-bar">
        <div class="container d-flex justify-content-between align-items-center">

            <a class="navbar-brand text-success logo h1 align-self-center" href="index.php">
                <img src="<?php print $GLOBALS['storeLogoPath']; ?>" style="width: 70px;" />
            </a>

            <div class="align-self-center flex-fill  d-lg-flex justify-content-lg-between">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                    </ul>
                </div>
                <div class="navbar align-self-center d-flex">
                    <a class="nav-icon position-relative text-decoration-none" href="#" data-bs-toggle="modal" data-bs-target="#carrinho">
                        <i class="fa fa-fw fa-cart-arrow-down mr-1" style="color: white;"></i>
                        <span class="position-absolute top-0 left-100 translate-middle badge rounded-pill bg-light text-dark"><?php print $stroply->countProducts(); ?></span>
                    </a>
                </div>
            </div>

        </div>
    </nav>
    <!-- Close Header -->

    <!-- Modal shopping cart -->
    <div class="modal fade" id="carrinho" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Shopping Cart</h5>
                    <button type="button" class="btn close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Price per unit</th>
                                    <th scope="col">Total</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($_SESSION["shoppingcart"])) {
                                    foreach ($_SESSION["shoppingcart"] as $item) { ?>
                                        <tr>
                                            <th scope="row"><?php print $item[2]; ?></th>
                                            <td><?php print $item[0]; ?></td>
                                            <td><?php print $item[3]; ?></td>
                                            <td><?php print $item[0] * $item[3]; ?></td>
                                            <td>
                                                <form method="post">
                                                    <input type="text" name="delete_id" value="<?php print $item[1]; ?>" style="display: none;" />
                                                    <input class="btn btn-danger text-white" type="submit" name="deleteItem" value="x" />
                                                </form>
                                            </td>
                                        </tr>
                                    <?php }
                                    if (sizeof($_SESSION['shoppingcart']) == 0) { ?>
                                        <tr>
                                            <td colspan="4">Your cart is empty </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="4">Your cart is empty </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php
                        //var_dump((float)getCartValue());
                        if (isset($_SESSION["shoppingcart"])) {
                            if (sizeof($_SESSION["shoppingcart"]) != 0 && (float)$stroply->getCartValue() >= (float)$GLOBALS['minimumOrderAmmount']) { ?>
                                <form method="post">
                                    <input class="btn btn-info text-white btn-payment" type="submit" name="genLink" value="Proceed to payment" />
                                </form>
                            <?php } else { ?>
                                <div class="alert alert-warning" role="alert">
                                    The minimum order amount is <?php print $GLOBALS['minimumOrderAmmount']; ?><?php print $GLOBALS['currency']; ?>
                                </div>
                            <?php }
                        } else { ?>
                            <div class="alert alert-warning" role="alert">
                                The minimum order amount is <?php print $GLOBALS['minimumOrderAmmount']; ?><?php print $GLOBALS['currency']; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-stroply" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal privacy policy -->
    <div class="modal fade" id="privacy" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php print $GLOBALS['privacyPolicyTitle']; ?></h5>
                    <button type="button" class="btn close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <p><?php print $GLOBALS['privacyPolicyText']; ?></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-stroply" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal shipping information -->
    <div class="modal fade" id="shipping" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php print $GLOBALS['shippingInfoTitle']; ?></h5>
                    <button type="button" class="btn close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <p><?php print $GLOBALS['shippingInfoText']; ?></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-stroply" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal terms conditions -->
    <div class="modal fade" id="terms" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php print $GLOBALS['termsTitle']; ?></h5>
                    <button type="button" class="btn close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <p><?php print $GLOBALS['termsText']; ?></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-stroply" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Start Content -->
    <div class="container py-5">
        <div class="row">

            <div class="col-12" align="center">
                        <h3><?php print $GLOBALS['storeName']; ?></h3>
                </div>
                <div class="row">
                    <?php
                    foreach ($stroply->getProducts() as $produto) {
                        if (!isset($produto["metadata"]["tipo"]) || $produto["metadata"]["tipo"] != "arquivado") {
                    ?>

                            <!-- Modal infos -->
                            <div class="modal fade" id="<?php print $produto["id"]; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Product Details</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <img class="card-img img-fluid img-produto" style="max-width: 10vw; border-radius: 10px;" src="<?php print $produto["images"][0]; ?>">
                                                    </div>
                                                    <div class="col-6">
                                                        <h3><?php print $produto["name"]; ?></h3>
                                                        <h4 class="product-price"><?php print $stroply->getPrice($produto["id"])[0]; ?>€</h4>
                                                    </div>
                                                </div>
                                                <h4>Description</h4>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-12">
                                                    <p><?php print $produto["description"]; ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-closeproduct" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" align="center">
                                <div class="card mb-4 product-wap rounded-0">
                                    <div class="card rounded-0">
                                        <img class="card-img rounded-0 img-fluid" src="<?php print $produto["images"][0]; ?>">
                                        <div class="card-img-overlay rounded-0 product-overlay d-flex align-items-center justify-content-center">
                                            <ul class="list-unstyled">
                                                <li><button class="btn btn-stroply text-white mt-2" data-bs-toggle="modal" data-bs-target="#<?php print $produto["id"]; ?>"><i class="far fa-eye"></i></a></button>
                                                <li>
                                                    <br>
                                                    <form method="post">
                                                        <input type="number" name="quantidade" value="1" style="display: none;" />
                                                        <input type="text" name="id" value="<?php print $stroply->getPrice($produto["id"])[1]; ?>" style="display: none;" />
                                                        <input type="text" name="nome" value="<?php print $produto["name"]; ?>" style="display: none;" />
                                                        <input type="text" name="price" value="<?php print $stroply->getPrice($produto["id"])[0]; ?>" style="display: none;" />
                                                        <input class="btn btn-stroply text-white" type="submit" name="addCart" value="Add to cart" />
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h3><?php print $produto["name"]; ?></h3>
                                        <h5 class="product-price"><?php print $stroply->getPrice($produto["id"])[0]; ?>€</h5>
                                        <ul class="w-100 list-unstyled d-flex justify-content-between mb-0">
                                            <li class="pt-2">
                                                <span class="product-color-dot color-dot-red float-left rounded-circle ml-1"></span>
                                                <span class="product-color-dot color-dot-blue float-left rounded-circle ml-1"></span>
                                                <span class="product-color-dot color-dot-black float-left rounded-circle ml-1"></span>
                                                <span class="product-color-dot color-dot-light float-left rounded-circle ml-1"></span>
                                                <span class="product-color-dot color-dot-green float-left rounded-circle ml-1"></span>
                                            </li>
                                        </ul>
                                        <form method="post">
                                            <input type="number" name="quantidade" value="1" style="width:60px;" />
                                            <input type="text" name="id" value="<?php print $stroply->getPrice($produto["id"])[1]; ?>" style="display: none;" />
                                            <input type="text" name="nome" value="<?php print $produto["name"]; ?>" style="display: none;" />
                                            <input type="text" name="price" value="<?php print $stroply->getPrice($produto["id"])[0]; ?>" style="display: none;" />
                                            <input class="btn btn-stroply text-white" type="submit" name="addCart" value="Add to cart" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
            </div>

        </div>
    </div>
    <!-- End Content -->


    <!-- Start Footer -->
    <footer class="bg-dark" id="stroply-footer">

        <div class="w-100 py-3" style="background-color: #FF7600;">
            <div class="container">
                <div class="row pt-2">
                    <div class="col-4" align="center">
                        <a class="footer-links" data-bs-toggle="modal" data-bs-target="#privacy">
                            Privacy Policy
                        </a>
                    </div>
                    <div class="col-4" align="center" data-bs-toggle="modal" data-bs-target="#shipping">
                        <a class="footer-links">
                            Shipping Information
                        </a>
                    </div>
                    <div class="col-4" align="center" data-bs-toggle="modal" data-bs-target="#terms">
                        <a class="footer-links">
                            Terms and Conditions
                        </a>
                    </div>
                </div>
                <div class="row pt-2 row-copyright">
                    <div class="col-12" align="center">
                        <!-- Please do not remove stroply link, suport the project -->
                        <p class="text-left text-dark">
                            Copyright &copy; <?php print $GLOBALS['storeName']; ?>
                            | Store powered by <a class="text-white text-decoration-none" rel="sponsored" href="https://stroply.com" target="_blank">stroply.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </footer>
    <!-- End Footer -->

    <!-- Start Script -->
    <script src="assets/js/jquery-1.11.0.min.js"></script>
    <script src="assets/js/jquery-migrate-1.2.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/templatemo.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        };
    </script>

    <!-- End Script -->
</body>

</html>