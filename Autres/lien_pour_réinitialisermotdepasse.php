,<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Page de connection</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
         background:white;
        }
        
        img
        {
            width: 1000px;
            height: 200px;
          
        }
        .btn1
        {
            border:none;
            outline: none;
            height: 50px;
            width: 100%;
            background-color: black;
            color: white;
            border-radius: 4px;
            font-weight: bold;
        }
        .btn1:hover
        {
            background-color: white;
            color: black;
            border: 1px solid black;    
        }
        .row
        {
           
            height: 100vh;
        }       
    </style>
<body>
    
    <?php
    if(isset($_POST["reinitialiser"])){

    include("fonction1.php");
    include("motdepasse.php");
    connection_base_de_données();
    reinitialiser_mot_de_passe($_Post["email"],$_Post["nouveau_mot_de_passe"]);


    


    
    }
    

    ?>

</body>
</html>