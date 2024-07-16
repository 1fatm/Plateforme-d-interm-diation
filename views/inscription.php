<?php

$prenomErreur = "";
$emailErreur = "";
$nomErreur = "";
$nomEntrepriseErreur = "";
$motDePasseErreur = "";

if (isset($_POST['inscrire'])) {

    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $nom_entreprise = htmlspecialchars(trim($_POST['nom_entreprise']));
    $motdepasse = htmlspecialchars(trim($_POST['motdepasse']));

    
    if (empty($email)) {
        $emailErreur = "Veuillez entrer votre email.";
    }

    if (empty($motdepasse)) {
        $motDePasseErreur = "Veuillez entrer votre mot de passe.";
    }
    if (empty($nom)) {
        $nomErreur = "Veuillez entrer votre nom.";
    }
    if (empty($prenom)) {
        $prenomErreur = "Veuillez entrer votre prenom.";
    }
    if (empty($nom_entreprise)) {
        $nomEntrepriseErreur = "Veuillez entrer le nom de votre entreprise.";
    }

    
    if (empty($emailErreur) && empty($motDePasseErreur) && empty($nomEntrepriseErreur) && empty($nomErreur) && empty($prenomErreur)) {
        insertion_base_de_données($nom, $prenom, $email, $nom_entreprise, $motdepasse);
    
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    
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
                                <h2 class="text-center font-weight-light mb-4">S'inscrire</h2>
                                <div class="text-center mb-4">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                                <form method="post">
                                    <div class="form-group">
                                        <input type="text" placeholder="Entrer votre Nom" class="form-control my-3 p-4" name="nom" value="<?php echo isset($_SESSION['nom']) ? htmlspecialchars($_SESSION['nom']) : ''; ?>">
                                        <span class="text-danger"><?php echo $nomErreur; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Entrer votre prenom" class="form-control my-3 p-4" name="prenom" value="<?php echo isset($_SESSION['prenom']) ? htmlspecialchars($_SESSION['prenom']) : ''; ?>">
                                        <span class="text-danger"><?php echo $prenomErreur; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" placeholder="Entrer votre mail" class="form-control my-3 p-4" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                        <span class="text-danger"><?php echo $emailErreur; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Entrer le nom de votre entreprise" class="form-control my-3 p-4" name="nom_entreprise" value="<?php echo isset($_SESSION['nom_entreprise']) ? htmlspecialchars($_SESSION['nom_entreprise']) : ''; ?>">
                                        <span class="text-danger"><?php echo $nomEntrepriseErreur; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" placeholder="Entrer un mot de passe" class="form-control my-3 p-4" name="motdepasse">
                                        <span class="text-danger"><?php echo $motDePasseErreur; ?></span>
                                    </div>
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary btn-block py-2" name="inscrire">S'inscrire</button>
                                    </div>
                                    <div class="text-center">
                                        <p class="d-inline">Vous avez déjà un compte ?</p>
                                        <a href="?page=connexion" class="d-inline small"> Se connecter</a>
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
