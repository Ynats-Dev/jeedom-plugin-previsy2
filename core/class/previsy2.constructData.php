<?php

require_once __DIR__ . "/previsy2.require_once.php";

class previsy2_constructData extends eqLogic {

    public static function start($_array, $_config = NULL) {

        $return = array();

        $return["execution"] = $_array["execution"];

        $return["execution"]["script"]["date"] = intval(date("YmdH"));

        $return["infos_localisation"] = $_array["infos_localisation"];

        $matchPoint = self::comptePointsConfig($_config);
        
        $compteur = $lastMatch = 0;

        foreach ($_array["jours"] as $jours => $arrayJour) {

            foreach ($arrayJour["heures"] as $heures => $valHeures) {

                if (!empty($valHeures["date"]) AND $return["execution"]["script"]["date"] <= $valHeures["date"]) { 

                    $match = 0;

                    // Alerte Pluie
                    if (isset($_config["pluie"]) AND $_config["pluie"] == 1 AND $valHeures["pluie"] > 0) {
                        $match++;
                    }
                    
                    if (isset($_config["no_pluie"]) AND $_config["no_pluie"] == 1 AND $valHeures["pluie"] == 0) {
                        $match++;
                    }

                    // Alerte Neige
                    if (isset($_config["neige"]) AND $_config["neige"] == 1 AND $valHeures["neige"] > 0) {
                        $match++;
                    }
                    
                    if (isset($_config["no_neige"]) AND $_config["no_neige"] == 1 AND $valHeures["neige"] == 0) {
                        $match++;
                    }
                    

                    // Alertes Vent
                    if (isset($_config["vent"]) AND previsy2_tools::seuilVentBeaufort($valHeures["vent_10m"]) >= $_config["vent"]) {
                        $match++;
                    }

                    // Alertes Direction Vent Cadran
                    if (
                            (isset($_config["vent_dir"]) AND $valHeures["dir_vent_cadran"] == $_config["vent_dir"]) OR
                            (isset($_config["vent_dir2"]) AND $valHeures["dir_vent_cadran"] == $_config["vent_dir2"]) OR
                            (isset($_config["vent_dir3"]) AND $valHeures["dir_vent_cadran"] == $_config["vent_dir3"]) OR
                            (isset($_config["vent_dir4"]) AND $valHeures["dir_vent_cadran"] == $_config["vent_dir4"])
                    ) {
                        $match++;
                    }

                    // Alerte Température Minimum
                    if (isset($_config["temperature_min"]) AND $valHeures["temperature"] <= $_config["temperature_min"]) {
                        $match["temperature_min"] = 1;
                    }

                    // Alerte Température Maximum
                    if (isset($_config["temperature_max"]) AND $valHeures["temperature"] >= $_config["temperature_max"]) {
                        $match++;
                    }

                    // Alerte Pression Minimum
                    if (isset($_config["pression_min"]) AND $valHeures["pression"] <= $_config["pression_min"]) {
                        $match++;
                    }

                    // Alerte Pression Maximum
                    if (isset($_config["pression_max"]) AND $valHeures["pression"] >= $_config["pression_max"]) {
                        $match++;
                    }

                    // Alerte Pourcentage Humidité Minimum
                    if (isset($_config["humidite_pourc_min"]) AND $valHeures["humidite_pourc"] <= $_config["humidite_pourc_min"]) {
                        $match++;
                    }

                    // $returnPourcentage Humidité Maximum
                    if (isset($_config["humidite_pourc_max"]) AND $valHeures["humidite_pourc"] >= $_config["humidite_pourc_max"]) {
                        $match++;
                    }
                    
                    if ($matchPoint == $match AND $match > 0) {
                         
                        if (($valHeures["timestamp"] - $lastMatch) > 3600) {
                            $compteur++;
                        }

                        if(empty($return["alertes"][$compteur])) { 
                            $return["alertes"][$compteur] = array();
                        }

                        $return["alertes"][$compteur] = self::addArray($valHeures, $return["alertes"][$compteur]);
                        
                        $lastMatch = $valHeures["timestamp"];
                    }
                    
                }
            }
        }

        return $return;
    }
    
    public static function comptePointsConfig($_config) {
        
        $return = 0;
        
        if (!empty($_config["pluie"])) {
            $return++;
        }
        if (!empty($_config["no_pluie"])) {
            $return++;
        }
        if (!empty($_config["neige"])) {
            $return++;
        }
        if (!empty($_config["no_neige"])) {
            $return++;
        }
        if (!empty($_config["temperature_min"])) {
            $return++;
        }
        if (!empty($_config["temperature_max"])) {
            $return++;
        }
        if (!empty($_config["pression_min"])) {
            $return++;
        }
        if (!empty($_config["pression_max"])) {
            $return++;
        }
        if (!empty($_config["humidite_pourc_min"])) {
            $return++;
        }
        if (!empty($_config["humidite_pourc_max"])) {
            $return++;
        }
        if (!empty($_config["vent"])) {
            $return++;
        }
        if (
                !empty($_config["vent_dir"]) OR
                !empty($_config["vent_dir2"]) OR
                !empty($_config["vent_dir3"]) OR
                !empty($_config["vent_dir4"])
        ) {
            $return++;
        }
        return $return;

    }

    public static function addArray($_valHeures, $_old) {

        $return = array();

        $return["timestamp"] = self::dataCompose($_old, "timestamp", $_valHeures["timestamp"]);
        $return["date"] = self::dataCompose($_old, "date", $_valHeures["date"]);
        $return["id_condition"] = self::dataCompose($_old, "id_condition", $_valHeures["id_condition"]);
        $return["pluie"] = self::dataCompose($_old, "pluie", $_valHeures["pluie"]);
        $return["no_pluie"] = self::dataCompose($_old, "vpluie", $_valHeures["no_pluie"]);
        $return["neige"] = self::dataCompose($_old, "neige", $_valHeures["neige"]);
        $return["no_neige"] = self::dataCompose($_old, "no_neige", $_valHeures["no_neige"]);
        $return["refroidissement-eolien"] = self::dataCompose($_old, "refroidissement-eolien", $_valHeures["refroidissement-eolien"]);
        $return["temperature"] = self::dataCompose($_old, "temperature", $_valHeures["temperature"]);
        $return["humidite_pourc"] = self::dataCompose($_old, "humidite_pourc", $_valHeures["humidite_pourc"]);
        $return["pression"] = self::dataCompose($_old, "pression", $_valHeures["pression"]);
        $return["vent_10m"] = self::dataCompose($_old, "vent_10m", $_valHeures["vent_10m"]);
        $return["rafale_10m"] = self::dataCompose($_old, "rafale_10m", $_valHeures["rafale_10m"]);
        $return["point_rosee"] = self::dataCompose($_old, "point_rosee", $_valHeures["point_rosee"]);
        $return["dir_vent_deg"] = self::dataCompose($_old, "dir_vent_deg", $_valHeures["dir_vent_deg"]);
        $return["dir_vent_cadran"] = self::dataCompose($_old, "dir_vent_cadran", $_valHeures["dir_vent_cadran"]);
        $return["moy_altitude_nuages"] = self::dataCompose($_old, "moy_altitude_nuages", $_valHeures["moy_altitude_nuages"]);
        $return["isotherme_zero"] = self::dataCompose($_old, "isotherme_zero", $_valHeures["isotherme_zero"]);
        $return["k_index"] = self::dataCompose($_old, "k_index", $_valHeures["k_index"]);

        return $return;
    }

    public static function dataCompose($_array, $_key, $_value) {

        if (empty($_array[$_key]["data"])) {
            $_array[$_key]["data"] = $_array[$_key]["stats"] = array();
        }

        $return["data"] = previsy2_tools::arrayPush($_array[$_key]["data"], $_value);

        switch ($_key) {
            case "timestamp":
                $return["stats"]["min"] = min($return["data"]);
                $return["stats"]["max"] = max($return["data"]);
                $return["stats"]["nb_heure"] = count($return["data"]);
                break;
            case "date":
                $return["stats"]["min"] = min($return["data"]);
                $return["stats"]["max"] = max($return["data"]);
                $return["stats"]["nb_heure"] = count($return["data"]);
                break;
            case "pluie":
                $return["stats"]["min"] = round(min($return["data"]), 2);
                $return["stats"]["max"] = round(max($return["data"]), 2);
                $return["stats"]["moyenne"] = previsy2_tools::getMoyenne($return["data"]);
                break;
            case "no_pluie":
                $return["stats"]["min"] = round(min($return["data"]), 2);
                $return["stats"]["max"] = round(max($return["data"]), 2);
                $return["stats"]["moyenne"] = previsy2_tools::getMoyenne($return["data"]);
                break;
            case "neige":
                $return["stats"]["min"] = round(min($return["data"]), 2);
                $return["stats"]["max"] = round(max($return["data"]), 2);
                $return["stats"]["moyenne"] = previsy2_tools::getMoyenne($return["data"]);
                break;
            case "no_neige":
                $return["stats"]["min"] = round(min($return["data"]), 2);
                $return["stats"]["max"] = round(max($return["data"]), 2);
                $return["stats"]["moyenne"] = previsy2_tools::getMoyenne($return["data"]);
                break;
            case "refroidissement-eolien":
                $return["stats"]["min"] = round(min($return["data"]), 2);
                $return["stats"]["max"] = round(max($return["data"]), 2);
                $return["stats"]["moyenne"] = previsy2_tools::getMoyenne($return["data"]);
                break;
            case "temperature":
                $return["stats"]["min"] = round(min($return["data"]), 2);
                $return["stats"]["max"] = round(max($return["data"]), 2);
                $return["stats"]["moyenne"] = previsy2_tools::getMoyenne($return["data"]);
                break;
            case "humidite_pourc":
                $return["stats"]["min"] = round(min($return["data"]), 2);
                $return["stats"]["max"] = round(max($return["data"]), 2);
                $return["stats"]["moyenne"] = previsy2_tools::getMoyenne($return["data"]);
                break;
            case "pression":
                $return["stats"]["min"] = round(min($return["data"]), 2);
                $return["stats"]["max"] = round(max($return["data"]), 2);
                $return["stats"]["moyenne"] = previsy2_tools::getMoyenne($return["data"]);
                break;
            case "vent_10m":
                $return["stats"]["min"] = round(min($return["data"]), 2);
                $return["stats"]["max"] = round(max($return["data"]), 2);
                $return["stats"]["moyenne"] = previsy2_tools::getMoyenne($return["data"]);
                break;
            case "rafale_10m":
                $return["stats"]["min"] = round(min($return["data"]), 2);
                $return["stats"]["max"] = round(max($return["data"]), 2);
                $return["stats"]["moyenne"] = previsy2_tools::getMoyenne($return["data"]);
                break;
            case "dir_vent_deg":
                $return["stats"]["min"] = round(min($return["data"]), 2);
                $return["stats"]["max"] = round(max($return["data"]), 2);
                $return["stats"]["moyenne"] = previsy2_tools::getMoyenne($return["data"]);
                break;
            case "point_rosee":
                $return["data"] = array_unique($return["data"]);
                break;
            default:
                break;
        }

        return $return;
    }

}
