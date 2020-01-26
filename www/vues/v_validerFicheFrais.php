<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="row">    
       <h2> Valider la fiche de frais </h2>
        <h3>Eléments forfaitisés</h3>
        <div class="col-md-4">
            <form method="post" 
                  action="index.php?uc=validerFicheFrais&action=corrigerFrais" 
                  role="form">
                <fieldset>       
                     <?php
                    foreach ($lesFraisForfait as $unFrais) {
                        $idFrais = $unFrais['idfrais'];
                        $libelle = htmlspecialchars($unFrais['libelle']);
                        $quantite = $unFrais['quantite']; ?>
                        <div class="form-group">
                            <label for="idFrais"><?php echo $libelle ?></label>
                            <input type="text" id="idFrais" 
                                   name="lesFrais[<?php echo $idFrais ?>]"
                                   size="10" maxlength="5" 
                                   value="<?php echo $quantite ?>" 
                                   class="form-control">
                        </div>
                        <?php
                    }
                    ?>
                    <button class="btn btn-warning" type="submit">Corriger</button>
                    <button class="btn btn-danger" type="reset">Réinitialiser</button>
                </fieldset>
            </form>
        </div>
    </div>
    <br>
    <div class="panel panel-warning">
        <div class="panel-heading">Descriptif des éléments hors forfait </div>
        <table class="table table-bordered table-responsive ">
            <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>                
            </tr>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $date = $unFraisHorsForfait['date'];
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $montant = $unFraisHorsForfait['montant'];
                $idFraisHF = $unFraisHorsForfait['id']; 
                $refus = $unFraisHorsForfait['refus'];?>
                <tr>
                
                    <td><?php echo $date ?></td>
                    <td><?php if ($refus) {
                                    echo "REFUSE : ";
                                }
                                echo $libelle;
                        ?></td>
                    <td><?php echo $montant ?></td>
                    <td>
                        <form id="majFraisHorsForfait_<?php echo $idFraisHF ?>"
                            action="index.php?uc=validerFicheFrais&action=fraisHF"
                            method="post" role="form">
                            <input type="hidden" 
                                   name="idFraisHorsForfait"
                                   size="10" maxlength="5"
                                   value="<?php echo $idFraisHF?>">
                            <input type="hidden" 
                                   name="libelleHF"
                                   size="10" maxlength="5"
                                   value="<?php echo $libelle?>">
                            <input type="hidden" 
                                   name="dateHF"
                                   size="10" maxlength="5"
                                   value="<?php echo $date?>">
                            <input type="hidden" 
                                   name="montantHF"
                                   size="10" maxlength="5"
                                   value="<?php echo $montant?>">
      
                            <button form="majFraisHorsForfait_<?php echo $idFraisHF ?>" 
                                name="bouton"
                                value="reporter" class="btn btn-warning"
                                onclick="return confirm('Voulez-vous vraiment reporter ce frais?\n Attention cette action est irrévesible')"
                                type="submit" >
                                Reporter
                            </button>
                            <button form="majFraisHorsForfait_<?php echo $idFraisHF ?>" 
                                   name="bouton"
                                   value="supprimer" class="btn btn-danger" 
                                   onclick="return confirm('Voulez-vous vraiment supprimer ce frais?\n Attention cette action est irrévesible')"
                                   type="submit" >
                                   Supprimer
                            </button>
                        </form>
                    </td> 
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <div> 
        <form method="post" 
      action="index.php?uc=validerFicheFrais&action=validerFicheFrais" 
      role="form"  >  
        <b> Nombre de justificatifs :</b> <input name="nbJustificatifs" value="<?php echo $nbJustificatifs ?>" size="2"> <br>
        <br> <button class="btn btn-warning" type="submit">Valider</button> 
        <button class="btn btn-danger" type="reset">Réinitialiser</button>
        
        </form>
    </div>


