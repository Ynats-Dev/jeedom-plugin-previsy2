<?php

require_once __DIR__ . "/previsy2.require_once.php";

class previsy2 extends eqLogic {

    public function postSave() { 
        
        // Mise à jours des commandes
        previsy2_cmd::createAllCmd($this);

        // Récupération données et enregistrement des JSON
        previsy2_metier::allSynchro(); 
        
    }
         
}

class previsy2Cmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {

    }

    /*     * **********************Getteur Setteur*************************** */
}