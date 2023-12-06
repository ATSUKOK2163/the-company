<?php
    include "../classes/User.php";


    #Create an object
    $user = new User;

    #Call the login method
    $user->login($_POST);
    #$_POST holds the data coming form the login form
    
?>