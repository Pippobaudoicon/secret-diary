<?php

    require_once '../assets/php/connection.php';

    if($_GET['email']){
        $query = "UPDATE `users` SET active = '1'  WHERE email = '".$_GET['email']."'";
        mysqli_query($link, $query);
        echo 'you have been confirmed';
    }else{
        echo 'make sure you registered correctly';
    }

    
   

?>
<br>
<a href="./index.php">start writing</a>
<br>
