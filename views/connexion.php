<?php
$emailErreur = "";
$motDePasseErreur = "";
if (isset($_POST['se_connecter'])) {
    if (empty($_POST['email'])) {
        $emailErreur = "Veuillez entrer votre email.";
    }
    if (empty($_POST['motdepasse'])) {
        $motDePasseErreur = "Veuillez entrer votre mot de passe.";
    }
    if (empty($emailErreur) && empty($motDePasseErreur)) {
        connexionAdmin($_POST['email'], $_POST['motdepasse']); 
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background: #f7f7f7;
        }
        .card {
            border: none;
            border-radius: 15px;
        }
        .card img {
            border-radius: 15px 0 0 15px;
        }
        .btn-primary {
            background: #007bff;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-size: 16px;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .form-control {
            border-radius: 50px;
            padding: 20px;
            font-size: 16px;
        }
        .form-control:focus {
            box-shadow: none;
        }
        .text-muted {
            font-size: 14px;
        }
        .small {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <section class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0">
                    <div class="row no-gutters">
                        <div class="col-lg-5 d-none d-lg-block">
                            <img src="./public/image.png" class="img-fluid h-100" alt="Image">
                        </div>
                        <div class="col-lg-7">
                            <div class="card-body p-5">
                                <h2 class="text-center font-weight-light mb-4">Se connecter</h2>
                                <div class="text-center mb-4">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                                <p class="text-center text-muted mb-4">Connectez-vous à votre compte pour accéder à votre espace personnel</p>
                                <form method="post">
                                    <div class="form-group">
                                        <input type="email" placeholder="Entrer votre mail" class="form-control my-3 p-4" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>">
                                        <span class="text-danger"><?php echo $emailErreur; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" placeholder="Entrer votre mot de passe" class="form-control my-3 p-4" name="motdepasse">
                                        <span class="text-danger"><?php echo $motDePasseErreur; ?></span>
                                    </div>
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary btn-block py-2" name="se_connecter">Se connecter</button>
                                    </div>
                                    <div class="text-center">
                                        <a href="?page=motdepasse" class="d-block small">Mot de passe oublié</a>
                                        <p class="d-inline">Vous n'avez pas de compte ?</p>
                                        <a href="?page=inscription" class="d-inline small"> S'inscrire</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
