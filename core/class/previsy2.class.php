<?php

require_once __DIR__ . "/previsy2.require_once.php";

class previsy2 extends eqLogic {

    public function postSave() { 
        
        // Mise à jours des commandes
        $delCmd = previsy2_cmd::createAllCmd($this);

        // Récupération données et enregistrement des JSON
        previsy2_metier::allSynchro(); 
        
        if($delCmd == TRUE) {
            ajax::success(utils::o2a($this));
        }
        
    }
    
    public static function cronHourly() {
        $eqLogics = eqLogic::byType('previsy2');
        foreach ($eqLogics as $previsy2) {
            if ($previsy2->getIsEnable() == 1) {
                // Récupération données et enregistrement des JSON
                previsy2_metier::allSynchro();
                
                log::add('previsy2', 'debug', '---------------------------------------------------------------------------------------');
                log::add('previsy2', 'debug', __('cronHourly :. ', __FILE__) . __('Lancement pour #ID# ', __FILE__) . $previsy2->getId());
            }
        }
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