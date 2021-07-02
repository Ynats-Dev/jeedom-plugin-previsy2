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
    
    public function toHtml($_version = 'dashboard') {
        log::add('previsy2', 'debug', 'toHtml');
        $replace = $this->preToHtml($_version);
        
        if (!is_array($replace)) {
            return $replace;
        }
        
        $version = jeedom::versionAlias($_version);
        
        $refresh = $this->getCmd(null, 'refresh');
        $replace['#refresh_id#'] = is_object($refresh) ? $refresh->getId() : '';
        
        $message_alerte = $this->getConfiguration("message_alerte");
        $replace['#message_alerte#'] = "";
        
        $tmp = $arr = array();
        
        foreach ($this->getCmd() as $cmd) {  
            //$replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
            
            preg_match_all('!\d+!',$cmd->getLogicalId(),$i);
            
            $u = $i[0][0];

            if($cmd->execCmd() > 0){
                array_push($arr, $u);
                $arr = array_unique($arr);
                if($cmd->getLogicalId() == "alerte_" . $u . "_debut") { $tmp[$u]["alerte_debut"] = $cmd->execCmd(); }
                elseif($cmd->getLogicalId() == "alerte_" . $u . "_fin") { $tmp[$u]["alerte_fin"] = $cmd->execCmd(); }
                elseif($cmd->getLogicalId() == "alerte_" . $u . "_duree") { $tmp[$u]["alerte_duree"] = $cmd->execCmd(); }
            }
        }

        $wid = "";
        
        foreach ($arr as $value) {
            
            if($wid == "" AND $message_alerte != ""){
                $replace['#message_alerte#'] = "<div style='margin:5px 10px; text-align:center; border: 1px dotted;'>".$message_alerte."</div>";
            }
            
            $wid .= "<div style='margin:5px 10px;'>";
            $wid .= "<div style='display: inline-block; padding: 0 5px;'><i class='fas fa-exclamation-triangle'></i></div>";
            $wid .= "<div style='display: inline-block; padding: 0 5px;'>" . previsy2_widget::phraseDates($tmp[$value]) . "</div>";
            $wid .= "</div>";
        }
        
        $replace['#alertes#'] = $wid;

        return template_replace($replace, getTemplate('core', $version, 'previsy2', 'previsy2'));
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