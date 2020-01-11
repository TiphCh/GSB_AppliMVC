<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="row">
    <div class="col-md-4">
        <form action="index.php?uc=validerFicheFrais&action=moisDispo" 
              method="post" role="form">
            <div class="form-group">
                <label for="lstVisiteur" accesskey="n">Choisir le visiteur : </label>
                <select id="lstVisiteur" name="idVisiteur" class="form-control" onchange="this.form.submit()">
                    <option value="">Sélectionner un visiteur</option>
                    <?php
                    foreach ($lesVisiteurs as $unVisiteur) {
                        $id = $unVisiteur['id'];
                        $nomVisiteur = $unVisiteur['nom'];
                        $prenomVisiteur = $unVisiteur['prenom']; 
                        if(!empty($idVisiteur) && $idVisiteur == $unVisiteur['id']){
                            ?>
                    <option value="<?php echo $id ?>" selected="true">
                            <?php echo $nomVisiteur . ' ' . $prenomVisiteur ?>
                    </option>
                                <?php
                        } else {
                    ?>
                    <option value="<?php echo $id ?>">
                            <?php echo $nomVisiteur . ' ' . $prenomVisiteur ?>
                    </option>
                        <?php
                        }
                        
                    }
                    ?>
                </select>
            </div>
        </form>
        
        <form action="index.php?uc=validerFicheFrais&action=afficherFicheVisiteur" 
              method="post" role="form"> 
            <div class="form-group">
                <label for="lstMois" accesskey="n"> Mois : </label>
                <select id="lstMois" name="leMois" class="form-control" onchange="this.form.submit()">
                    <option value="">Sélectionner un mois</option>
                    <?php
                    foreach($lesMois as $unMois){
                        $mois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        if(!empty($leMois) && $leMois == $unMois['mois']){
                         ?>
                    <option value="<?php echo $mois ?>" selected="true">
                        <?php echo $numMois . '/' . $numAnnee ?>
                    </option>
                        <?php
                        }else{
                    ?> 
                    <option value="<?php echo $mois ?>">
                        <?php echo $numMois . '/' . $numAnnee ?>
                    </option>
                        <?php
                        }
                    }
                    ?>
                </select>
                
            </div>
        </form>
    </div>
</div>

