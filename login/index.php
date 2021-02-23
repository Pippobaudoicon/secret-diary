<?php
    session_start();

    $error = "";    

    if (array_key_exists("logout", $_GET)) {
        session_destroy();
        unset($_SESSION);
        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";
        header("location: index.php");
        
    } else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
        
        header("Location: loggedinpage.php");
        
    }

    if (array_key_exists("submit", $_POST)) {
        
       include_once '../assets/php/connection.php';
        
        if (!$_POST['email']) {
            
            $error .= "Il campo email è obbligatorio<br>";
            
        } 
        
        if (!$_POST['password']) {
            
            $error .= "Il campo password è obbligatorio<br>";
            
        } 
        
        if ($error != "") {
            
            $error = "<p>Ci sono stati degli errori</p>".$error;
            
        } else {
            
            if ($_POST['signUp'] == '1') {
            
                $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

                $result = mysqli_query($link, $query);
               
                if ($result && mysqli_num_rows($result) > 0) {
                        
                    $error = "Email inserita è gia stata presa";

                } else {

                    $query = "INSERT INTO `users` (`email`, `password`, `active`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."', 0)";
                
                    if (!mysqli_query($link, $query)) {

                        $error = "<p>Ci sono stati degli errori del server.Riprovare più tardi.</p>";

                    } else {
                        $id = mysqli_insert_id($link);
                        $password =password_hash($id .$_POST['password'], PASSWORD_DEFAULT  );
                        $query = "UPDATE `users` SET password = '".$password."' WHERE id = ".$id." LIMIT 1";
                       
                        mysqli_query($link, $query);

                        $_SESSION['id'] =  $id;

                        if (isset($_POST['stayLoggedIn']) && $_POST['stayLoggedIn'] == '1') {

                            setcookie("id", $id, time() + 60*60*24*30);

                        } 

                        $email = mysqli_real_escape_string($link, $_POST['email']);

                        require_once 'sendEmail.php';

                    }

                } 
                
            } else {
                    
                    $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                
                    $result = mysqli_query($link, $query);
                
                    $row = mysqli_fetch_array($result);
                
                    if (isset($row)) {
                        
                        $password = $row['id'].$_POST['password'];

                        $active = $row['active'];
                        
                        if (password_verify( $password , $row['password']) && $active  == '1') {
                            
                            $_SESSION['id'] = $row['id'];
 
                            
                            if (!empty($_POST['stayLoggedIn'])) {

                                setcookie("id", $row['id'], time() + 60*60*24*30);

                            } 

                            header("Location: loggedinpage.php");
                                
                        } else {
                            
                            $error = "That email/password combination could not be found, or you haven't confirmed your email.";
                            
                        }
                        
                    } else {
                        
                        $error = "La combinazione email/password non esiste";
                        
                    }
                    
                }
            
        }
        
        
    }

include '../assets/php/header.php';
?>

<div class="background">
    <div class="container logInContainer" id="homePageContainer">
        <div id="error">
            <?php 

            if($error!=''){

            echo '  <div class="alert alert-danger">'.$error.'</div>';
                
            }
            ?></div>
        <a class="brand" href="../index.html"><h1>Secret Diary</h1></a>
        <p><strong>Store your thoughts permanently and securely.</strong></p>
        <div id="error"></div>
        <form method="post" id = "signUpForm">
            <p>Interested? Sign up now.</p>
            <fieldset class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Your Email">
            </fieldset>
            <fieldset class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Password">
            </fieldset>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="stayLoggedIn" value=1> Remember Me
                </label>
            </div>
            <fieldset class="form-group">
                <input type="hidden" name="signUp" value="1">
                <input class="btn btn-head rounded" type="submit" name="submit" value="Sign Up!">
            </fieldset>
            <p><a class="toggleForms toggleButton" href="#">Log in</a></p>
        </form>
        <form method="post" id = "logInForm">
            <p>Log in using your username and password.</p>
            <fieldset class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Your Email">
            </fieldset>
            <fieldset class="form-group">
                <input class="form-control"type="password" name="password" placeholder="Password">
            </fieldset>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="stayLoggedIn" value=1> Remember Me
                </label>
            </div>
            <input type="hidden" name="signUp" value="0">
            <fieldset class="form-group">
                <button class="btn btn-head rounded" type="submit" name="submit">Log In!</button>
            </fieldset>
            <p><a class="toggleForms toggleButton">Sign up</a></p>
        </form>
    </div>
</div>
<?php
include '../assets/php/footer.php';