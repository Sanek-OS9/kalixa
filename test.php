<?php
require_once('sys/init.php');
use lib\DB;

$q = DB::me()->query("SELECT * FROM `orders`");
$items = $q->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blog</title>

    <!-- Lato Font -->
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>

    <!-- Stylesheet -->
    <link href="//cdn.shopify.com/s/files/1/1775/8583/t/1/assets/gallery-materialize.min.opt.css?9863881460345178995" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <meta name="shopify-checkout-api-token" content="6aacc581eb2b41d74f03c38d3c985dba">
<script type="text/javascript">
//<![CDATA[
      var Shopify = Shopify || {};
      Shopify.shop = "materialize-themes.myshopify.com";
      Shopify.theme = {"name":"debut","id":133945025,"theme_store_id":796,"role":"main"};
      Shopify.theme.handle = "null";
      Shopify.theme.style = {"id":null,"handle":null};

//]]>
</script><script id="__st">
//<![CDATA[
var __st={"a":17758583,"offset":-28800,"reqid":"8e72c0f6-6ed5-4b95-8ef6-5dc6266276ff","pageurl":"themes.materializecss.com\/pages\/no-image","s":"pages-200225793","u":"a79582921e62","p":"page","rtyp":"page","rid":200225793};
//]]>
</script>
<meta id="shopify-digital-wallet" name="shopify-digital-wallet" content="/17758583/digital_wallets/dialog" />
<script src="//cdn.shopify.com/s/assets/themes_support/ga_urchin_forms-68ca1924c495cfc55dac65f4853e0c9a395387ffedc8fe58e0f2e677f95d7f23.js" defer="defer"></script>
      <script type="text/javascript">
        
      window.ShopifyAnalytics = window.ShopifyAnalytics || {};
      window.ShopifyAnalytics.meta = window.ShopifyAnalytics.meta || {};
      window.ShopifyAnalytics.meta.currency = 'USD';
      var meta = {"page":{"pageType":"page","resourceType":"page","resourceId":200225793}};
      for (var attr in meta) {
        window.ShopifyAnalytics.meta[attr] = meta[attr];
      }
    
      </script>

      <script type="text/javascript">
        window.ShopifyAnalytics.merchantGoogleAnalytics = function() {
          
        };
      </script>

    
<meta id="in-context-paypal-metadata" data-shop-id="17758583" data-environment="production" data-locale="en_US" data-merchant-id="972FMYYC2E548" data-redirect-url="" />
<script src="//cdn.shopify.com/s/assets/storefront/express_buttons-5530a7c24a97016875fee7b57d34eb37669f1953bf564e7052c0190e3c3d6722.js" defer="defer" crossorigin="anonymous" integrity="sha256-VTCnwkqXAWh1/ue1fTTrN2afGVO/Vk5wUsAZDjw9ZyI="></script><script>
//<![CDATA[
      window.Shopify = window.Shopify || {};
      window.Shopify.Checkout = window.Shopify.Checkout || {};
      window.Shopify.Checkout.apiHost = "materialize-themes.myshopify.com";
      window.Shopify.Checkout.rememberMeHost = "pay.shopify.com";
      window.Shopify.Checkout.rememberMeAccessToken = "S1hTMXhiemJSSkw1bnhyVnJTMUU0Q1hnbkRjWkxyVlAyazZXdkYwT3AxRm9KeTU5cjBFTmNxQVE3VXp5S1FUVi0tUFZVbG1wa1p4NnhVckxieWhBUHZ4Zz09--483db0d090860cf9c8bdacbf5a78b3e7515f8d05";
      window.Shopify.Checkout.sheetStyleSheetUrl = "\/\/cdn.shopify.com\/s\/assets\/shared\/sheet\/main-041a6790c0b4b830edefcf985de6f9d99546e1f0eeebadee9bf1bff653a66b10.css";

//]]>
</script>
<script>
//<![CDATA[
window.ShopifyPaypalV4VisibilityTracking = true;
//]]>
</script>

<style media="all">.additional-checkout-button{border:0 !important;border-radius:5px !important;display:inline-block;margin:0 0 10px;padding:0 24px !important;max-width:100%;min-width:150px !important;line-height:44px !important;text-align:center !important}.additional-checkout-button+.additional-checkout-button{margin-left:10px}.additional-checkout-button:last-child{margin-bottom:0}.additional-checkout-button span{font-size:14px !important}.additional-checkout-button img{display:inline-block !important;height:1.3em !important;margin:0 !important;vertical-align:middle !important;width:auto !important}@media (max-width: 500px){.additional-checkout-button{display:block;margin-left:0 !important;padding:0 10px !important;width:100%}}.additional-checkout-button--apple-pay{background-color:#000 !important;color:#fff !important;display:none;font-family:-apple-system, &#39;Helvetica Neue&#39;, sans-serif !important;min-width:150px !important;white-space:nowrap !important}.additional-checkout-button--apple-pay:hover,.additional-checkout-button--apple-pay:active,.additional-checkout-button--apple-pay:visited{color:#fff !important;text-decoration:none !important}.additional-checkout-button--apple-pay .additional-checkout-button__logo{background:-webkit-named-image(apple-pay-logo-white) center center no-repeat !important;background-size:auto 100% !important;display:inline-block !important;vertical-align:middle !important;width:3em !important;height:1.3em !important}@media (max-width: 500px){.additional-checkout-button--apple-pay{display:none}}.additional-checkout-button--paypal-express{background-color:#ffc439 !important}.additional-checkout-button--paypal{vertical-align:top;line-height:0 !important;margin:0 !important;padding:0 !important}.additional-checkout-button--amazon{background-color:#fad676 !important;position:relative !important}.additional-checkout-button--amazon .additional-checkout-button__logo{-webkit-transform:translateY(4px) !important;transform:translateY(4px) !important}.additional-checkout-button--amazon .alt-payment-list-amazon-button-image{max-height:none !important;opacity:0 !important;position:absolute !important;top:0 !important;left:0 !important;width:100% !important;height:100% !important}.additional-checkout-button-visually-hidden{border:0 !important;clip:rect(0, 0, 0, 0) !important;clip:rect(0 0 0 0) !important;width:1px !important;height:1px !important;margin:-2px !important;overflow:hidden !important;padding:0 !important;position:absolute !important}
</style>
  <link rel="canonical" href="https://themes.materializecss.com/pages/no-image">
</head>

  <body>

    <!-- Navbar and Header -->
    <nav class="nav-extended ao darken-1">
      <div class="nav-background">
        <div class="ea k" style="background-image: url('//cdn.shopify.com/s/files/1/1775/8583/t/1/assets/icon-seamless.png?9863881460345178995');"></div>
      </div>
      <div class="nav-wrapper db">
        <a href="/" class="brand-logo"><i class="material-icons">camera</i>Test pay Ven</a>
        <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
        <!-- <ul class="bt hide-on-med-and-down">
          <li><a href="/orders.list.php">List orders</a></li>
        </ul> -->

      </div>
    </nav>
    <ul class="side-nav" id="nav-mobile">
      <li><a href="/"><i class="material-icons">camera</i>Home</a></li>
      <li class="k"><a href="/orders.list.php"><i class="material-icons">edit</i>List orders</a></li>
    </ul>

    <div class="row center-align">
    <form class="col s12" action="pay.form.php">
      <div class="row">
        <div class="col s12">
          How many veins do you want to buy?
          <div class="input-field inline">
            <input id="ven" type="number" class="validate" name="ven">
            <label for="ven" data-error="wrong" data-success="right">Ven</label>
          </div>
          <div class="input-field inline">
            <input class="btn waves-effect waves-light" type="submit" value="Pay">
          </div>
        </div>
      </div>
    </form>
  </div>
    <style>
      span.test {
        font-weight: 300;
        font-size: 0.8rem;
        color: #fff;
        background-color: #26a69a;
        border-radius: 2px;
      }
    </style>
    <div id="portfolio" class="cx gray">
      <div class="db">
      
        <div class="b e gallery-collection">

          <?php foreach ($items as $order) : 
            $ank = getUser($order['user_id']);
          ?>
          <div class="d ii offset-m2 gu gallery-item gallery-expand ce">
            <div class="gallery-curve-wrapper">
              <div class="gallery-header">
                <span><?= $ank['username'] ?></span>
                <a href="#!" class="collection-item"><span class="test badge blue"><?= $order['count_ven'] ?></span></a>
                <a href="#!" class="collection-item"><span class="<?= 'Cancelled' == $order['state'] ? 'test badge red' : 'badge' ?>"><?= $order['state'] ?></span></a>
              </div>
              <div class="gallery-body">
                <div class="title-wrapper">
                  <h3><?= $ank['username'] ?></h3>
                </div>
                <p class="fi">
                  <b><?= $ank['username'] ?></b> bought <em><?= $order['count_ven'] ?></em> Ven
                </p>
                <p class="fi">
                  <b>paymentID: </b><?= $order['paymentID'] ?><br>
                  <b>merchantTransactionID: </b><?= $order['id'] ?><br>
                </p>
                <p class="fi">
                  <?php
                  // $kalixa = new Kalixa('getPayments');
                  // $kalixa->xml->merchantID = merchantID;
                  // $kalixa->xml->shopID = shopID;
                  // $kalixa->xml->merchantTransactionID = $order['merchantTransactionID'];

                  // $response = $kalixa->getResponse();
                  // dump($response);
                  ?>
                </p>
              </div>
              <div class="gallery-action">
              <a class="btn-floating btn-large waves-effect waves-light" href="./payment.action.php?paymentID=<?= $order['paymentID'] ?>&amp;order_id=<?= $order['id'] ?>"><i class="material-icons">info</i></a>
              <!-- <a class="btn-floating btn-large waves-effect waves-light" href="./payment.status.php?merchantTransactionID=<?= $order['merchantTransactionID'] ?>&amp;order_id=<?= $order['id'] ?>"><i class="material-icons">info</i></a> -->
                <?php if ('Cancelled' != $order['state']): ?>
                  <a class="btn-floating btn-large waves-effect waves-light" href="./payment.cencel.php?paymentID=<?= $order['paymentID'] ?>&amp;order_id=<?= $order['id'] ?>"><i class="material-icons">do_not_disturb_alt</i></a>
                <?php endif ?>
              </div>
            </div>
          </div>
          <?php endforeach ?>

        </div>

      </div>

    </div><!-- /.container -->


    <!-- Core Javascript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/materialize/0.98.0/js/materialize.min.js"></script>
    <script src="//cdn.shopify.com/s/files/1/1775/8583/t/1/assets/gallery.min.opt.js?9863881460345178995" crossOrigin="anonymous"></script>

  </body>
</html>
