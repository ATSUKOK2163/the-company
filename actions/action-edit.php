<?php
    include "../classes/User.php";

    $user = new User;
    $user->update($_POST, $_FILES);
    #Note; $_POST -- holds the data (firstname, lastname, username)
    #$_FILES -- holds the files (eg images files updated by the user)
?>