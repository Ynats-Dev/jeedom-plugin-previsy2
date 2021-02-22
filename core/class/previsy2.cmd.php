<?php

require_once __DIR__ . "/previsy2.require_once.php";

class previsy2_cmd extends eqLogic {
    
    public static function createCmd($_array = NULL, $_this = NULL) {
        $info = $_this->getCmd(NULL, $_array["LogicalId"]);
        
        if (!is_object($info)) {
            $info = new previsy2Cmd();
            $info->setName(__($_array["Name"], __FILE__));
        }
        
        $info->setLogicalId($_array["LogicalId"]);
        $info->setEqLogic_id($_this->getId());
        $info->setIsHistorized($_array["Historized"]);
        $info->setIsVisible($_array["Visible"]);
        $info->setType($_array["Type"]);
        $info->setSubType($_array["SubType"]);
        
        if (!empty($_array["Unite"]) AND "Unite" != NULL ) {
            $info->setUnite($_array["Unite"]);
        }
        
        $info->save();
    }
    
    public static function removeCmd($_LogicalId, $_this = NULL){
        $info = $_this->getCmd(NULL, $_LogicalId);
        if (is_object($info)) {
            $info->remove();
        }
    }
    
    public static function createAllCmd($_this){
        
        $delCmd = FALSE;
        $typeDegre = previsy2_config::getConfigFormatDegres();
        $nbAlerte = previsy2_config::getConfigNbAlerte();
        
        for ($alerte = 1; $alerte <= $nbAlerte; $alerte++) {
            
            // Informations de datation de l'alerte
            self::createCmd(["LogicalId" => "alerte_".$alerte."_debut", "Name" => "Alerte_".$alerte."_Debut", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_fin", "Name" => "Alerte_".$alerte."_Fin", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_duree", "Name" => "Alerte_".$alerte."_Duree", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);

            // Alertes liées aux types d'averses (pluie)
            if ($_this->getConfiguration("pluie") == 1) { 
                self::createCmd(["LogicalId" => "alerte_" . $alerte . "_pluie_min", "Name" => "Alerte_" . $alerte . "_Pluie_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
                self::createCmd(["LogicalId" => "alerte_" . $alerte . "_pluie_max", "Name" => "Alerte_" . $alerte . "_Pluie_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
                self::createCmd(["LogicalId" => "alerte_" . $alerte . "_pluie_moyenne", "Name" => "Alerte_" . $alerte . "_Pluie_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_pluie_min"))){ 
                    self::removeCmd("alerte_" . $alerte . "_pluie_min", $_this);
                    self::removeCmd("alerte_" . $alerte . "_pluie_max", $_this);
                    self::removeCmd("alerte_" . $alerte . "_pluie_moyenne", $_this);
                    $delCmd = TRUE;
                }
            }
            
            // Alertes liées aux types d'averses (neige)
            if ($_this->getConfiguration("neige") == 1) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_neige_min", "Name" => "Alerte_".$alerte."_Neige_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_neige_max", "Name" => "Alerte_".$alerte."_Neige_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_neige_moyenne", "Name" => "Alerte_".$alerte."_Neige_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_neige_min"))){
                    self::removeCmd("alerte_" . $alerte . "_neige_min", $_this);
                    self::removeCmd("alerte_" . $alerte . "_neige_max", $_this);
                    self::removeCmd("alerte_" . $alerte . "_neige_moyenne", $_this);
                    $delCmd = TRUE;
                }
            }
            
            // Alertes liées au vent (beaufort)
            if ($_this->getConfiguration("seuilVent", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_beaufort_min", "Name" => "Alerte_".$alerte."_Vent_Force_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "km/h"], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_beaufort_max", "Name" => "Alerte_".$alerte."_Vent_Force_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "km/h"], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_beaufort_moyenne", "Name" => "Alerte_".$alerte."_Vent_Force_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "km/h"], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_vent_beaufort_min"))){
                    self::removeCmd("alerte_" . $alerte . "_vent_beaufort_min", $_this);
                    self::removeCmd("alerte_" . $alerte . "_vent_beaufort_max", $_this);
                    self::removeCmd("alerte_" . $alerte . "_vent_beaufort_moyenne", $_this);
                    $delCmd = TRUE;
                }
            }
            
            // Alertes liées au vent (sens du vent)
            if ($_this->getConfiguration("directionVent", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_orientation", "Name" => "Alerte_".$alerte."_Vent_Orientation", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "string", "Unite" => NULL], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_vent_orientation"))){
                    self::removeCmd("alerte_" . $alerte . "_vent_orientation", $_this);
                    $delCmd = TRUE;
                }
            }
            
            if ($_this->getConfiguration("directionVent2", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_orientation2", "Name" => "Alerte_".$alerte."_Vent_Orientation2", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "string", "Unite" => NULL], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_vent_orientation2"))){
                    self::removeCmd("alerte_" . $alerte . "_vent_orientation2", $_this);
                    $delCmd = TRUE;
                }
            }
            
            if ($_this->getConfiguration("directionVent3", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_orientation3", "Name" => "Alerte_".$alerte."_Vent_Orientation3", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "string", "Unite" => NULL], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_vent_orientation3"))){
                    self::removeCmd("alerte_" . $alerte . "_vent_orientation3", $_this);
                    $delCmd = TRUE;
                }
            }
            
            if ($_this->getConfiguration("directionVent4", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_orientation4", "Name" => "Alerte_".$alerte."_Vent_Orientation4", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "string", "Unite" => NULL], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_vent_orientation4"))){
                    self::removeCmd("alerte_" . $alerte . "_vent_orientation4", $_this);
                    $delCmd = TRUE;
                }   
            }
            
            if ($_this->getConfiguration("directionVent5", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_orientation5", "Name" => "Alerte_".$alerte."_Vent_Orientation5", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "string", "Unite" => NULL], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_vent_orientation5"))){
                    self::removeCmd("alerte_" . $alerte . "_vent_orientation5", $_this);
                    $delCmd = TRUE;
                }
            }
            
            //Alertes liées à la température (Min)
            if ($_this->getConfiguration("temperatureMin", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_temperature_min_min", "Name" => "Alerte_".$alerte."_Temperature_Minimum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_temperature_min_max", "Name" => "Alerte_".$alerte."_Temperature_Minimum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_temperature_min_moyenne", "Name" => "Alerte_".$alerte."_Temperature_Minimum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_temperature_min_min"))){
                    self::removeCmd("alerte_" . $alerte . "_temperature_min_min", $_this);
                    self::removeCmd("alerte_" . $alerte . "_temperature_min_max", $_this);
                    self::removeCmd("alerte_" . $alerte . "_temperature_min_moyenne", $_this);
                    $delCmd = TRUE;
                }
            }
            
            //Alertes liées à la température (Max)
            if ($_this->getConfiguration("temperatureMax", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_temperature_max_min", "Name" => "Alerte_".$alerte."_Temperature_Maximum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_temperature_max_max", "Name" => "Alerte_".$alerte."_Temperature_Maximum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_temperature_max_moyenne", "Name" => "Alerte_".$alerte."_Temperature_Maximum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_temperature_max_min"))){
                    self::removeCmd("alerte_" . $alerte . "_temperature_max_min", $_this);
                    self::removeCmd("alerte_" . $alerte . "_temperature_max_max", $_this);
                    self::removeCmd("alerte_" . $alerte . "_temperature_max_moyenne", $_this);
                    $delCmd = TRUE;
                }
            }
            
            //Alertes liées au refroidissement éolien (ou température ressentie) (Min)
            if ($_this->getConfiguration("refroidissementMin", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_refroidissement_min_min", "Name" => "Alerte_".$alerte."_Temperature_Ressentie_Minimum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_refroidissement_min_max", "Name" => "Alerte_".$alerte."_Temperature_Ressentie_Minimum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_refroidissement_min_moyenne", "Name" => "Alerte_".$alerte."_Temperature_Ressentie_Minimum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_refroidissement_min_min"))){
                    self::removeCmd("alerte_" . $alerte . "_refroidissement_min_min", $_this);
                    self::removeCmd("alerte_" . $alerte . "_refroidissement_min_max", $_this);
                    self::removeCmd("alerte_" . $alerte . "_refroidissement_min_moyenne", $_this);
                    $delCmd = TRUE;
                }
            }
            
            //Alertes liées au refroidissement éolien (ou température ressentie) (Max)
            if ($_this->getConfiguration("refroidissementMax", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_refroidissement_max_min", "Name" => "Alerte_".$alerte."_Temperature_Ressentie_Maximum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_refroidissement_max_max", "Name" => "Alerte_".$alerte."_Temperature_Ressentie_Maximum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_refroidissement_max_moyenne", "Name" => "Alerte_".$alerte."_Temperature_Ressentie_Maximum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_refroidissement_max_min"))){
                    self::removeCmd("alerte_" . $alerte . "_refroidissement_max_min", $_this);
                    self::removeCmd("alerte_" . $alerte . "_refroidissement_max_max", $_this);
                    self::removeCmd("alerte_" . $alerte . "_refroidissement_max_moyenne", $_this);
                    $delCmd = TRUE;
                }
            }
            
            //Alertes liées au pourcentage d'humidité (Min)
            if ($_this->getConfiguration("humiditeMin", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_humidite_min_min", "Name" => "Alerte_".$alerte."_Humidite_Minimum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "%"], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_humidite_min_max", "Name" => "Alerte_".$alerte."_Humidite_Minimum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "%"], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_humidite_min_moyenne", "Name" => "Alerte_".$alerte."_Humidite_Minimum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "%"], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_humidite_min_min"))){
                    self::removeCmd("alerte_" . $alerte . "_humidite_min_min", $_this);
                    self::removeCmd("alerte_" . $alerte . "_humidite_min_max", $_this);
                    self::removeCmd("alerte_" . $alerte . "_humidite_min_moyenne", $_this);
                    $delCmd = TRUE;
                }
            }
            
            //Alertes liées au pourcentage d'humidité (Max)
            if ($_this->getConfiguration("humiditeMax", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_humidite_max_min", "Name" => "Alerte_".$alerte."_Humidite_Maximum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "%"], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_humidite_max_max", "Name" => "Alerte_".$alerte."_Humidite_Maximum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "%"], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_humidite_max_moyenne", "Name" => "Alerte_".$alerte."_Humidite_Maximum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "%"], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_humidite_max_min"))){
                    self::removeCmd("alerte_" . $alerte . "_humidite_max_min", $_this);
                    self::removeCmd("alerte_" . $alerte . "_humidite_max_max", $_this);
                    self::removeCmd("alerte_" . $alerte . "_humidite_max_moyenne", $_this);
                    $delCmd = TRUE;
                }
            }
            
            //Alertes liées à la pression atmosphérique (Min)
            if ($_this->getConfiguration("pressionMin", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_pression_min_min", "Name" => "Alerte_".$alerte."_Pression_Minimum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "hPa"], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_pression_min_max", "Name" => "Alerte_".$alerte."_Pression_Minimum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "hPa"], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_pression_min_moyenne", "Name" => "Alerte_".$alerte."_Pression_Minimum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "hPa"], $_this);            
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_pression_min_min"))){
                    self::removeCmd("alerte_" . $alerte . "_pression_min_min", $_this);
                    self::removeCmd("alerte_" . $alerte . "_pression_min_max", $_this);
                    self::removeCmd("alerte_" . $alerte . "_pression_min_moyenne", $_this);
                    $delCmd = TRUE;
                }
            }
            
            //Alertes liées à la pression atmosphérique (Max)
            if ($_this->getConfiguration("pressionMax", NULL) != NULL) {
                self::createCmd(["LogicalId" => "alerte_".$alerte."_pression_max_min", "Name" => "Alerte_".$alerte."_Pression_Maximum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "hPa"], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_pression_max_max", "Name" => "Alerte_".$alerte."_Pression_Maximum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "hPa"], $_this);
                self::createCmd(["LogicalId" => "alerte_".$alerte."_pression_max_moyenne", "Name" => "Alerte_".$alerte."_Pression_Maximum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "hPa"], $_this);
            } else {
                if(is_object($_this->getCmd(null, "alerte_" . $alerte . "_pression_max_min"))){
                    self::removeCmd("alerte_" . $alerte . "_pression_max_min", $_this);
                    self::removeCmd("alerte_" . $alerte . "_pression_max_max", $_this);
                    self::removeCmd("alerte_" . $alerte . "_pression_max_moyenne", $_this);
                    $delCmd = TRUE;
                }
            }
            
        }
        
        return $delCmd;
        
    }
    
}
