<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING); 

switch ($action) {
case 'afficherVisiteurs' :
    $lesVisiteurs = $pdo->getLesVisiteursNonComptables();
    include 'vues/v_listeVisiteur.php';
    break;
case 'moisDispo' :
    $lesVisiteurs = $pdo->getLesVisiteursNonComptables();
    $idVisiteur = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_STRING);
    $_SESSION['visiteurSelectionne'] = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_STRING);
    $lesMois = $pdo->getLesMoisDisponiblesCL($idVisiteur);
    include 'vues/v_listeVisiteur.php';
    break;
case 'afficherFicheVisiteur':
    $lesVisiteurs = $pdo->getLesVisiteursNonComptables();
    $_SESSION['leMois'] = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_STRING);
    $idVisiteur = $_SESSION['visiteurSelectionne'];
    $lesMois = $pdo->getLesMoisDisponiblesCL($idVisiteur);
    
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $_SESSION['leMois']);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $_SESSION['leMois']);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $_SESSION['leMois']);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    
    include 'vues/v_listeVisiteur.php';
    include 'vues /v_validerFicheFrais.php';
    break;
    
case 'corrigerFrais':
    $lesVisiteurs = $pdo->getLesVisiteursNonComptables();
    $leMois = $_SESSION['leMois'];
    $idVisiteur = $_SESSION['visiteurSelectionne'];
    $lesMois = $pdo->getLesMoisDisponiblesCL($idVisiteur);
    
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];

    //Partie corrigerFrais
    $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
    if (lesQteFraisValides($lesFrais)) {
        $pdo->majFraisForfait($idVisiteur, $leMois, $lesFrais);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        include 'vues/v_success.php';
    } else {
        ajouterErreur('Les valeurs des champs doivent être numériques');
        include 'vues/v_erreurs.php';
    }
    include 'vues/v_listeVisiteur.php';
    include 'vues /v_validerFicheFrais.php';
    break;

case 'fraisHF':
    $lesVisiteurs = $pdo->getLesVisiteursNonComptables();
    $leMois = $_SESSION['leMois'];
    $idVisiteur = $_SESSION['visiteurSelectionne'];
    $lesMois = $pdo->getLesMoisDisponiblesCL($idVisiteur);
    
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    
    
    //Récupération du bouton pressé par l'utilisateur
    $boutonHorsForfait = filter_input(INPUT_POST, 'bouton', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
    switch($boutonHorsForfait) {
        case 'reporter':
            $leMoisSuivant = moisSuivant($leMois);
            $dateHF = filter_input(INPUT_POST, 'dateHF', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
            $libelleHF = filter_input(INPUT_POST, 'libelleHF', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
            $montantHF = filter_input(INPUT_POST, 'montantHF', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);
            if($pdo->estPremierFraisMois($idVisiteur, $leMoisSuivant)){
                $pdo->creeNouvellesLignesFrais($idVisiteur, $leMoisSuivant);
            } 
            $pdo->creeNouveauFraisHorsForfait($idVisiteur, $leMoisSuivant, $libelleHF, $dateHF, $montantHF);
            $fraisHF = filter_input(INPUT_POST, 'idFraisHorsForfait', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
            $pdo->supprimerFraisHorsForfait($fraisHF);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
            $lesMois = $pdo->getLesMoisDisponiblesCL($idVisiteur);
            break;
        case 'supprimer':
            $fraisHF = filter_input(INPUT_POST, 'idFraisHorsForfait', FILTER_DEFAULT, FILTER_SANITIZE_STRING);
            $pdo->supprimerFraisHorsForfait($fraisHF);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
            break;
    }
    include 'vues/v_listeVisiteur.php';
    include 'vues /v_validerFicheFrais.php';
    break;

case 'validerFicheFrais':
    $lesVisiteurs = $pdo->getLesVisiteursNonComptables();
    $leMois = $_SESSION['leMois'];
    $idVisiteur = $_SESSION['visiteurSelectionne'];
    $lesMois = $pdo->getLesMoisDisponiblesCL($idVisiteur);
    
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $nbJustificatifs = filter_input(INPUT_POST, 'nbJustificatifs', FILTER_DEFAULT, FILTER_SANITIZE_NUMBER_INT);
    $pdo->majNbJustificatifs($idVisiteur, $leMois, $nbJustificatifs);
    $pdo->majEtatFicheFrais($idVisiteur, $leMois, 'VA');
    
    include 'vues/v_validationFiche.php';
    break;
}