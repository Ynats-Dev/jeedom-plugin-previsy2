<?php

require_once __DIR__ . "/previsy2.require_once.php";

class previsy2_metier extends eqLogic {
    
    public static function allSynchro(){
        $eqLogics = eqLogic::byType('previsy2');
        foreach ($eqLogics as $previsy2) {
            if ($previsy2->getIsEnable() == 1) {
                // Récupère données et enregistre dans Json
                self::record($previsy2->getId());
                
                // Traite les données
                self::execute($previsy2);
            }
        }
    }
    
    public static function record($_id){ // J'en suis ici

        $eqLogic = self::byId($_id);
        
        $config = previsy2_config::constructArray($eqLogic);
        
        if($config["api"]["bridge"] == "prevision_meteo_ch"){

            if(!file_exists(previsy2_json::$_jsonLocalize.$_id.".json") OR (file_exists(previsy2_json::$_jsonLocalize.$_id.".json") AND (time() - filemtime(previsy2_json::$_jsonLocalize.$_id.".json")) > 10800)) { // Si dernière synchro à moins de 3 heures
                if(previsy2_prevision_meteo_ch::checkApi(previsy2_prevision_meteo_ch::$_urlApi) == TRUE){ 
                    $localisationData = previsy2_prevision_meteo_ch::readApiToArray($config["localisation"]); 
                    if($localisationData["data"] != NULL){ 
                        $localisationMapping = previsy2_prevision_meteo_ch::mapping($localisationData);
                        previsy2_json::recordInJson(previsy2_json::$_jsonLocalize.$_id, $localisationMapping);
                    }
                }
            }
            
            if(!file_exists(previsy2_json::$_jsonLocalize.$_id.".json")) {
                return FALSE;
            } else {
                $datasJson = previsy2_json::getJson(previsy2_json::$_jsonLocalize.$_id.".json");
                $datasData = previsy2_constructData::start($datasJson, $config["alertes"]);
                previsy2_json::recordInJson(previsy2_json::$_jsonDatas.$_id, $datasData);
            }
            
            if(!file_exists(previsy2_json::$_jsonDatas.$_id.".json")) {
                return FALSE;
            } else {
                return TRUE;
            }
            
        }
        
    }
    
    public static function execute($_previsy2){
        
        if(file_exists(previsy2_json::$_jsonDatas.$_previsy2->getId().".json")) {
            
            $nbAlerte = previsy2_config::getConfigNbAlerte();
            
            $datas = previsy2_json::getJson(previsy2_json::$_jsonDatas.$_previsy2->getId().".json"); 
            
            // Nettoyage des commandes
            previsy2_cmd::clearCmd($_previsy2);

            log::add('previsy2', 'debug', '---------------------------------------------------------------------------------------');
            log::add('previsy2', 'debug', "#". $_previsy2->getId() . "> Traitement des données " . $datas["infos_localisation"]["ville"] . "(" . $datas["infos_localisation"]["latitude"] . ", " . $datas["infos_localisation"]["longitude"] . ")");
            
            if(!empty($datas["alertes"][1])){ 
                
                $_previsy2->checkAndUpdateCmd("alerte_personalize" , $_previsy2->getConfiguration("message_alerte"));
            
                foreach ($datas["alertes"] as $key => $alerte) { 
                    
                    if($key <= $nbAlerte ){

                        // Informations de datation de l'alerte
                        $_previsy2->checkAndUpdateCmd("alerte_" . $key . "_debut" , $alerte["date"]["stats"]["min"]);
                        $_previsy2->checkAndUpdateCmd("alerte_" . $key . "_fin" , ($alerte["date"]["stats"]["max"]+1));
                        $_previsy2->checkAndUpdateCmd("alerte_" . $key . "_duree" , $alerte["date"]["stats"]["nb_heure"]);

                        // Alertes liées aux types d'averses (pluie)
                        if ($_previsy2->getConfiguration("pluie") == 1) {
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_pluie_min", $alerte["pluie"]["stats"]["min"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_pluie_max", $alerte["pluie"]["stats"]["max"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_pluie_moyenne", $alerte["pluie"]["stats"]["moyenne"]);
                        }

                        // Alertes liées aux types d'averses (neige)
                        if ($_previsy2->getConfiguration("neige") == 1) {
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_neige_min", $alerte["neige"]["stats"]["min"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_neige_max", $alerte["neige"]["stats"]["max"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_neige_moyenne", $alerte["neige"]["stats"]["moyenne"]);
                        }

                        // Alertes liées au vent (beaufort)
                        if ($_previsy2->getConfiguration("seuilVent", NULL) != NULL) {
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_vent_beaufort_min", $alerte["vent_10m"]["stats"]["min"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_vent_beaufort_max", $alerte["vent_10m"]["stats"]["max"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_vent_beaufort_moyenne", $alerte["vent_10m"]["stats"]["moyenne"]);
                        }
                        
                        $dir_vent_cadran = array_unique($alerte["dir_vent_cadran"]["data"]); 
                        
                        foreach ($dir_vent_cadran as $sensVent) { 
                            if($sensVent == $_previsy2->getConfiguration("directionVent", NULL)){
                                $_previsy2->checkAndUpdateCmd("alerte_".$key."_vent_orientation", $sensVent);
                            }
                            elseif($sensVent == $_previsy2->getConfiguration("directionVent2", NULL)){
                                $_previsy2->checkAndUpdateCmd("alerte_".$key."_vent_orientation2", $sensVent);
                            }
                            elseif($sensVent == $_previsy2->getConfiguration("directionVent3", NULL)){
                                $_previsy2->checkAndUpdateCmd("alerte_".$key."_vent_orientation3", $sensVent);
                            }
                            elseif($sensVent == $_previsy2->getConfiguration("directionVent4", NULL)){
                                $_previsy2->checkAndUpdateCmd("alerte_".$key."_vent_orientation4", $sensVent);
                            }
                            elseif($sensVent == $_previsy2->getConfiguration("directionVent5", NULL)){
                                $_previsy2->checkAndUpdateCmd("alerte_".$key."_vent_orientation5", $sensVent);
                            }
                        }

                        //Alertes liées à la température (Min)
                        if ($_previsy2->getConfiguration("temperatureMin", NULL) != NULL) {
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_temperature_min_min", $alerte["temperature"]["stats"]["min"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_temperature_min_max", $alerte["temperature"]["stats"]["max"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_temperature_min_moyenne", $alerte["temperature"]["stats"]["moyenne"]);
                        }

                        //Alertes liées à la température (Max)
                        if ($_previsy2->getConfiguration("temperatureMax", NULL) != NULL) {
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_temperature_max_min", $alerte["temperature"]["stats"]["min"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_temperature_max_max", $alerte["temperature"]["stats"]["max"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_temperature_max_moyenne", $alerte["temperature"]["stats"]["moyenne"]);
                        }
                        
                        //Alertes liées au refroidissement éolien (ou température ressentie) (Min)
                        if ($_previsy2->getConfiguration("refroidissementMin", NULL) != NULL) {
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_refroidissement_min_min", $alerte["refroidissement-eolien"]["stats"]["min"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_refroidissement_min_max", $alerte["refroidissement-eolien"]["stats"]["max"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_refroidissement_min_moyenne", $alerte["refroidissement-eolien"]["stats"]["moyenne"]);
                        }

                        //Alertes liées au refroidissement éolien (ou température ressentie) (Max)
                        if ($_previsy2->getConfiguration("refroidissementMax", NULL) != NULL) {
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_refroidissement_max_min", $alerte["refroidissement-eolien"]["stats"]["min"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_refroidissement_max_max", $alerte["refroidissement-eolien"]["stats"]["max"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_refroidissement_max_moyenne", $alerte["refroidissement-eolien"]["stats"]["moyenne"]);
                        }

                        //Alertes liées au pourcentage d'humidité (Min)
                        if ($_previsy2->getConfiguration("humiditeMin", NULL) != NULL) {
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_humidite_min_min", $alerte["humidite_pourc"]["stats"]["min"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_humidite_min_max", $alerte["humidite_pourc"]["stats"]["max"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_humidite_min_moyenne", $alerte["humidite_pourc"]["stats"]["moyenne"]);
                        }

                        //Alertes liées au pourcentage d'humidité (Max)
                        if ($_previsy2->getConfiguration("humiditeMax", NULL) != NULL) {
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_humidite_max_min", $alerte["humidite_pourc"]["stats"]["min"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_humidite_max_max", $alerte["humidite_pourc"]["stats"]["max"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_humidite_max_moyenne", $alerte["humidite_pourc"]["stats"]["moyenne"]);
                        }

                        //Alertes liées à la pression atmosphérique (Min)
                        if ($_previsy2->getConfiguration("pressionMin", NULL) != NULL) {
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_pression_min_min", $alerte["pression"]["stats"]["min"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_pression_min_max", $alerte["pression"]["stats"]["max"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_pression_min_moyenne", $alerte["pression"]["stats"]["moyenne"]);
                        }

                        //Alertes liées à la pression atmosphérique (Max)
                        if ($_previsy2->getConfiguration("pressionMax", NULL) != NULL) {
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_pression_max_min", $alerte["pression"]["stats"]["min"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_pression_max_max", $alerte["pression"]["stats"]["max"]);
                            $_previsy2->checkAndUpdateCmd("alerte_".$key."_pression_max_moyenne", $alerte["pression"]["stats"]["moyenne"]);
                        }

                    }
                }
            }
        } else {
            return FALSE;
        }
    }
     
}
