<?php 
include './include/header.php' ?>
<body>
    <?php
        session_start();
        include './core/fonctions.php';


        
        if (isset($_GET['page'])) {
            require './views/' . $_GET['page'] . '.php';
        } else {
            require './views/connexion.php';
        }
    ?>

</body>
<?php include "./include/footer.php"; ?>