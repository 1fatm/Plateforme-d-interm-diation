<?php
    function connection_base_de_données()
    {
        $host = "localhost";
        $user = "root";
        $password = "";
        $base = "Formulaire_cnscl";
        $connexion = mysqli_connect($host, $user, $password, $base);
        if ($connexion->connect_error)
        {
            die("Erreur de connexion à la base de données : ". $connexion->connect_error);
        }
        else
        {
            echo "Connexion à la base de données réussie";
            return $connexion;
        }
        
    }
    function  insertion_base_de_données($intitule,$T_activité,$date_lancement, $date_attribution, $Norme,$ref,$Description)
    {
        $connexion = connection_base_de_données();
        
    //    $intitule=$_POST['intitulé'];
    //     $T_activité=$_POST['T_activité'];
    //     $date_lancement=$_POST['date_lancement'];
    //     $date_attribution=$_POST['date_attribution'];
    //     $Norme=$_POST['Norme'];
    //     $ref=$_POST['ref'];
    //     $Description=$_POST['Description'];
        $query="insert into ppm (intitulé,Type_activité, Dat_lancement, Description ,Référence,Date_attribution ,Norme) values ('$intitule','$T_activité','$Description''$date_lancement','$Description','$ref','$date_attribution','$Norme')";

        $result = mysqli_query($connexion, $query);
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



    function affichage_resultat()
    {
        $connection=connection_base_de_données();
        $requete="select * from ppm";
        
        $r=mysqli_query($connection,$requete);
       
        echo "<table border='2'>\n";
        echo'<tr>';
        echo "<th> Intitulé   </th>\n";
        echo "<th> Type_activité   </th>\n";
        echo "<th> Dat_lancement   </th>\n";
        echo "<th> Description   </th>\n";
        echo "<th> Référence   </th>\n";
        echo "<th> Date_attribution   </th>\n";
        echo "<th> Norme   </th>\n";
        echo '</tr>';
         while ($row = mysqli_fetch_assoc($r)) 
         {
          $intitule  = $row["Intitulé"];
          $T_activite  = $row["Type_activité"];
          $date_lancement  = $row["Dat_lancement"];
          $Description = $row["Description"];
          $Référence = $row["Référence"];
          $date_attribution  = $row["Date_attribution"];
          $Norme  = $row["Norme"];
          echo '<tr>';
          
       
          echo "<td>$intitule</td>\n";
          echo "<td>$T_activite</td>\n";
          echo "<td>$date_lancement</td>\n";
          echo "<td>$Description</td>\n";
          echo "<td>$Référence</td>\n";
          echo "<td>$date_attribution</td>\n";
          echo "<td>$Norme</td>\n";
          echo "</tr>\n";
         
          
         }
        
         echo '</table>';
        
        
        //print_r($q);
        
        mysqli_close($connection);



    }
?> 