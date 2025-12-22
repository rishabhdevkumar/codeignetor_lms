<?php 

function encodedSalt($plaintext) {
    $salt = 'ABC596';
    return base64_encode($salt . $plaintext);
}
function decodeedSalt($encodedString) {
    $salt = 'ABC596';
    $decoded = base64_decode($encodedString);
    return str_replace($salt, '', $decoded);
}