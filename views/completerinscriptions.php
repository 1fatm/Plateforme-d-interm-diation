<?php


$emailErreur = "";
$dateCreationErreur = "";
$numIdentificationFiscaleErreur = "";
$registreCommerceErreur = "";
$nombreEmployesErreur = "";
$chiffreAffaireErreur = "";
$logoErreur = "";

$file_name = ""; 

if (isset($_POST['completer_profil'])) {
    
    
    if (empty($_POST['date_creation'])) {
        $dateCreationErreur = "Veuillez entrer la date de création de l'entreprise.";
    }
    if (empty($_POST['num_identification_fiscale'])) {
        $numIdentificationFiscaleErreur = "Veuillez entrer le numéro d'identification fiscale.";
    }
    if (empty($_POST['registre_commerce'])) {
        $registreCommerceErreur = "Veuillez entrer le numéro de registre de commerce.";
    }
    if (empty($_POST['nombre_employes'])) {
        $nombreEmployesErreur = "Veuillez entrer le nombre d'employés.";
    }
    if (empty($_POST['chiffre_affaire'])) {
        $chiffreAffaireErreur = "Veuillez entrer le chiffre d'affaires.";
    }
    
    if (!empty($_FILES['logo']['name'])) {
        $file_name = $_FILES['logo']['name'];
        $file_size = $_FILES['logo']['size'];
        $file_tmp = $_FILES['logo']['tmp_name'];
        $file_type = $_FILES['logo']['type'];
        
        
        $file_ext_array = explode('.', $_FILES['logo']['name']);
        $file_ext = strtolower(end($file_ext_array));
        $extensions = array("jpeg", "jpg", "png");
        
        if (in_array($file_ext, $extensions) === false) {
            $logoErreur = "Extension de fichier non autorisée, veuillez choisir une image JPEG ou PNG.";
        }
        
        if ($file_size > 2097152) {
            $logoErreur = 'La taille du fichier doit être inférieure à 2 Mo';
        }
        
        if (empty($logoErreur)) {
            
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }
            
            $file_name = uniqid() . '-' . $file_name;
            move_uploaded_file($file_tmp, "uploads/" . $file_name);
        }
    } else {
    
        $logoErreur = "Veuillez télécharger une image.";
    }
    

    if (empty($dateCreationErreur) && empty($numIdentificationFiscaleErreur) && empty($registreCommerceErreur) && empty($nombreEmployesErreur) && empty($chiffreAffaireErreur) && empty($logoErreur)) {
        $logo = $file_name; 
        $date_creation = $_POST['date_creation'];
        $num_identification_fiscale = $_POST['num_identification_fiscale'];
        $registre_commerce = $_POST['registre_commerce'];
        $nombre_employes = $_POST['nombre_employes'];
        $chiffre_affaire = $_POST['chiffre_affaire'];
        
        completerinscription($date_creation, $num_identification_fiscale, $registre_commerce, $nombre_employes, $chiffre_affaire, $logo);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compléter le Profil</title>
    
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
                                <h2 class="text-center font-weight-light mb-4">Compléter le Profil</h2>
                                <div class="text-center mb-4">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                                <form method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="date_creation"> Date de création de l'entreprise</label>
                                        <input type="date" placeholder="Date de création de l'entreprise" class="form-control my-3 p-4" name="date_creation" value="<?php echo isset($_POST['date_creation']) ? htmlspecialchars($_POST['date_creation']) : ''; ?>">
                                        <span class="text-danger"><?php echo $dateCreationErreur; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Numéro d'identification fiscale" class="form-control my-3 p-4" name="num_identification_fiscale" value="<?php echo isset($_POST['num_identification_fiscale']) ? htmlspecialchars($_POST['num_identification_fiscale']) : ''; ?>">
                                        <span class="text-danger"><?php echo $numIdentificationFiscaleErreur; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Numéro de registre de commerce" class="form-control my-3 p-4" name="registre_commerce" value="<?php echo isset($_POST['registre_commerce']) ? htmlspecialchars($_POST['registre_commerce']) : ''; ?>">
                                        <span class="text-danger"><?php echo $registreCommerceErreur; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" placeholder="Nombre d'employés" class="form-control my-3 p-4" name="nombre_employes" value="<?php echo isset($_POST['nombre_employes']) ? htmlspecialchars($_POST['nombre_employes']) : ''; ?>">
                                        <span class="text-danger"><?php echo $nombreEmployesErreur; ?></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" placeholder="Chiffre d'affaires" class="form-control my-3 p-4" name="chiffre_affaire" value="<?php echo isset($_POST['chiffre_affaire']) ? htmlspecialchars($_POST['chiffre_affaire']) : ''; ?>">
                                        <span class="text-danger"><?php echo $chiffreAffaireErreur; ?></span>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="logo">Télécharger le logo de l'entreprise</label>
                                        <input type="file" class="form-control-file" id="logo" name="logo">
                                        <span class="text-danger"><?php echo $logoErreur; ?></span>
                                    </div>
                                    
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-primary btn-block py-2" name="completer_profil">Valider</button>
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