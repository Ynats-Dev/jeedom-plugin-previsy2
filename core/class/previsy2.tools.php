<?php

require_once __DIR__ . "/previsy2.require_once.php";

class previsy2_tools extends eqLogic {

    public static function arrayPush($_array = NULL, $_value){
        if($_array == NULL){
            $return = array();
            $return[] = $_value;
            return $return;
        } else {
            array_push($_array, $_value); 
            return $_array;
        }   
    }
    
    public static function getMoyenne($_array){ 
        return round(array_sum($_array)/count($_array),1);
    }
    
    public static function seuilVentBeaufort($_input){
        if ($_input < 1) {
            return 0;
        } elseif ($_input >= 1 AND $_input < 6) {
            return 1;
        } elseif ($_input >= 6 AND $_input < 12) {
            return 2;
        } elseif ($_input >= 12 AND $_input < 20) {
            return 3;
        } elseif ($_input >= 20 AND $_input < 29) {
            return 4;
        } elseif ($_input >= 29 AND $_input < 39) {
            return 5;
        } elseif ($_input >= 39 AND $_input < 50) {
            return 6;
        } elseif ($_input >= 50 AND $_input < 62) {
            return 7;
        } elseif ($_input >= 62 AND $_input < 75) {
            return 8;
        } elseif ($_input >= 75 AND $_input < 89) {
            return 9;
        } elseif ($_input >= 89 AND $_input < 103) {
            return 10;
        } elseif ($_input >= 103 AND $_input < 118) {
            return 11;
        } elseif ($_input > 118) {
            return 12;
        }
    }
    
    public static function mkT($_date, $_lang = "Fr"){
        $return["an"] = $an = substr($_date, 0, 4);
        $return["mois"] = $mois = substr($_date, 4, 2);
        $return["jour"] = $jour = substr($_date, 6, 2);
        $return["heure"] = $heure = substr($_date, 8, 2);
        
        $return["jour_"] = $jour_ = self::getJour(date("N", mktime(0, 0, 0, $mois, $jour, $an)));
        $return["mois_"] = $mois_ = self::getMois($mois);
        
        $return["time"] = self::dansCombienDeTemps($return);
        
        $return["j8"] = $an.$mois.$jour;
 
        return $return;
    }
    
    public static function getJour($_jour){
        switch ($_jour) {
            case 1:
                return "lundi";
                break;
            case 2:
                return "mardi";
                break;
            case 3:
                return "mercredi";
                break;
            case 4:
                return "jeudi";
                break;
            case 5:
                return "vendredi";
                break;
            case 6:
                return "samedi";
                break;
            case 7:
                return "dimanche";
                break;
            default:
                return "ERROR";
                break;
        }
    }
    
    public static function getMois($_jour){
        switch ($_jour) {
            case "01":
                return "janvier";
                break;
            case "02":
                return "février";
                break;
            case "03":
                return "mars";
                break;
            case "04":
                return "avril";
                break;
            case "05":
                return "mai";
                break;
            case "06":
                return "juin";
                break;
            case "07":
                return "juillet";
                break;
            case "08":
                return "aout";
                break;
            case "09":
                return "septembre";
                break;
            case "10":
                return "octobre";
                break;
            case "11":
                return "novembre";
                break;
            case "12":
                return "décembre";
                break;
            default:
                return "ERROR";
                break;
        }
    }
    
    public static function dansCombienDeTemps($_array) {
        return mktime($_array["heure"], 0, 0, $_array["mois"], $_array["jour"], $_array["an"]);
    }

}