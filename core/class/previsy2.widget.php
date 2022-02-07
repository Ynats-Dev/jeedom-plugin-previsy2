<?php

require_once __DIR__ . "/previsy2.require_once.php";

class previsy2_widget extends eqLogic {

    public static function phraseQuand($_time){
        
        $now = mktime(date("h"), 0, 0, date("m"), date("d"), date("Y"));
        $start = mktime(date("h",$_time), 0, 0, date("m",$_time), date("d",$_time), date("Y",$_time));
        
        $dansHeure = $now - $start;
        $timeStart_plus_1 = date('Ymd', strtotime(date('Y-m-d'). ' + 1 days'));
        $timeStart_plus_2 = date('Ymd', strtotime(date('Y-m-d'). ' + 2 days'));
        
        if($start < time()){
            return "en ce moment, ";
        }
        elseif(date("Ymd") == date("Ymd", $start)){
            $return = "dans ".$dansHeure." heure";
            if($dansHeure > 1){ $return .= "s "; }
            $return .= ", ";
            return $return;
        }
        elseif(date("Ymd", $start) == $timeStart_plus_1){
            return "Demain, ";
        }
        elseif(date("Ymd", $start) == $timeStart_plus_2){
            return "après-demain, ";
        } else{
            return "";
        }
        
        $tmp = ($_time - mktime(date("h"), 0, 0, date("m"), date("d"), date("Y")))/3600;
        

        switch ($tmp) {
            case $value < 24:
                return $tmp."H";
                break;
            case $value < 24:
                return $tmp."H";
                break;
            default:
                break;
        }
    }
    
    public static function phraseDates($_array){
        $tmp_debut = previsy2_tools::mkT($_array["alerte_debut"]);
        $tmp_fin = previsy2_tools::mkT($_array["alerte_fin"]);
        
        $dans_heures = self::phraseQuand($tmp_debut["time"]);
        
        if($tmp_debut["j8"] == $tmp_fin["j8"]){
            $return =  $dans_heures;
            //if($dans_heures > 1) { $return .= "s"; }
            $return .= "ce " . $tmp_debut["jour_"] . " " . $tmp_debut["jour"] . " " . $tmp_debut["mois_"] . " de " . $tmp_debut["heure"] . "H à " . $tmp_fin["heure"] . "H (durant " . $_array["alerte_duree"] . " heure";
            if($_array["alerte_duree"] > 1) { $return .= "s"; }
            $return .= ").";
        } else {
            $return =  $dans_heures;
            $return .= "ce " . $tmp_debut["jour_"] . " " . $tmp_debut["jour"] . " " . $tmp_debut["mois_"] . " " . $tmp_debut["heure"] . "H à " . $tmp_fin["jour_"] . " " . $tmp_fin["jour"] . " " . $tmp_fin["mois_"] . " " . $tmp_fin["heure"] . "H  (durant " . $_array["alerte_duree"] . " heure";
            if($_array["alerte_duree"] > 1) { $return .= "s"; }
            $return .= ").";
        } 
        
        return ucfirst($return);
    }

}