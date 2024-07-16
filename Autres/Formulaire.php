<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Formulaire.css">
    <title>Document</title>
</head>
<body>


    <form action="Formulaire.php" class="page_principale" method="post">
    <h1>Formulaire du ppm</h1>
    <div class="Formulaire">
        <div>

        </div>
  
        <label for="nom">Intitulé</label>
        <input type="text" name="intitulé" id="intitlulé"> <br/> </div>
        <label for="T_activités">Type d'activités</label>
        <select name="T_activité" id="activité">
            <option value="Traveaux">Traveaux</option>
            <option value="Services">Services</option>
            <option value="Fournitures">Fournitures</option>
            
        </select><br/>
    
        <label for="date_lancement">Date de Lancement</label>
        <input type="date" name="date_lancement" id="date_lancemment"><br/><br/>
        <label for="date_attribution">Date_attribution</label>
        <input type="date"  name="date_attribution"  id="date_attribution"><br/><br/>
        <label for="Norme">Norme</label>
        <input type="text" name="Norme" id="Norme"><br/><br/>
        <label for="Ref">Réference</label>
        <input type="text" name="ref" id="ref"><br/><br/>
        
                

        <label for="Descprition">Description</label>
        <input type="text" name="Description" id="Desciption"><br/><br/>
        <input type="submit"  name="soumettre" value="Soumettre">
        <input type="submit" value="Annuler"><br/>

        </div>


    </form>
  
     
    
    
   
    <?php
    if(isset($_REQUEST['soumettre']))
    {
        include "Fonctions.php";

        $connection=connection_base_de_données();

            if ($connection->connect_error) 
            {
                die("Erreur de connexion à la base de données : " . $connection->connect_error);
            }
            else
            {
               /* $intitulé=$_POST['intitulé'];
                $T_activité=$_POST['T_activité'];
                $date_lancement=$_POST['date_lancement'];
                $date_attribution=$_POST['date_attribution'];
                $Norme=$_POST['Norme'];
                $ref=$_POST['ref'];

                $Description=$_POST['Description'];*/
                
                insertion_base_de_données($_POST['intitulé'], $_POST['T_activité'], $_POST['date_lancement'],$_POST['date_attribution'],$_POST['ref'],$_POST['Norme'],$_POST['Description']);
                affichage_resultat();
                
                



            }
       

     }
?>


</body>
</html>