<?php

    session_start();

    if (array_key_exists("id", $_COOKIE)) {
        
        $_SESSION['id'] = $_COOKIE['id'];
        
    }

    if (array_key_exists("id", $_SESSION)) {
        
      
        include '../assets/php/connection.php';
        $diary='';
        $sql ="SELECT `diary`,`email` FROM users WHERE id=".(int)$_SESSION['id'];
       
        $query= mysqli_query($link,$sql);
        $row = mysqli_fetch_array($query);
        $diary = $row['diary'];
        $email = $row['email'];

        $name = explode('@', $email);
        $left_part = $name[0];
        $left_part = preg_replace('/[^a-zA-Z0-9_ -%][().][\/]/s', '', $left_part);
        $left_part = str_replace('.', ' ', $left_part);
    } else {
        
        header("Location: index.php");
        
    }

    include '../assets/php/headerDiary.php';

    ?>

    <header>
        <div class="hero">
            <p class="logo" style="z-index: 1;">Welcome <?=$left_part?></p>
            <div class="night">
                <div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div><div class="shooting_star"></div>
            </div>
            <div class="hamb-wrap">
                <div class="hamb"></div>
            </div>
            <nav class="flyinTxtCont">
                <ul>
                <li>
                    <div class="flyIn line"><a href="../index.html">Home</a></div>
                </li>
                <li>
                    <div class="flyIn line"><a href="../index.html#contact">Contact</a></div>
                </li>
                <li>
                    <div class="flyIn line"><a href="./index.php?logout=1">Log Out</a></div>
                </li>
                </ul>
            </nav>
        </div>
    </header>
    <section class="content paperSheet effect8">
        <textarea id="diary" class="form-control"><?=$diary?></textarea>
    </section>
<?php

    include '../assets/php/footer.php';
?>