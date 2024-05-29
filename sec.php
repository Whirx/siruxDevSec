<?php
error_reporting(0);

if (isset($_GET['url'])) {
    $url = $_GET['url'];

if (!preg_match("#^https?://#i", $url)) {
        $url = "https://$url";
    }

    function display_sourcecode($url)
    {
        $lineas = file($url);
        $output = "";
        foreach ($lineas as $line_num => $linea) {
            $output .= "Line #<b>{$line_num}</b> : " . htmlspecialchars($linea) . "<br>\n";
        }
        return $output;
    }

    function detect_payment_gateways($url)
    {
        $headers  = json_encode(get_headers($url));
        $response = display_sourcecode($url);

        $gateways = [];
        $seguridad = [];

        $response = strtolower($response);

       
            if (strpos($response, 'stripe') !== false) {
                array_push($gateways, 'Stripe');
            }
            if (strpos($response, 'https://www.paypal.com/sdk/') !== false) {
                array_push($gateways, 'Paypal');
            }
            if (strpos($response, 'braintree') !== false) {
                array_push($gateways, 'Braintree');
            }
            if (strpos($response, 'adyen.com') !== false) {
                array_push($gateways, 'Adyen');
            }
            if (strpos($response, 'woocommerce') !== false) {
                array_push($gateways, 'Woocommerce');
            }
            if (strpos($response, 'payflow') !== false) {
                array_push($gateways, 'Payflow');
            }
            if (strpos($response, 'myshopify.com') !== false) {
                array_push($gateways, 'Shopify');
            }
            if (strpos($response, 'klarna') !== false) {
                array_push($gateways, 'Klarna');
            }
            if (strpos($response, 'cybersourse') !== false) {
                array_push($gateways, 'Cybersourse');
            }
            if (strpos($response, 'payyezy') !== false) {
                array_push($gateways, 'Payeezy');
            }
            if (strpos($response, 'authorize.net') !== false) {
                array_push($gateways, 'Authorize.net');
            }
            if (strpos($response, '2checkout') !== false) {
                array_push($gateways, '2Checkout');
            }
            if (strpos($response, 'squareup') !== false) {
                array_push($gateways, 'squareup');
            }
            if (strpos($response, 'amazon pay') !== false) {
                array_push($gateways, 'Amazon pay');
            }
            if (strpos($response, 'worldpay') !== false) {
                array_push($gateways, 'Worldpay');
            }
    
            if(strpos($headers, 'CF-RAY') !== false){
                array_push($seguridad, 'Cloudflare'); 
            }
            if(strpos($headers, 'Akamai') !== false || strpos($headers, 'akamai') !== false){
                array_push($seguridad, 'Akamai'); 
            }
            if(strpos($headers, 'sucuri') !== false){
                array_push($seguridad, 'Sucuri'); 
            }
            if(strpos($headers, 'X-DataDome: protected') !== false){
                array_push($seguridad, 'X-DataDome'); 
            }
            if(strpos($headers, 'recaptcha') !== false || strpos($response, 'recaptcha')){
                array_push($seguridad, 'reCaptcha'); 
            }
            if(empty($gateways)){
                array_push($gateways, 'No se encontraron pasarelas'); 
            }
            if(empty($seguridad)){
                array_push($seguridad, 'No se encontraron seguridades'); 
            }

        if (empty($gateways)) {
            array_push($gateways, 'No se encontraron pasarelas');
        }
        if (empty($seguridad)) {
            array_push($seguridad, 'No se encontraron seguridades');
        }
        $gateways = implode(", ", $gateways);
        $seguridad = implode(", ", $seguridad);

        $result = [
            'gateways' => $gateways,
            'seguridad' => $seguridad,
        ];

        return json_encode($result);
    }

    $site = detect_payment_gateways($url);
    echo $site;
} else {
    echo json_encode(['error' => 'Missing URL parameter']);
}
?>
