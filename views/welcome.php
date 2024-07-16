<?php

$errors = [];
$success_message = '';

if (!isset($_SESSION['entreprise_id'])) {
    header('Location: ?page=connexion');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty($_POST['intitule'])) {
        $errors['intitule'] = "Veuillez saisir ce champ";
    }
    if (empty($_POST['reference'])) {
        $errors['reference'] = "Veuillez saisir la référence";
    }
    if (empty($_POST['date_lancement'])) {
        $errors['date_lancement'] = "Veuillez saisir la date prévue de lancement";
    }
    if (empty($_POST['date_attribution'])) {
        $errors['date_attribution'] = "Veuillez saisir la date prévue d'attribution";
    }
    if (empty($_POST['description'])) {
        $errors['description'] = "Veuillez saisir la description";
    }
    if (empty($_POST['norme'])) {
        $errors['norme'] = "Veuillez saisir la norme";
    }
    if (empty($_POST['typeActivites'])) {
        $errors['typeActivites'] = "Veuillez choisir un type d'activités";
    }
    if (empty($_POST['activites'])) {
        $errors['activites'] = "Veuillez choisir une activité";
    }

    if (empty($errors)) {
        $intitule = $_POST['intitule'];
        $reference = $_POST['reference'];
        $date_lancement = $_POST['date_lancement'];
        $date_attribution = $_POST['date_attribution'];
        $description = $_POST['description'];
        $norme = $_POST['norme'];
        $typeActivites = $_POST['typeActivites'];
        $activites = $_POST['activites'];

        ppm($intitule, $reference, $date_lancement, $date_attribution, $description, $norme, $typeActivites, $activites);

        $success_message = "Le projet de marché a été ajouté avec succès.";
    }


}


$ppm_utilisateur = recupererPPMUtilisateurConnecte();
$nombre_ppm = count($ppm_utilisateur);


function getStatistics() {
    global $nombre_ppm;
    $totalPPM = $nombre_ppm;
    $totalAMI = 0; 
    $totalAO = 0;  

    return [
        'totalPPM' => $totalPPM,
        'totalAMI' => $totalAMI,
        'totalAO' => $totalAO
    ];
}

define('RESULTS_PER_PAGE', 5);
$total_projects = $nombre_ppm;
$total_pages = ceil($total_projects / RESULTS_PER_PAGE);

$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($current_page < 1 || $current_page > $total_pages) {
    $current_page = 1;
}

$offset = ($current_page - 1) * RESULTS_PER_PAGE;
$projects_to_display = array_slice($ppm_utilisateur, $offset, RESULTS_PER_PAGE);

$stats = getStatistics();
$entreprise_info = getEntrepriseInfo();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Formulaire du PPM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
       body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: white;
            padding-top: 20px;
            z-index: 1000;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
            min-height: calc(100vh - 100px);
            position: relative;
            margin-bottom: 80px
        }

        .carousel {
            z-index: 500;
        }

        .carousel-inner img {
            width: 100%;
            height: auto;
        }

        .carousel-caption {
            z-index: 700;
        }

        .sidebar h3 {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .sidebar h3 img {
            margin-right: 10px;
            width: 250px;
            height: auto;
            border-radius: 5px;
            margin-top: 12px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #ccc;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background-color: #454d55;
            color: #333;
            text-decoration: none;
        }

        .sidebar .nav-link i {
            margin-right: 30px;
        }

        .sidebar .nav-link {
            padding: 10px 20px;
            display: block;
            color: #ccc;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background-color: #454d55;
            color: #333;
            text-decoration: none;
        }

        .sidebar .nav-link:hover {
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .green-background {
            background-color: green;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 40px;
        }

        .error-message {
            color: red;
            font-size: 14px;
        }

        .error {
            border: 2px solid red;
        }

        .statistics {
            margin-bottom: 12px;
        }

        .statistics .stat {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 12px;
            margin-top: 12px;
        }

        .statistics .stat h3 {
            margin: 0;
        }

        .project-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
            background-color: #fff;
            margin-top: 15px;
        }

        .project-card .project-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .project-card .project-header h5 {
            margin: 0;
        }

        .project-card .project-body {
            margin-top: 10px;
        }

        .project-card .project-body p {
            margin: 5px 0;
        }

        .project-card .project-actions {
            margin-top: 15px;
            text-align: right;
        }

        .project-card .project-actions a {
            margin-left: 10px;
        }

        .accordion-header {
            padding: 15px;
        }

        .logout-link {
            position: absolute;
            bottom: 20px;
            width: 100%;
            padding: 10px 20px;
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logout-link:hover {
            background-color: black;
            color: #fff;
        }

        .custom-icon {
            color: transparent;
            -webkit-text-stroke: 1px black;
            
            text-stroke: 1px black;
        
        }
        .project-card.validated {
        border-color: green;
    }

    .project-card.rejected {
        border-color: red;
   }
   .badge {
        font-size: 14px;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 4px;
        margin-right: 5px;
    }

    .badge.validated {
        background-color: green;
        color: white;
    }

    .badge.rejected {
        background-color: red;
        color: white;
    }
    #accueil-section {
    background-color: #f8f9fa;
    padding: 20px 0;
}

.card {
    border: none;
    max-width:900px; 
    margin: 0 auto; 
    border-radius: 15px; 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
    max-height: 50px;
}

.card-header {
    background:linear-gradient(90deg, #28a745, #218838);
    color: #fff;
    text-align: center;
    font-size: 1.5rem;
    font-weight: 700px;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}

.card-body {
    padding: 20px; 
    background-color: #fff;
    margin: 0 auto; 
    border-bottom-left-radius: 15px;
}

.card-title {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #343a40;
}

.card-text {
    font-size: 1rem;
    margin-bottom: 15px;
}

.card-text strong {
    color: #1e3c72;
}

.img-fluid {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    border: 2px solid #ddd;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .card-body {
        padding: 20px;
    }

    .card-title {
        font-size: 1.5rem;
    }

    .card-text {
        font-size: 0.9rem;
    }
}
    .section {
            display: none;
      
        

    }
        .section.active {
            display: block;
            margin-top: 20px;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 100px;
            
            
        }
        .accueil-info {
            background-color: #f0f0f0;
            padding: 20px;
            margin: 20px;
            border-radius: 5px;
        }
    


    </style>
</head>

<body>
<div class="sidebar">
        <h3>
            <img src="./public/I.png" alt="Logo de l'entreprise">
        </h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-dark"  href="#" data-section="acceuil-section">
                    <i class="fas fa-home fa-lg custom-icon"></i> Accueil
                </a>
            </li>

        
            <li class="nav-item">
                <a class="nav-link text-dark"  href="#" data-section="ppm-section" data-bs-toggle="tooltip" data-bs-placement="right" title="Projet de marché ">
                    <i class="fas fa-list-alt fa-lg custom-icon"></i> PPM
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="?page=utilisateur">
                    <i class="fa fa-globe fa-lg custom-icon"></i> Annuaire
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark " href="#" data-bs-toggle="tooltip" data-bs-placement="right" title="Appels d'offres  soumis">
                    <i class="fa-solid fa-envelopes-bulk fa-lg custom-icon"></i> AOs  générés
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark " href="#" data-bs-toggle="tooltip" data-bs-placement="right" title=" Appels à manifestation d'interêt soumis">
                    <i class="fas fa-bullhorn fa-lg custom-icon"></i> AMIs Générés
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark " href="#" data-bs-toggle="tooltip" data-bs-placement="right" title="Publications">
                    <i class="fas fa-newspaper fa-lg custom-icon"></i> Publications
                </a>
            </li>
       
        </ul>
        <a href="?page=connexion" class="logout-link">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
    </div>
    <section id="ppm-section" class="section">
    <div class="main-content">
        <div class="container">
            <div class="green-background">
                <p>Bonjour,</p>
                <p>Votre plan de passation pour l'année 2024 a été automatiquement créé. Vous êtes maintenant prêt à soumettre vos projets de marchés pour validation auprès du Secrétariat technique du CNSCL. Une fois vos projets validés par le CNSCL, les appels d'offres et appels à manifestation d'intérêt seront générés depuis cette plateforme.</p>
            </div>

            <div class="statistics row">
                <div class="col-md-4">
                    <div class="stat">
                        <h3><?php echo $stats['totalPPM']; ?></h3>
                        <p>PPM</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat">
                        <h3><?php echo $stats['totalAMI']; ?></h3>
                        <p>AMI</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat">
                        <h3><?php echo $stats['totalAO']; ?></h3>
                        <p>AO</p>
                    </div>
                </div>
            </div>

            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Ajouter un nouveau projet de marché
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <form id="formPPM" method="POST" class="mb-4">
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="intitule">Intitulé</label>
                                        <input type="text" class="form-control <?php echo isset($errors['intitule']) ? 'error' : '' ?>" id="intitule" name="intitule" value="<?php echo isset($_POST['intitule']) ? $_POST['intitule'] : ''; ?>">
                                        <?php if (isset($errors['intitule'])) : ?>
                                            <p class="error-message"><?php echo $errors['intitule']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col">
                                        <label for="reference">Référence</label>
                                        <input type="text" class="form-control <?php echo isset($errors['reference']) ? 'error' : '' ?>" id="reference" name="reference" value="<?php echo isset($_POST['reference']) ? $_POST['reference'] : ''; ?>">
                                        <?php if (isset($errors['reference'])) : ?>
                                            <p class="error-message"><?php echo $errors['reference']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="date_lancement">Date de lancement prévue</label>
                                        <input type="date" class="form-control <?php echo isset($errors['date_lancement']) ? 'error' : '' ?>" id="date_lancement" name="date_lancement" value="<?php echo isset($_POST['date_lancement']) ? $_POST['date_lancement'] : ''; ?>">
                                        <?php if (isset($errors['date_lancement'])) : ?>
                                            <p class="error-message"><?php echo $errors['date_lancement']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col">
                                        <label for="date_attribution">Date d'attribution prévue</label>
                                        <input type="date" class="form-control <?php echo isset($errors['date_attribution']) ? 'error' : '' ?>" id="date_attribution" name="date_attribution" value="<?php echo isset($_POST['date_attribution']) ? $_POST['date_attribution'] : ''; ?>">
                                        <?php if (isset($errors['date_attribution'])) : ?>
                                            <p class="error-message"><?php echo $errors['date_attribution']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="description">Description</label>
                                        <textarea class="form-control <?php echo isset($errors['description']) ? 'error' : '' ?>" id="description" name="description"><?php echo isset($_POST['description']) ? $_POST['description'] : ''; ?></textarea>
                                        <?php if (isset($errors['description'])) : ?>
                                            <p class="error-message"><?php echo $errors['description']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="norme">Norme</label>
                                        <input type="text" class="form-control <?php echo isset($errors['norme']) ? 'error' : '' ?>" id="norme" name="norme" value="<?php echo isset($_POST['norme']) ? $_POST['norme'] : ''; ?>">
                                        <?php if (isset($errors['norme'])) : ?>
                                            <p class="error-message"><?php echo $errors['norme']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col">
                                        <label for="typeActivites">Type d'activités</label>
                                        <select class="form-select <?php echo isset($errors['typeActivites']) ? 'error' : '' ?>" id="typeActivites" name="typeActivites">
                                            <option value="">Sélectionner le type d'activités</option>
                                            <option value="1" <?php echo (isset($_POST['typeActivites']) && $_POST['typeActivites'] == '1') ? 'selected' : ''; ?>>Traveaux</option>
                                            <option value="2" <?php echo (isset($_POST['typeActivites']) && $_POST['typeActivites'] == '2') ? 'selected' : ''; ?>>Services</option>
                                            <option value="3" <?php echo (isset($_POST['typeActivites']) && $_POST['typeActivites'] == '3') ? 'selected' : ''; ?>>Fournitures</option>
                                        </select>

                                        <?php if (isset($errors['typeActivites'])) : ?>
                                            <p class="error-message"><?php echo $errors['typeActivites']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col">
                                        <label for="activites">Activités</label>
                                        <select class="form-select <?php echo isset($errors['activites']) ? 'error' : '' ?>" id="activites" name="activites">
                                            <option value="">Sélectionner une activité</option>
                                            <?php foreach ($activites as $activite) : ?>
                                                <option value="<?php echo $activite['id']; ?>" <?php echo (isset($_POST['activites']) && $_POST['activites'] == $activite['id']) ? 'selected' : ''; ?>><?php echo $activite['nom']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <?php if (isset($errors['activites'])) : ?>
                                            <p class="error-message"><?php echo $errors['activites']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="reset" class="btn btn-danger">Annuler</button>
                                    <div>
                                        <button type="submit" class="btn btn-primary">Soumettre</button>
                                    </div>
                                </div>
                            </form>
                            <?php if ($success_message) : ?>
                                <div class="alert alert-success mt-3"><?php echo $success_message; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($projects_to_display)) : ?>
                <?php foreach ($projects_to_display as $index => $projet) : ?>
                    <div class="project-card">
                        <div class="project-header">
                            <h5><?php echo isset($projet['intitule']) ? htmlspecialchars($projet['intitule']) : 'Intitulé non défini'; ?></h5>
                            <?php
                            $statut_class = '';
                            switch ($projet['statut']) {
                                case 'validé':
                                    $statut_class = 'badge bg-success';
                                    break;
                                case 'rejeté':
                                    $statut_class = 'badge bg-danger';
                                    break;
                                default:
                                    $statut_class = 'badge bg-warning';
                                    break;
                            }
                            ?>
                            <span class="<?php echo $statut_class; ?>"><?php echo ucfirst($projet['statut']); ?></span>
                        </div>
                      
                        
                        <div class="project-actions">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalVisualiser_<?php echo $index; ?>"><i class="fas fa-eye"></i> Visualiser</button>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalModifier_<?php echo $index; ?>"><i class="fas fa-edit"></i> Modifier</button>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalSupprimer_<?php echo $index; ?>"><i class="fas fa-trash"></i> Supprimer</button>
                        </div>
                    </div>

                    
                    <div class="modal fade" id="modalVisualiser_<?php echo $index; ?>" tabindex="-1" aria-labelledby="modalVisualiserLabel_<?php echo $index; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalVisualiserLabel_<?php echo $index; ?>">Détails du Projet de Marché</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Intitulé:</strong> <?php echo isset($projet['intitule']) ? htmlspecialchars($projet['intitule']) : 'Non défini'; ?></p>
                <p><strong>Référence:</strong> <?php echo isset($projet['reference']) ? htmlspecialchars($projet['reference']) : 'Non définie'; ?></p>
                <p><strong>Date de publication prévue:</strong> <?php echo isset($projet['date_attribution']) ? htmlspecialchars($projet['date_attribution']) : 'Non définie'; ?></p>
                <p><strong>Date de lancement prévue:</strong> <?php echo isset($projet['date_lancement']) ? htmlspecialchars($projet['date_lancement']) : 'Non définie'; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                
                <?php if (isset($projet['statut']) && $projet['statut'] == 'validé') : ?>
                    <button type="submit" class="btn btn-warning">Générer AMI</button>
                    <button type="submit" class="btn btn-success">Générer AO</button>
                <?php else : ?>
                    <button type="submit" class="btn btn-warning" style="display: none;">Générer AMI</button>
                    <button type="submit" class="btn btn-success" style="display: none;">Générer AO</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalAppelOffre_<?php echo $index; ?>" tabindex="-1" aria-labelledby="modalAppelOffreLabel_<?php echo $index; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAppelOffreLabel_<?php echo $index; ?>">Nouvel Appel d'Offre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="intitule_<?php echo $index; ?>" class="form-label">Intitulé *</label>
                            <input type="text" class="form-control" id="intitule_<?php echo $index; ?>" name="intitule" value="<?php echo isset($projet['intitule']) ? htmlspecialchars($projet['intitule']) : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="reference_<?php echo $index; ?>" class="form-label">Référence *</label>
                            <input type="text" class="form-control" id="reference_<?php echo $index; ?>" name="reference" value="<?php echo isset($projet['reference']) ? htmlspecialchars($projet['reference']) : ''; ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="objet_<?php echo $index; ?>" class="form-label">Objet *</label>
                            <input type="text" class="form-control" id="objet_<?php echo $index; ?>" name="objet" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_publication_<?php echo $index; ?>" class="form-label">Date de publication *</label>
                            <input type="date" class="form-control" id="date_publication_<?php echo $index; ?>" name="date_publication" required value="<?php echo isset($projet['date_attribution']) ? htmlspecialchars($projet['date_attribution']) : ''; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_limite_<?php echo $index; ?>" class="form-label">Date limite *</label>
                            <input type="date" class="form-control" id="date_limite_<?php echo $index; ?>" name="date_limite" required  value="<?php echo isset($projet['date_lancement']) ? htmlspecialchars($projet['date_lancement']) : ''; ?>" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="categorie_activite_<?php echo $index; ?>" class="form-label">Catégorie d'activité *</label>
                            <input type="text" class="form-control" id="categorie_activite_<?php echo $index; ?>" name="categorie_activite" required  value="<?php echo isset($projet['typeActivite']) ? htmlspecialchars($projet['typeActivite']) : ''; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="activite_<?php echo $index; ?>" class="form-label">Activité *</label>
                            <input type="text" class="form-control" id="activite_<?php echo $index; ?>" name="activite" required  value="<?php echo isset($projet['activites']) ? htmlspecialchars($projet['activites']) : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="normes_standards_<?php echo $index; ?>" class="form-label">Normes et standards</label>
                            <textarea class="form-control" id="normes_standards_<?php echo $index; ?>" name="normes_standards" rows="2"  value="<?php echo isset($projet['norme']) ? htmlspecialchars($projet['norme']) : ''; ?>"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description_<?php echo $index; ?>" class="form-label">Description *</label>
                            <textarea class="form-control" id="description_<?php echo $index; ?>" name="description" rows="2" required  value="<?php echo isset($projet['date_attribution']) ? htmlspecialchars($projet['description']) : ''; ?>"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="instructions_<?php echo $index; ?>" class="form-label">Instructions de l'appel d'offre *</label>
                            <textarea class="form-control" id="instructions_<?php echo $index; ?>" name="instructions" rows="2" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="pieces_jointes_<?php echo $index; ?>" class="form-label">Pièces jointes *</label>
                            <input type="file" class="form-control" id="pieces_jointes_<?php echo $index; ?>" name="pieces_jointes[]" multiple required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalAMI_<?php echo $index; ?>" tabindex="-1" aria-labelledby="modalAMILabel_<?php echo $index; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAMILabel_<?php echo $index; ?>">Nouvel Appel d'Offre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    
            </div>
            <div class="modal-body">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="intitule_<?php echo $index; ?>" class="form-label">Intitulé *</label>
                            <input type="text" class="form-control" id="intitule_<?php echo $index; ?>" name="intitule" value="<?php echo isset($projet['intitule']) ? htmlspecialchars($projet['intitule']) : ''; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="reference_<?php echo $index; ?>" class="form-label">Référence *</label>
                            <input type="text" class="form-control" id="reference_<?php echo $index; ?>" name="reference" value="<?php echo isset($projet['reference']) ? htmlspecialchars($projet['reference']) : ''; ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="objet_<?php echo $index; ?>" class="form-label">Objet *</label>
                            <input type="text" class="form-control" id="objet_<?php echo $index; ?>" name="objet" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_publication_<?php echo $index; ?>" class="form-label">Date de publication *</label>
                            <input type="date" class="form-control" id="date_publication_<?php echo $index; ?>" name="date_publication" required value="<?php echo isset($projet['date_attribution']) ? htmlspecialchars($projet['date_attribution']) : ''; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date_limite_<?php echo $index; ?>" class="form-label">Date limite *</label>
                            <input type="date" class="form-control" id="date_limite_<?php echo $index; ?>" name="date_limite" required  value="<?php echo isset($projet['date_lancement']) ? htmlspecialchars($projet['date_lancement']) : ''; ?>" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="categorie_activite_<?php echo $index; ?>" class="form-label">Catégorie d'activité *</label>
                            <input type="text" class="form-control" id="categorie_activite_<?php echo $index; ?>" name="categorie_activite" required  value="<?php echo isset($projet['typeActivite']) ? htmlspecialchars($projet['typeActivite']) : ''; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="activite_<?php echo $index; ?>" class="form-label">Activité *</label>
                            <input type="text" class="form-control" id="activite_<?php echo $index; ?>" name="activite" required  value="<?php echo isset($projet['activites']) ? htmlspecialchars($projet['activites']) : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="normes_standards_<?php echo $index; ?>" class="form-label">Normes et standards</label>
                            <textarea class="form-control" id="normes_standards_<?php echo $index; ?>" name="normes_standards" rows="2"  value="<?php echo isset($projet['norme']) ? htmlspecialchars($projet['norme']) : ''; ?>"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description_<?php echo $index; ?>" class="form-label">Description *</label>
                            <textarea class="form-control" id="description_<?php echo $index; ?>" name="description" rows="2" required  value="<?php echo isset($projet['date_attribution']) ? htmlspecialchars($projet['description']) : ''; ?>"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="instructions_<?php echo $index; ?>" class="form-label">Instructions de l'appel d'offre *</label>
                            <textarea class="form-control" id="instructions_<?php echo $index; ?>" name="instructions" rows="2" required></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="pieces_jointes_<?php echo $index; ?>" class="form-label">Pièces jointes *</label>
                            <input type="file" class="form-control" id="pieces_jointes_<?php echo $index; ?>" name="pieces_jointes[]" multiple required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </form>
            </div>
        </div>
    </div>
</div>
                    <div class="modal fade" id="modalModifier_<?php echo $index; ?>" tabindex="-1" aria-labelledby="modalModifierLabel_<?php echo $index; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalModifierLabel_<?php echo $index; ?>">Modifier le Projet de Marché</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" id="formModifier_<?php echo $index; ?>">
                                        <input type="hidden" name="projet_id" value="<?php echo $index; ?>">
                                        <div class="form-group">
                                            <label for="intitule_<?php echo $index; ?>">Intitulé</label>
                                            <input type="text" id="intitule_<?php echo $index; ?>" name="intitule" value="<?php echo isset($projet['intitule']) ? htmlspecialchars($projet['intitule']) : ''; ?>" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="reference_<?php echo $index; ?>">Référence</label>
                                            <input type="text" id="reference_<?php echo $index; ?>" name="reference" value="<?php echo isset($projet['reference']) ? htmlspecialchars($projet['reference']) : ''; ?>" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="date_lancement_<?php echo $index; ?>">Date de lancement prévue</label>
                                            <input type="date" id="date_lancement_<?php echo $index; ?>" name="date_lancement" value="<?php echo isset($projet['date_lancement']) ? htmlspecialchars($projet['date_lancement']) : ''; ?>" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="date_attribution_<?php echo $index; ?>">Date d'attribution prévue</label>
                                            <input type="date" id="date_attribution_<?php echo $index; ?>" name="date_attribution" value="<?php echo isset($projet['date_attribution']) ? htmlspecialchars($projet['date_attribution']) : ''; ?>" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="description_<?php echo $index; ?>">Description</label>
                                            <textarea id="description_<?php echo $index; ?>" name="description" class="form-control"><?php echo isset($projet['description']) ? htmlspecialchars($projet['description']) : ''; ?></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                    <button type="button" class="btn btn-primary" >Enregistrer les modifications</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="modal fade" id="modalSupprimer_<?php echo $index; ?>" tabindex="-1" aria-labelledby="modalSupprimerLabel_<?php echo $index; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalSupprimerLabel_<?php echo $index; ?>">Confirmer la suppression</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Êtes-vous sûr de vouloir supprimer ce projet de marché?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <form method="POST" >
                                        <input type="hidden" name="projet_id" value="<?php echo $index; ?>">
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php else : ?>
                <p>Aucun projet à afficher pour le moment.</p>
            <?php endif; ?>
            <nav aria-label="Pagination">
                <ul class="pagination justify-content-center">
                    <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
                        <li class="page-item <?php echo ($page == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
          
        </div>
    </div>
</section>
<section id="entreprise-section" class="section active">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0">Informations de l'entreprise</h3>
            </div>
            <div class="card-body">
                
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4 text-center">
                        <img src="uploads/<?php echo htmlspecialchars($entreprise_info['photo']); ?>" alt="Logo de l'entreprise" class="img-fluid rounded mt-3 border company-logo">
                    </div>
                    <div class="col-md-8">
                        <h5 class="card-title"><?php echo htmlspecialchars($entreprise_info['nomEntreprise']); ?></h5>
                        <p class="card-text"><strong>Date de création :</strong> <?php echo htmlspecialchars($entreprise_info['date_creation']); ?></p>
                        <p class="card-text"><strong>Numéro d'identification fiscale :</strong> <?php echo htmlspecialchars($entreprise_info['numero_fiscal']); ?></p>
                     
                    </div>
                </div>
                
                
                <hr class="my-4">
                
    
                <div class="row">
                    <div class="col-md-12">
                        <h4>Informations supplémentaires</h4>
                        <p class="card-text"><strong>Numéro de registre de commerce :</strong> <?php echo htmlspecialchars($entreprise_info['registre_commerce']); ?></p>
                        <p class="card-text"><strong>Nombre d'employés :</strong> <?php echo htmlspecialchars($entreprise_info['nbre_employes']); ?></p>
                        <p class="card-text"><strong>Chiffre d'affaires :</strong> <?php echo htmlspecialchars($entreprise_info['chiffre_affaire']); ?></p>
                        <button id="btn-modifier" class="btn btn-primary mt-3">Modifier</button>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="formulaire-modification" class="section" style="display: none; width: 100%;">
    <div class="container mt-3">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Modifier les informations de l'entreprise</h3>
            </div>
            <div class="card-body p-2">
                <form action="update_profile.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4 text-center mb-2">
                            <img src="uploads/<?php echo htmlspecialchars($entreprise_info['photo']); ?>" alt="Logo de l'entreprise" class="img-fluid rounded border company-logo mb-2">
                            <input type="file" name="photo" class="form-control form-control-sm mt-1">
                        </div>
                        <div class="col-md-8">
                            <div class="form-group mb-1">
                                <label for="nomEntreprise">Nom de l'entreprise</label>
                                <input type="text" name="nomEntreprise" id="nomEntreprise" class="form-control form-control-sm" value="<?php echo htmlspecialchars($entreprise_info['nomEntreprise']); ?>">
                            </div>
                            <div class="form-group mb-1">
                                <label for="date_creation">Date de création</label>
                                <input type="date" name="date_creation" id="date_creation" class="form-control form-control-sm" value="<?php echo htmlspecialchars($entreprise_info['date_creation']); ?>">
                            </div>
                            <div class="form-group mb-1">
                                <label for="numero_fiscal">Numéro d'identification fiscale</label>
                                <input type="text" name="numero_fiscal" id="numero_fiscal" class="form-control form-control-sm" value="<?php echo htmlspecialchars($entreprise_info['numero_fiscal']); ?>">
                            </div>
                            <div class="form-group mb-1">
                                <label for="registre_commerce">Numéro de registre de commerce</label>
                                <input type="text" name="registre_commerce" id="registre_commerce" class="form-control form-control-sm" value="<?php echo htmlspecialchars($entreprise_info['registre_commerce']); ?>">
                            </div>
                            <div class="form-group mb-1">
                                <label for="nbre_employes">Nombre d'employés</label>
                                <input type="number" name="nbre_employes" id="nbre_employes" class="form-control form-control-sm" value="<?php echo htmlspecialchars($entreprise_info['nbre_employes']); ?>">
                            </div>
                            <div class="form-group mb-1">
                                <label for="chiffre_affaire">Chiffre d'affaires</label>
                                <input type="text" name="chiffre_affaire" id="chiffre_affaire" class="form-control form-control-sm" value="<?php echo htmlspecialchars($entreprise_info['chiffre_affaire']); ?>">
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <button type="reset" class="btn btn-danger btn-sm" id="annuler">Annuler</button>
                                <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

    <section id="annuaire" class="section">
    <h2>Liste des entreprises</h2>
    <div class="row">
        <?php foreach ($entreprises as $entreprise) : ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="uploads/<?php echo htmlspecialchars($entreprise['photo']); ?>" class="card-img-top entreprise-logo" alt="Logo de <?php echo htmlspecialchars($entreprise['nomEntreprise']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($entreprise['nomEntreprise']); ?></h5>
                        <p class="card-text"><strong>Nom :</strong> <?php echo htmlspecialchars($entreprise['nom']); ?></p>
                        <p class="card-text"><strong>Prénom :</strong> <?php echo htmlspecialchars($entreprise['prenom']); ?></p>
                        <p class="card-text"><strong>Email :</strong> <?php echo htmlspecialchars($entreprise['email']); ?></p>
                        
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="http://localhost/Plateforme_CNSCL/public/script.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
        document.getElementById('annuler').addEventListener('click', function() {
           
            document.getElementById('ppm-section').style.display = 'block';
        });
        
        document.getElementById('btn-modifier').addEventListener('click', function() {
            document.getElementById('entreprise-section').style.display = 'none';
            document.getElementById('formulaire-modification').style.display = 'block';
        });
    </script>
 <script>
$(document).ready(function() {
    
    $('.nav-link').click(function(e) {
        e.preventDefault();
        var targetSection = $(this).attr('data-section');
        $('.section').removeClass('active');
        $('#' + targetSection).addClass('active');
    });
});
</script>
<script>
    document.getElementById('annuler').addEventListener('click', function() {
           
           document.getElementById('ppm-section').style.display = 'block';
       });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var generateAOButtons = document.querySelectorAll('.btn-success');

        generateAOButtons.forEach(function(button, index) {
            button.addEventListener('click', function() {
                var modal = new bootstrap.Modal(document.getElementById('modalAppelOffre_' + index));
                modal.show();
            });
        });
    });
</script>


</body>
</html>