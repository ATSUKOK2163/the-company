<?php
    include "../classes/User.php"; //this is the class file that contains the logics of the app

    #Create an object
    $user = new User; //the "User" is the class file

    #Call the store method
    $user->store($_POST);
    //Note; THe $_POST ---  holds the data from the form


?>