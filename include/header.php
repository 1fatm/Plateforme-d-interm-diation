
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel=" icon" href="téléchargement.icon" type="image/x-icon">
    <title>Page de connection</title>

     <style>
         body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: white;
              
        }
        .error-message {
            color: red;
        }
        .btn1 {
            background-color: green;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .btn1:hover {
            background-color: #0056b3;
        }
        footer {
            position: fixed;
            bottom: 5px;
            width: 100%;
            background-color: white;
            color: black;
            padding: 10px 20px;
            text-align: center;
            z-index: 50px;
        
    
        }
        .image-container {
            height: 100vh;
            overflow: hidden;
        
        }
        .image-container img {
        
           
            width: 100;
            height: 100%;
            border-radius: 10px;
            object-fit:contained;
        }
        .row
        {
            background:white;
            border-radius: 12px;
        }
        .message 
        {
            color: red;
            font-weight: bold;
            font-size: 20px;
            text-align: center;
            margin-top: 20px;
        }
        .messagereussie
        {
            color: green;
            font-weight: bold;
            font-size: 20px;
            text-align: center;
            margin-top: 20px;
        }
        .lien
        {
            color:black;
            font-weight: bold;
            font-size: 20px;
            text-align: center;
            margin-top: 20px;
        }
        .icon-container {
    display: flex;
    justify-content: center;
    position: relative;
    top: -20px; 
}

.icon-center {
    background: white;
    padding: 0 10px; 
    position: relative;
    z-index: 1;
}

hr {
    border: none;
    border-top: 1px solid #ccc;
    position: relative;
}

    </style>    
</head>
<body>
<?php
    if (isset($_SESSION['message'])) {
        echo '<div class="message">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']); 
    }
 ?>