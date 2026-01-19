<?php
session_set_cookie_params([
    'lifetime' => 0,           //cookie dies when browser closes.
    'path' => '/',             //cookie available for entire website.
    'domain' => '',            //current domain only
    'secure' => false,         //cookie can be sent only over https.
    'httponly' => true,        // Prevent JavaScript access to session cookies (JS cannot read your cookie).
    'samesite' => 'Lax'        // Reduce risk of CSRF attacks (another site cannot send request).
]);

session_start();
?>