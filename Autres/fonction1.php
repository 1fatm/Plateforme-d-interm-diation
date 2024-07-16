<?php
function connection_base_de_données()
    {
        
    }
    function insertion_base_de_données($nom,$prenom,$email,$nomEntreprise)
    {
        $connexion = connection_base_de_données();
        $requete="insert into entreprise (nom,prenom,email,nomEntreprise) values('$nom','$prenom','$email','$nomEntreprise')";
        $result = mysqli_query($connexion, $requete);
        if (!$result)
        {
            die("Erreur lors de la requête : ". mysqli_error($connexion));
        }
        else
        {
            echo "Insertion réussie";
        }
        mysqli_close($connexion);


    }
    function connection_plateforme($nom,$motdepasse)
    {
        $connexion=connection_base_de_données();
        $requete="select nom,motdepasse from entreprise where nom='$nom' and motdepasse='$motdepasse'";
        $result = mysqli_query($connexion, $requete);
        if (!$result)
        {  
            echo "Vous n'êtes pas incrit sur la plateforme";
            die("Erreur lors de la requête : ". mysqli_error($connexion));
        }
        else{
           header("Location:pagePrincipale.php");
        }
      

    }
    function reinitialiser_mot_de_passe($email,$motdepassenouv)
    {
        $connexion=connection_base_de_données();
        $requete = "UPDATE entreprise SET mot_de_passe='$motdepassenouv' WHERE email='$email'";
         $resultat = mysqli_query($connexion, $requete);
        
        if (!$resultat) {
             die("Erreur lors de la réinitialisation du mot de passe : " . mysqli_error($connexion));
         } else {
            echo "Mot de passe réinitialisé avec succès";
        }
    }

?>