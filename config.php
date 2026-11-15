<?php

define("FIREBASE_API_KEY", "AIzaSyAvcZ147Ih9yx22I-J0nHZI-4ZR2p4L0Bc");

define(
    "FIREBASE_URL",
    "https://klick-mark-default-rtdb.firebaseio.com"
);

// Awtomatikong mag-aadjust kung nasa localhost o live sa Render
$baseUrl = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) ? "/KLICK%20MARK" : "";
define("BASE_URL", $baseUrl);

?>