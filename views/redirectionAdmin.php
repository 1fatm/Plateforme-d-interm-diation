
<?php
define('RESULTS_PER_PAGE', 15);

$current_page = isset($_GET['pag']) ? (int)$_GET['pag'] : 1;
if ($current_page < 1) {
    $current_page = 1;
}

$offset = ($current_page - 1) * RESULTS_PER_PAGE;
$ppms = recupererTousLesPPM($offset, RESULTS_PER_PAGE); 
$admins = recupererTousLesAdmins(); 
$entreprises = recupererTousLesUtilisateurs(); 

$total_projects = countPPMs();
$total_pages = ceil($total_projects/ RESULTS_PER_PAGE);
$page = isset($_GET['page']) ? $_GET['page'] : 'ppms';

$page = $_GET['page'];
function getStatistics($ppms)
{
    $totalPPM = count($ppms);
    $totalValidés = 0;  
    $totalRejetés = 0;   

    foreach ($ppms as $ppm) {
        if ($ppm['statut'] == 'validé') {
            $totalValidés++;
        } elseif ($ppm['statut'] == 'rejeté') {
            $totalRejetés++;
        }
    }

    return [
        'totalPPM' => $totalPPM,
        'totalValidés' => $totalValidés,
        'totalRejetés' => $totalRejetés
    ];
}

$stats = getStatistics($ppms);

$stats = getStatistics($ppms);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_admin'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    creerAdmin($nom, $prenom, $email, $password); 
    header("Location: ?page=redirectionAdmin");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['valider_ppm'])) {
        validerPPM($_POST['ppm_id']);
    } elseif (isset($_POST['rejeter_ppm'])) {
        rejeterPPM($_POST['ppm_id']);
    }
    header("Location: ?page=redirectionAdmin");
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Liste des PPM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        #ppms-section,
        #utilisateur-section,
        #entreprise-section {
            display: none;
        }
        #utilisateur-section {
            margin-top: 50px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #4CAF50;
            border: none;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            margin-top: 30px;
        }

        table th,
        table td {
            text-align: center;
            vertical-align: middle;
        }

        .table thead th {
            background-color: #f8f9fa;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 220px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: white;
            padding-top: 20px;
            z-index: 1000;
        }

        .sidebar .nav-link i {
            margin-right: 15px;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
            min-height: calc(100vh - 100px);
            position: relative;
            margin-bottom: 80px;
        }

        .statistics {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            flex: 1;
            margin-left: 10px;
            background-color: #fff;
        }

        .stat h3 {
            margin: 0;
            color: green;
        }

        .project-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 45px;
        }

        .project-table th,
        .project-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .project-table th {
            background-color: #f2f2f2;
        }

        .project-table tr:hover {
            background-color: #f9f9f9;
        }

        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .pagination .page-item {
            margin: 0 5px;
        }

        .logout-link {
            position: absolute;
            bottom: 20px;
            width: 100%;
            padding: 10px 20px;
            color: #ccc;
            text-decoration: none;
            transition: all 0.3s ease;
            text-align: center;
        }

        .logout-link:hover {
            background-color: black;
            color: #fff;
        }

        .sidebar .nav-link:hover {
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 15px;
        }

        .custom-icon {
            color: transparent;
            -webkit-text-stroke: 1px black;
            text-stroke: 1px black;
        }

        .user-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #fff;
            margin-bottom: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .user-card img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            margin-bottom: 15px;
        }

        .user-card h5 {
            margin-bottom: 5px;
        }
        .entreprise-logo {
        height: 150px; 
        object-fit: contain; 
        margin-bottom: 10px; 
    }
    .status-valide {
    color: green;
    font-weight: bold;
}

.status-rejete {
    color: red;
    font-weight: bold;
}

.status-attente {
    color: orange;
    font-weight: bold;
}
.btn-right {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1rem;
        }

    
    </style>

<body>

    <div class="sidebar">
        <h3>
            <img src="./public/I.png" alt="Logo de l'entreprise" width="210px">
        </h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-dark" href="?page=utilisateur" data-section-id="utilisateur">
                    <i class="fas fa-user fa-lg custom-icon"></i> Créer admin
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="?page=entreprise" data-section-id="entreprise">
                    <i class="fas fa-users fa-lg custom-icon"></i> Entreprises
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="?page=ppms" data-section-id="ppms">
                    <i class="fas fa-list-alt fa-lg custom-icon"></i> PPMs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#">
                    <i class="fas fa-file-alt fa-lg custom-icon"></i> AMIs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="#">
                    <i class="fa-solid fa-envelopes-bulk fa-lg custom-icon"></i> Appels d'offres
                </a>
            </li>
        </ul>
        <a class="logout-link" href="?page=connexion">
            <i class="fas fa-sign-out-alt fa-lg"></i> Déconnexion
        </a>
    </div>

    <div class="main-content">
    <div class="statistics">
    <div class="stat">
        <h4>Total PPMs</h4>
        <h3><?php echo $stats['totalPPM']; ?></h3>
    </div>
    <div class="stat">
        <h4>PPMs validés</h4>
        <h3><?php echo $stats['totalValidés']; ?></h3>
    </div>
    <div class="stat">
        <h4>PPMs rejetés</h4>
        <h3><?php echo $stats['totalRejetés']; ?></h3>
    </div>
</div>

<div id="utilisateur-section" style="display:none;">
            
<div class="d-flex justify-content-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createAdminModal">
                Créer un nouvel administrateur
            </button>
        </div>
            <div class="modal fade" id="createAdminModal" tabindex="-1" aria-labelledby="createAdminModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createAdminModalLabel">Créer un nouvel administrateur</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="">
                                <input type="hidden" name="create_admin" value="1">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom" required>
                                </div>
                                <div class="mb-3">
                                    <label for="prenom" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-success">Créer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="mt-5">Liste des administrateurs nouvellement créés</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['nom']); ?></td>
                            <td><?php echo htmlspecialchars($admin['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($admin['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div id="entreprise-section" style="display: none;">
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
    <nav class="pagination">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li class="page-item <?php if ($i == $current_page) echo 'active'; ?>">
                    <a class="page-link" href="?page=ppms&pag=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
  </div>
        <div id="ppms-section">
            <h2>Liste des PPM</h2>
            <table class="table project-table">
    <thead>
        <tr>
            <th>Intitulé</th>
            <th>Référence</th>
            <th>Date de Lancement</th>
            <th>Date d'Attribution</th>
            <th>Actions</th>
            <th>Statut du ppm</th> 
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ppms as $ppm) : ?>
            <tr>

                
                <td><?php echo htmlspecialchars($ppm['intitule']); ?></td>
                <td><?php echo htmlspecialchars($ppm['reference']); ?></td>
                <td><?php echo htmlspecialchars($ppm['date_lancement']); ?></td>
                <td><?php echo htmlspecialchars($ppm['date_attribution']); ?></td>
                <td>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailsModal<?php echo $ppm['id']; ?>">
                        Détails
                    </button>
                </td>
                <td><?php echo htmlspecialchars($ppm['statut']); ?></td> 
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php foreach ($ppms as $ppm) : ?>
    <div class="modal fade" id="detailsModal<?php echo $ppm['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel<?php echo $ppm['id']; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel<?php echo $ppm['id']; ?>">Détails du projet de marché</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Intitulé :</strong> <?php echo htmlspecialchars($ppm['intitule']); ?></p>
                    <p><strong>Référence :</strong> <?php echo htmlspecialchars($ppm['reference']); ?></p>
                    <p><strong>Date de lancement :</strong> <?php echo htmlspecialchars($ppm['date_lancement']); ?></p>
                    <p><strong>Date d'attribution :</strong> <?php echo htmlspecialchars($ppm['date_attribution']); ?></p>
                    <p><strong>Description :</strong> <?php echo htmlspecialchars($ppm['description']); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <form method="post" action="">
                        <input type="hidden" name="ppm_id" value="<?php echo $ppm['id']; ?>">
                        <button type="submit" name="valider_ppm" class="btn btn-success">Valider</button>
                        <button type="submit" name="rejeter_ppm" class="btn btn-danger">Rejeter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
            <nav class="pagination">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?php if ($i == $current_page) echo 'active'; ?>">
                            <a class="page-link" href="?page=ppms&pag=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>

    </div>
    <script>
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                const sectionId = link.getAttribute('data-section-id');
                document.querySelectorAll('.main-content > div').forEach(section => {
                    section.style.display = 'none';
                });
                document.getElementById(`${sectionId}-section`).style.display = 'block';
            });
        });

        
        document.getElementById('ppms-section').style.display = 'block';
    </script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

   