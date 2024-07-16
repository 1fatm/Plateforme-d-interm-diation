<?php
function connection_base_de_données()
{
    $host = "localhost";
    $user = "root";
    $password = "";
    $base = "plateforme";
    $connexion = mysqli_connect($host, $user, $password, $base);
    if (!$connexion) {
        die("Erreur de connexion à la base de données : " . mysqli_connect_error());
    }
    return $connexion;
}
function insertion_base_de_données($nom, $prenom, $email, $nomEntreprise, $motDePasse, $role = 'user')
{

    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
    $_SESSION['nom_entreprise'] = $nomEntreprise;

    $connexion = connection_base_de_données();

    if (mailexistant($email)) {
        $_SESSION['message'] = 'Cette adresse mail existe déjà.';
        header('Location: ?page=inscription');
        exit();
    } else {
        $motDePasseHache = password_hash($motDePasse, PASSWORD_DEFAULT);
        $requete = "INSERT INTO entreprise (nom, prenom, email, nomEntreprise, mot_de_passe, role) VALUES ('$nom', '$prenom', '$email', '$nomEntreprise', '$motDePasseHache', '$role')";
        $result = mysqli_query($connexion, $requete);
        if (!$result) {
            die("Erreur lors de la requête : " . mysqli_error($connexion));
        } else {
            $entreprise_id = mysqli_insert_id($connexion);
            $_SESSION['entreprise_id'] = $entreprise_id;
            $_SESSION['message'] = 'Inscription réussie.';
            header('Location: ?page=completerinscriptions');
            exit();
        }
    }
}

function getEntrepriseInfo()
{
    $connexion = connection_base_de_données();
    $entreprise_id = $_SESSION['entreprise_id'];
    $requete = "SELECT * FROM entreprise WHERE id = '$entreprise_id'";
    $result = mysqli_query($connexion, $requete);
    if (!$result) {
        die("Erreur lors de la récupération des informations de l'entreprise : " . mysqli_error($connexion));
    }
    return mysqli_fetch_assoc($result);
}
function completerinscription($date_creation, $num_identification_fiscale, $registre_commerce, $nombre_employes, $chiffre_affaire, $logo)
{

    $connexion = connection_base_de_données();
    $entreprise_id = $_SESSION['entreprise_id'];

    if (!$entreprise_id) {
        die("Erreur : ID de l'entreprise non trouvé dans la session.");
    }

    $requete = "UPDATE entreprise 
                SET photo = '$logo', 
                    date_creation = '$date_creation', 
                    numero_fiscal = '$num_identification_fiscale', 
                    registre_commerce = '$registre_commerce', 
                    nbre_employes = '$nombre_employes', 
                    chiffre_affaire = '$chiffre_affaire'
                WHERE id = '$entreprise_id'";

    $result = mysqli_query($connexion, $requete);

    if (!$result) {
        die("Erreur lors de la requête : " . mysqli_error($connexion));
    } else {
        header('Location: ?page=welcome');
        exit();
    }
}


function updateEntreprise($entreprise_id, $date_creation, $numero_fiscal, $registre_commerce, $nbre_employes, $chiffre_affaire, $photo)
{
    $connexion = connection_base_de_données();


    if (!empty($photo['name'])) {
        $photo_nom = basename($photo['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $photo_nom;
        move_uploaded_file($photo['tmp_name'], $target_file);
    } else {

        $photo_nom = '';
    }


    $requete = "UPDATE entreprise SET 
                    photo = '$photo_nom', 
                    date_creation = '$date_creation', 
                    numero_fiscal = '$numero_fiscal', 
                    registre_commerce = '$registre_commerce', 
                    nbre_employes = '$nbre_employes', 
                    chiffre_affaire = '$chiffre_affaire' 
                WHERE id = '$entreprise_id'";

    $resultat = mysqli_query($connexion, $requete);

    if (!$resultat) {
        die("Erreur lors de la mise à jour des informations de l'entreprise : " . mysqli_error($connexion));
    } else {

        header('Location: ?page=welcome');
        exit();
    }
}

function connexionAdmin($email, $motdepasse)
{

    $_SESSION['email'] = $email;
    $connexion = connection_base_de_données();
    $sql = "SELECT id, mot_de_passe, role FROM entreprise WHERE email = '$email'";
    $resultat = mysqli_query($connexion, $sql);

    if ($resultat && mysqli_num_rows($resultat) > 0) {
        $row = mysqli_fetch_assoc($resultat);
        $motdepasse_hache = $row['mot_de_passe'];
        $role = $row['role'];
        $_SESSION['entreprise_id'] = $row['id'];

        if (password_verify($motdepasse, $motdepasse_hache)) {
            if ($role == 'admin') {
                header('Location: ?page=redirectionAdmin');
                exit();
            } else {
                header('Location: ?page=welcome');
                exit();
            }
        } else {
            $_SESSION['message'] = 'Mot de passe incorrect.';
        }
    } else {
        $_SESSION['message'] = 'Vous n\'êtes pas inscrit sur la plateforme.';
    }
    header("Location: ?page=connexion");
    exit();
}
function ppm($intitule, $reference, $date_lancement, $date_attribution, $description, $norme, $typeActivites, $activite, $statut = 'soumis')
{
    $connexion = connection_base_de_données();
    $entreprise_id = $_SESSION['entreprise_id'];
    $intitule = mysqli_real_escape_string($connexion, $intitule);
    $reference = mysqli_real_escape_string($connexion, $reference);
    $date_lancement = mysqli_real_escape_string($connexion, $date_lancement);
    $date_attribution = mysqli_real_escape_string($connexion, $date_attribution);
    $description = mysqli_real_escape_string($connexion, $description);
    $norme = mysqli_real_escape_string($connexion, $norme);
    $typeActivites = mysqli_real_escape_string($connexion, $typeActivites);
    $activite = mysqli_real_escape_string($connexion, $activite);
    $statut = mysqli_real_escape_string($connexion, $statut);

    $requete = "INSERT INTO ppm (intitule, reference, date_lancement, date_attribution, description, norme, typeActivite, activites, entreprise_id, statut) VALUES ('$intitule', '$reference', '$date_lancement', '$date_attribution', '$description', '$norme', '$typeActivites', '$activite', '$entreprise_id', '$statut')";
    $result = mysqli_query($connexion, $requete);

    if (!$result) {
        die("Erreur lors de la requête : " . mysqli_error($connexion));
    } else {
        header('Location: ?page=welcome');
        exit();
    }
}



function recupererPPMUtilisateurConnecte()
{
    $connexion = connection_base_de_données();
    $entreprise_id = $_SESSION['entreprise_id'];
    $requete = "SELECT * FROM ppm WHERE entreprise_id = '$entreprise_id'";
    $result = mysqli_query($connexion, $requete);
    if (!$result) {
        die("Erreur lors de la récupération des PPM : " . mysqli_error($connexion));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function recupererTousLesUtilisateurs()
{
    $connexion = connection_base_de_données();
    $requete = "SELECT * FROM entreprise where role='user'";
    $result = mysqli_query($connexion, $requete);
    if (!$result) {
        die("Erreur lors de la récupération des utilisateurs : " . mysqli_error($connexion));
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// function recupererTousLesPPM() {
//     $connexion = connection_base_de_données();
//     $requete = "SELECT * FROM ppm";
//     $result = mysqli_query($connexion, $requete);
//     if (!$result) {
//         die("Erreur lors de la récupération des PPM : " . mysqli_error($connexion));
//     }
//     return mysqli_fetch_all($result, MYSQLI_ASSOC);
// }

function reinitialiser_mot_de_passe($email, $motdepassenouv)
{
    $connexion = connection_base_de_données();
    $motDePasseNouvHache = password_hash($motdepassenouv, PASSWORD_DEFAULT);
    $requete = "UPDATE entreprise SET mot_de_passe='$motDePasseNouvHache' WHERE email='$email'";
    $resultat = mysqli_query($connexion, $requete);
    if (!$resultat) {
        die("Erreur lors de la réinitialisation du mot de passe : " . mysqli_error($connexion));
    } else {
        $_SESSION['message'] = 'Mot de passe réinitialisé avec succès.';
        header('Location: ?page=connexion');
        exit();
    }
}

function mailexistant($email)
{
    $connexion = connection_base_de_données();
    $sql = "SELECT * FROM entreprise WHERE email = '$email'";
    $resultat = mysqli_query($connexion, $sql);
    mysqli_close($connexion);
    return (mysqli_num_rows($resultat) > 0);
}



function appel_offre()
{
    $connexion = connection_base_de_données();
    $entreprise_id = $_SESSION['entreprise_id'];
    $requete = "INSERT INTO appel_offre (entreprise_id) VALUES ('$entreprise_id')";
    $result = mysqli_query($connexion, $requete);
    if (!$result) {
        die("Erreur lors de la création de l'appel d'offre : " . mysqli_error($connexion));
    } else {
        header('Location: ?page=welcome');
        exit();
    }
}
function AMI()
{
    $connexion = connection_base_de_données();
    $entreprise_id = $_SESSION['entreprise_id'];
    $requete = "INSERT INTO ami (entreprise_id) VALUES ('$entreprise_id')";
    $result = mysqli_query($connexion, $requete);
    if (!$result) {
        die("Erreur lors de la création de l'ami : " . mysqli_error($connexion));
    } else {
        header('Location: ?page=welcome');
        exit();
    }
}
function validerPPM($ppm_id)
{
    $connexion = connection_base_de_données();
    $requete = "UPDATE ppm SET statut = 'validé' WHERE id = '$ppm_id'";
    $resultat = mysqli_query($connexion, $requete);
    if (!$resultat) {
        die("Erreur lors de la validation du PPM : " . mysqli_error($connexion));
    }
}


function rejeterPPM($ppm_id)
{
    $connexion = connection_base_de_données();
    $requete = "UPDATE ppm SET statut = 'rejeté' WHERE id = '$ppm_id'";
    $resultat = mysqli_query($connexion, $requete);
    if (!$resultat) {
        die("Erreur lors du rejet du PPM : " . mysqli_error($connexion));
    }
}
function getPPMDetails($ppm_id)
{
    $connexion = connection_base_de_données();
    $requete = "SELECT * FROM ppm WHERE id = '$ppm_id'";
    $resultat = mysqli_query($connexion, $requete);
    if (!$resultat) {
        die("Erreur lors de la récupération des détails du PPM : " . mysqli_error($connexion));
    }
    return mysqli_fetch_assoc($resultat);
}
function getEntrepriseById($entreprise_id)
{
    $connexion = connection_base_de_données();
    $requete = "SELECT * FROM entreprise WHERE id = '$entreprise_id'";
    $resultat = mysqli_query($connexion, $requete);
    if (!$resultat) {
        die("Erreur lors de la récupération des détails de l'entreprise : " . mysqli_error($connexion));
    }
    return mysqli_fetch_assoc($resultat);
}
function recupererTousLesPPM($offset, $limit)
{

    $connexion = connection_base_de_données();
    $query = sprintf("SELECT * FROM ppm LIMIT %d, %d", $offset, $limit);
    $result = mysqli_query($connexion, $query);

    $ppms = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $ppms[] = $row;
    }

    mysqli_close($connexion);
    return $ppms;
}

function modifierPPM($ppm_id, $intitule, $reference, $date_lancement, $date_attribution, $description, $norme, $typeActivites, $activite)
{
    $connexion = connection_base_de_données();

    $requete = "UPDATE ppm 
                SET intitule = '$intitule', 
                    reference = '$reference', 
                    date_lancement = '$date_lancement', 
                    date_attribution = '$date_attribution', 
                    description = '$description', 
                    norme = '$norme', 
                    typeActivite = '$typeActivites', 
                    activites = '$activite' 
                WHERE id = '$ppm_id'";

    $resultat = mysqli_query($connexion, $requete);

    if (!$resultat) {
        die("Erreur lors de la modification du PPM : " . mysqli_error($connexion));
    } else {
        header('Location: ?page=welcome');
        exit();
    }
}

function supprimerPPM($ppm_id)
{
    $connexion = connection_base_de_données();


    $requete = "DELETE FROM ppm WHERE id = '$ppm_id'";

    $resultat = mysqli_query($connexion, $requete);

    if (!$resultat) {
        die("Erreur lors de la suppression du PPM : " . mysqli_error($connexion));
    } else {
        header('Location: ?page=welcome');
        exit();
    }
}

function countPPMs()
{
    $connexion = connection_base_de_données();

    $query = "SELECT COUNT(*) AS total FROM ppm";
    $result = mysqli_query($connexion, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    } else {
        return 0;
    }
}


function recupererTousLesAdmins()
{
    $connexion = connection_base_de_données();
    $requete = "SELECT * FROM entreprise WHERE role='admin'";
    $resultat = mysqli_query($connexion, $requete);

    $admins = array();

    while ($row = mysqli_fetch_assoc($resultat)) {
        $admins[] = $row;
    }

    mysqli_close($connexion);
    return $admins;
}
function creerAdmin($nom, $prenom, $email, $password)
{

    $connexion = connection_base_de_données();


    $nom = $connexion->real_escape_string($nom);
    $prenom = $connexion->real_escape_string($prenom);
    $email = $connexion->real_escape_string($email);
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO entreprise (nom, prenom, email, mot_de_passe, role) VALUES ('$nom', '$prenom', '$email', '$password_hashed', 'admin')";

    if ($connexion->query($sql) === TRUE) {
        echo "Nouvel administrateur créé avec succès.";
    } else {
        echo "Erreur lors de la création de l'administrateur : " . $connexion->error;
    }


    $connexion->close();
}