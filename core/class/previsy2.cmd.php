<?php

require_once __DIR__ . "/previsy2.require_once.php";

class previsy2_cmd extends eqLogic {
    
    public static function createCmd($_array = NULL, $_this = NULL) {
        $info = $_this->getCmd(null, $_array["LogicalId"]);
        
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
    
    public static function createAllCmd($_this){
        
        $typeDegre = previsy2_config::getConfigFormatDegres();
        $nbAlerte = previsy2_config::getConfigNbAlerte();
        
        for ($alerte = 1; $alerte <= $nbAlerte; $alerte++) {
            
            // Informations de datation de l'alerte
            self::createCmd(["LogicalId" => "alerte_".$alerte."_debut", "Name" => "Alerte_".$alerte."_Debut", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_fin", "Name" => "Alerte_".$alerte."_Fin", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_duree", "Name" => "Alerte_".$alerte."_Duree", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);

            // Alertes liées aux types d'averses (pluie)
            self::createCmd(["LogicalId" => "alerte_".$alerte."_pluie_min", "Name" => "Alerte_".$alerte."_Pluie_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_pluie_max", "Name" => "Alerte_".$alerte."_Pluie_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_pluie_moyenne", "Name" => "Alerte_".$alerte."_Pluie_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
            
            // Alertes liées aux types d'averses (neige)
            self::createCmd(["LogicalId" => "alerte_".$alerte."_neige_min", "Name" => "Alerte_".$alerte."_Neige_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_neige_max", "Name" => "Alerte_".$alerte."_Neige_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_neige_moyenne", "Name" => "Alerte_".$alerte."_Neige_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => NULL], $_this);

            // Alertes liées au vent (beaufort)
            self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_beaufort_min", "Name" => "Alerte_".$alerte."_Vent_Force_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "km/h"], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_beaufort_max", "Name" => "Alerte_".$alerte."_Vent_Force_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "km/h"], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_beaufort_moyenne", "Name" => "Alerte_".$alerte."_Vent_Force_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "km/h"], $_this);
            
            // Alertes liées au vent (sens du vent)
            self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_orientation", "Name" => "Alerte_".$alerte."_Vent_Orientation", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "string", "Unite" => NULL], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_orientation2", "Name" => "Alerte_".$alerte."_Vent_Orientation2", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "string", "Unite" => NULL], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_orientation3", "Name" => "Alerte_".$alerte."_Vent_Orientation3", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "string", "Unite" => NULL], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_orientation4", "Name" => "Alerte_".$alerte."_Vent_Orientation4", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "string", "Unite" => NULL], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_vent_orientation5", "Name" => "Alerte_".$alerte."_Vent_Orientation5", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "string", "Unite" => NULL], $_this);

            //Alertes liées à la température (Min)
            self::createCmd(["LogicalId" => "alerte_".$alerte."_temperature_min_min", "Name" => "Alerte_".$alerte."_Temperature_Minimum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_temperature_min_max", "Name" => "Alerte_".$alerte."_Temperature_Minimum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_temperature_min_moyenne", "Name" => "Alerte_".$alerte."_Temperature_Minimum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            
            //Alertes liées à la température (Max)
            self::createCmd(["LogicalId" => "alerte_".$alerte."_temperature_max_min", "Name" => "Alerte_".$alerte."_Temperature_Maximum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_temperature_max_max", "Name" => "Alerte_".$alerte."_Temperature_Maximum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_temperature_max_moyenne", "Name" => "Alerte_".$alerte."_Temperature_Maximum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            
            //Alertes liées au refroidissement éolien (ou température ressentie) (Min)
            self::createCmd(["LogicalId" => "alerte_".$alerte."_refroidissement_min_min", "Name" => "Alerte_".$alerte."_Temperature_Ressentie_Minimum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_refroidissement_min_max", "Name" => "Alerte_".$alerte."_Temperature_Ressentie_Minimum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_refroidissement_min_moyenne", "Name" => "Alerte_".$alerte."_Temperature_Ressentie_Minimum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            
            //Alertes liées au refroidissement éolien (ou température ressentie) (Max)
            self::createCmd(["LogicalId" => "alerte_".$alerte."_refroidissement_max_min", "Name" => "Alerte_".$alerte."_Temperature_Ressentie_Maximum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_refroidissement_max_max", "Name" => "Alerte_".$alerte."_Temperature_Ressentie_Maximum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_refroidissement_max_moyenne", "Name" => "Alerte_".$alerte."_Temperature_Ressentie_Maximum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => $typeDegre], $_this);
            
            //Alertes liées au pourcentage d'humidité (Min)
            self::createCmd(["LogicalId" => "alerte_".$alerte."_humidite_min_min", "Name" => "Alerte_".$alerte."_Humidite_Minimum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "%"], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_humidite_min_max", "Name" => "Alerte_".$alerte."_Humidite_Minimum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "%"], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_humidite_min_moyenne", "Name" => "Alerte_".$alerte."_Humidite_Minimum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "%"], $_this);
            
            //Alertes liées au pourcentage d'humidité (Max)
            self::createCmd(["LogicalId" => "alerte_".$alerte."_humidite_max_min", "Name" => "Alerte_".$alerte."_Humidite_Maximum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "%"], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_humidite_max_max", "Name" => "Alerte_".$alerte."_Humidite_Maximum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "%"], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_humidite_max_moyenne", "Name" => "Alerte_".$alerte."_Humidite_Maximum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "%"], $_this);

            //Alertes liées à la pression atmosphérique (Min)
            self::createCmd(["LogicalId" => "alerte_".$alerte."_pression_min_min", "Name" => "Alerte_".$alerte."_Pression_Minimum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "hPa"], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_pression_min_max", "Name" => "Alerte_".$alerte."_Pression_Minimum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "hPa"], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_pression_min_moyenne", "Name" => "Alerte_".$alerte."_Pression_Minimum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "hPa"], $_this);            
            
            //Alertes liées à la pression atmosphérique (Max)
            self::createCmd(["LogicalId" => "alerte_".$alerte."_pression_max_min", "Name" => "Alerte_".$alerte."_Pression_Maximum_Min", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "hPa"], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_pression_max_max", "Name" => "Alerte_".$alerte."_Pression_Maximum_Max", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "hPa"], $_this);
            self::createCmd(["LogicalId" => "alerte_".$alerte."_pression_max_moyenne", "Name" => "Alerte_".$alerte."_Pression_Maximum_Moyenne", "Historized" => 1, "Visible" => 0, "Type" => "info", "SubType" => "numeric", "Unite" => "hPa"], $_this);
        
        }
    }
    
}
