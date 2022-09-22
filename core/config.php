<?php
////  This is the file you should edit with all the configs.
////  If you do need any help, contact us at hello@stroply.com

require('functions.php');

//////////////////////////////
// STRIPE API CONFIGURATION //
//////////////////////////////

//Please inser your Stripe API key here, it starts like "sk_live_..."
$stroply = new stroplyCore('sk_live_...');


//Whether or not to you want to allow your customers to use promotional codes
//This value should be set to true or false
$allowPromotionCodes = true;

//Whether or not to you want to collect the phone number from your buyers
//This value should be set to true or false
$phoneNumberCollection = true;


/////////////////////////
// STORE CONFIGURATION //
/////////////////////////

$storeName = "stroply";
$storeLogoPath = "assets/img/stroply.png";

//If you do not want to show one, leave it blank like: ""
$storeEmail = "hello@stroply.com";
$storePhoneNumber = "123456789";

//This is the minimum order ammount for your customers
// pay atention that the value SHOULD NOT BE LOWER than 0.50
// because stripe.com sets 0.50 as the minimum ammount for a payment
// link to be generated. This value should be float (ex: 1.00 or 15.50)
$minimumOrderAmmount = 0.50;

//The store currency, like € or $
//this do not change the currency in your sripe account
$currency = "€";

//Fil the URL of your store's social media
//If you do not want to show one, leave it blank like: ""
$socialMediaFacebook = "urlHere";
$socialMediaInstagram = "urlHere";
$socialMediaTwitter = "urlHere";


//Be aware that some of these informations are legally required in some countries.
$privacyPolicyTitle = "Privacy Policy";
$privacyPolicyText = "Insert your privacy policy here.";

//Do not remove or edit the line bellow, it is legally required to inform your visitors about the information we collect.
$privacyPolicyText .= " <br> stroply.com collects your ip address and region to protect and analyse information about this store.";
/////////////////////////

$shippingInfoTitle = "Shipping Information";
$shippingInfoText = "Insert your Shipping Information here";

$termsTitle = "Terms and conditions";
$termsText = "Insert your Terms and conditions here";

?>