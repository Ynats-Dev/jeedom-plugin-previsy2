<?php

require_once __DIR__ . "/previsy2.require_once.php";

class previsy2_config extends eqLogic {

    public static function getConfigFormatDegres() {
        return config::byKey('type_degre', 'previsy2', "°C");
    }

    public static function getConfigNbAlerte() {
        return config::byKey('nb_alerte', 'previsy2', 1);
    }
    
    public static function getConfigMode() {
        return config::byKey('mode_plugin', 'previsy2', 'normal');
    }
    
    public static function constructArray($_eqLogic){
        
        $return = array();

        $return["api"]["bridge"] = "prevision_meteo_ch";

        $ville = $_eqLogic->getConfiguration("ville");
        $latitude = $_eqLogic->getConfiguration("latitude");
        $longitude = $_eqLogic->getConfiguration("longitude");

        if (!empty($latitude) AND !empty($longitude)) {
            $return["localisation"]["latitude"] = $latitude;
            $return["localisation"]["longitude"] = $longitude;
            $return["localisation"]["ville"] = NULL;
        } else {
            $return["localisation"]["latitude"] = NULL;
            $return["localisation"]["longitude"] = NULL;
            $return["localisation"]["ville"] = $ville;
        }

        $return["alertes"] = array();

        if ($_eqLogic->getConfiguration("pluie") == 1) {
            $return["alertes"]["pluie"] = 1;
        }
        
        if ($_eqLogic->getConfiguration("neige") == 1) {
            $return["alertes"]["neige"] = 1;
        }
        
        $vent = $_eqLogic->getConfiguration("vent", NULL);
        if ($vent != NULL) {
            $return["alertes"]["vent"] = $vent;
        }
        
        $vent_dir = $_eqLogic->getConfiguration("vent_dir", NULL);
        if ($vent_dir != NULL) {
            $return["alertes"]["vent_dir"] = $vent_dir;
        }
        
        $vent_dir2 = $_eqLogic->getConfiguration("vent_dir2", NULL);
        if ($vent_dir2 != NULL) {
            $return["alertes"]["vent_dir2"] = $vent_dir2;
        }
        
        $vent_dir3 = $_eqLogic->getConfiguration("vent_dir3", NULL);
        if ($vent_dir3 != NULL) {
            $return["alertes"]["vent_dir3"] = $vent_dir3;
        }
        
        $vent_dir4 = $_eqLogic->getConfiguration("vent_dir4", NULL);
        if ($vent_dir4 != NULL) {
            $return["alertes"]["vent_dir4"] = $vent_dir4;
        }
        
        $temperature_min = $_eqLogic->getConfiguration("temperatureMin", NULL);
        if ($temperature_min != NULL) {
            $return["alertes"]["temperature_min"] = $temperature_min;
        }
        
        $temperature_max = $_eqLogic->getConfiguration("temperatureMax", NULL);
        if ($temperature_max != NULL) {
            $return["alertes"]["temperature_max"] = $temperature_max;
        }
        
        $refroidissement_min = $_eqLogic->getConfiguration("refroidissementMin", NULL);
        if ($refroidissement_min != NULL) {
            $return["alertes"]["refroidissement_min"] = $refroidissement_min;
        }
        
        $refroidissement_max = $_eqLogic->getConfiguration("refroidissementMax", NULL);
        if ($refroidissement_max != NULL) {
            $return["alertes"]["refroidissement_max"] = $refroidissement_max;
        }
        
        $pression_min = $_eqLogic->getConfiguration("pressionMin", NULL);
        if ($pression_min != NULL) {
            $return["alertes"]["pression_min"] = $pression_min;
        }
        
        $pression_max = $_eqLogic->getConfiguration("pressionMax", NULL);
        if ($pression_max != NULL) {
            $return["alertes"]["pression_max"] = $pression_max;
        }
        
        $humidite_pourc_min = $_eqLogic->getConfiguration("humiditeMin", NULL);
        if ($humidite_pourc_min != NULL) {
            $return["alertes"]["humidite_pourc_min"] = $humidite_pourc_min;
        }
        
        $humidite_pourc_max = $_eqLogic->getConfiguration("humiditeMax", NULL);
        if ($humidite_pourc_max != NULL) {
            $return["alertes"]["humidite_pourc_max"] = $humidite_pourc_max;
        }
        
        return $return;
        
    }
    
}