<?php
/*
* Handle an order status change using the Mollie API.
*/
try {

  require_once __DIR__ . "/Mollie/vendor/autoload.php";
  require_once __DIR__ . "/resources/log.php";
  require_once __DIR__ . "/resources/slack.php";
  require_once __DIR__ . "/resources/mail.php";


      // require_once __DIR__ . "/functions.php";

/*
* Initialize the Mollie API library with your API key.
*
* See: https://www.mollie.com/dashboard/developers/api-keys
*/
    $mollie = new \Mollie\Api\MollieApiClient();
    $mollie->setApiKey("live_5myAsvKxUNxz9JNFzjHGnRhvjrKpMT");

    /*
    * After your webhook has been called with the order ID in its body, you'd like
    * to handle the order's status change. This is how you can do that.
    *
    * See: https://docs.mollie.com/reference/v2/orders-api/get-order
    */
    if(!isset($_POST["id"])){
      echo "Dit is niet een pagina waar je hoort te zijn!";

      exit;


    }

    $order = $mollie->orders->get($_POST["id"]);
    $orderId = $order->orderNumber;

    if ($order->isPaid()) {

        if(!$order->metadata->mailsent){

        $moment = $order->metadata->moment;
        $to = $order->shippingAddress->email;
        // $subject = "Je bestelling is binnen!";
        // $message = "Beste ".$name.", \n Hartelijk dank voor jouw bestelling bij WXNDER. We gaan zo snel mogelijk hard voor je aan de slag om een prachtige box voor je in elkaar te zetten. \n Je hebt gekozen voor een levering op ".$leverdatum.". Zodra we weten hoelaat de box wordt geleverd ontvang je daarover meteen een mailtje. \n\n\n Bijgevoegd vind je de factuur voor uw bestelling.";

        // wp_mail($to, $subject, $message);
        $name = $oder->metadata->name;

        // //call make folder
        // $APPS_SCRIPT_URL = "https://script.google.com/macros/s/AKfycbzTjzg2sidZ7NsZMxj65ntLU3zI6fay6ay6tv2zhiHwtDRoElwK/exec?id=".$orderId;


        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $APPS_SCRIPT_URL);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        // $content = curl_exec($ch);
        // curl_close($ch);

        // //call send email
        slack("$orderId: Bestelling betaald", "#verkoop");
        logMessage($orderId, "Bestelling betaald");
        $mysqli = new mysqli("rdbms.strato.de","U4442376","Wandeling2020!","DB4442376");
        $updateSQL = "UPDATE Bestellingen SET Betaald = NOW() WHERE ID = $orderId";
        $resultUpdate = $mysqli->multi_query($updateSQL);
        if(!($resultUpdate === TRUE)) {
          echo "Er is iets misgegaan met jouw betaling! Neem contact op met info@wxnder.nl en we lossen het zo snel mogelijk voor je op.";
          echo $mysqli->error;
          slack("ERROR: Update betaling foutgegaan\n$orderId", "#verkoop");
        }
        $email = '';
        $name = '';
        $getEmailSQL = "SELECT * FROM `Gebruikers` WHERE ID = $orderId";
        $resultEmail = $mysqli->query($getEmailSQL);
        if( $resultEmail->num_rows > 0){
          while ($row = $resultEmail->fetch_assoc()) {
            // echo $leverRow['ID'];
            $email = $row['Email'];
            $name = $row['Voornaam'];
          }
        }
        $getBestelSQL = "SELECT * FROM `Bestellingen` WHERE ID = $orderId";
        $resultBestel = $mysqli->query($getBestelSQL);
        if( $resultBestel->num_rows > 0){
          while ($row = $resultBestel->fetch_assoc()) {
            // echo $leverRow['ID'];
            $product = $row['Product'];
            $prijs = $row['Prijs'];
            $info = $row['ProductSpecificatie'];
            $adres = $row['LeverAdres']." ". $row['LeverPostcode']." ". $row['LeverPlaats'];
          }
        }

        $bevestiging= bestelBevestiging($email,$name,$orderId);
        logMessage($orderId, "Bestelbevestiging gestuurd $bevestiging");

        $bevestigingIntern = bestelBevestigingIntern($email , $name, $orderId, $product, $prijs, $adres, $info);
        logMessage($orderId, "Interne bevestiging gestuurd $bevestigingIntern");

        $sql= "SELECT * FROM `Deelbestellingen` WHERE orderID = $orderId";
        $result = $mysqli->query($sql);
        $updateSQL = '';
        if($result->num_rows > 0){
          while($row = $result->fetch_assoc()){
            $productID = $row['ProductID'];
            $aantal = $row['Aantal'];
            $updateSQL.="UPDATE `Voorraad` SET `Huidige-voorraad` = `Huidige-voorraad` - $aantal WHERE ID= $productID; ";
          }
        }
        $resultUpdate = $mysqli->multi_query($updateSQL);
        if(!($resultUpdate == TRUE)){
          slack("$orderId: ERROR: voorraad verrekenen is misgegaan", "#voorraad");
        }
        else{
          logMessage($orderId, "Voorraad verrekend");
          slack("$orderId: Voorraad verrekend", "#voorraad");
        }



        $order->metadata->mailsent->true;
        $order->update();
        }
        /*
        * The order is paid or authorized
        * At this point you'd probably want to start the process of delivering the product to the customer.
        */
    } elseif ($order->isCanceled()) {
        /*
        * The order is canceled.
        */
        slack("$orderId: Betaling geannuleerd", "#verkoop");
        logMessage($orderId, "Bestelling geannuleerd");
    } elseif ($order->isExpired()) {
        /*
        * The order is expired.
        */
        slack("$orderId: Betaling verlopen", "#verkoop");
        logMessage($orderId, "Bestelling verlopen");

    } elseif ($order->isCompleted()) {
        /*
        * The order is completed.
        */
    } elseif ($order->isPending()) {
        /*
        * The order is pending.
        */
    }
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
}




?>
