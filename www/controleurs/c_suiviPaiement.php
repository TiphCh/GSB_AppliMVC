<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$lesVisiteurs = $pdo->getLesVisiteursNonComptables();

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING); 
    
switch ($action) {
    
case 'afficherVisiteur' :
    include 'vues/v_suiviPaiement.php';
    break;

case 'listeMois' :
    $_SESSION['visiteurSelectionne'] = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_STRING);
    $idVisiteur = $_SESSION['visiteurSelectionne'];
    $lesMois = $pdo->getLesMoisDisponibles($_SESSION['visiteurSelectionne']);
    include 'vues/v_suiviPaiement.php';
    break;

case 'ficheFrais' :
    $idVisiteur = $_SESSION['visiteurSelectionne'];
    $_SESSION['moisSelectionne'] = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_STRING);
    $lesFraisForfait = $pdo->getLesFraisForfait($_SESSION['visiteurSelectionne'], $_SESSION['moisSelectionne']);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($_SESSION['visiteurSelectionne'], $_SESSION['moisSelectionne']);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($_SESSION['visiteurSelectionne'], $_SESSION['moisSelectionne']);
    $idEtat = $lesInfosFicheFrais['idEtat'];
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $dateModif = $lesInfosFicheFrais['dateModif'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    switch ($idEtat){
        case 'VA':        
        case 'MP' :
            $lesMois = $pdo->getLesMoisDisponibles($_SESSION['visiteurSelectionne']);
            include 'vues/v_suiviPaiement.php';
            include 'vues/v_suiviFicheFrais.php';
            break;

        default: 
            ajouterErreur('Pas de fiche disponible pour ce mois et ce visiteur');
            $lesMois = $pdo->getLesMoisDisponibles($_SESSION['visiteurSelectionne']);
            include 'vues/v_erreurs.php';
            include 'vues/v_suiviPaiement.php';
            break;
    }
    break;

case 'majEtatFiche':
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($_SESSION['visiteurSelectionne'], $_SESSION['moisSelectionne']);
    $idEtat = $lesInfosFicheFrais['idEtat'];
    if($idEtat == "VA"){
        $pdo->majEtatFicheFrais($_SESSION['visiteurSelectionne'], $_SESSION['moisSelectionne'], 'MP');
    }elseif($idEtat == "MP"){
        $pdo->majEtatFicheFrais($_SESSION['visiteurSelectionne'], $_SESSION['moisSelectionne'], 'RB');
    }
    include 'vues/v_success.php';
    break;
        
        

}

