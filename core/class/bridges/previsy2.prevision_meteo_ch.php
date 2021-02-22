<?php

class previsy2_prevision_meteo_ch {
    
    public static $_urlApi = "https://www.prevision-meteo.ch/services/json/"; // Url du Json de prevision-meteo.ch
    public static $_nbJour = 5;
    
    public static function checkApi($_api){
        if(get_headers($_api)){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public static function readApiToArray($_array) {
                
        if(!empty($_array["latitude"]) AND !empty($_array["longitude"])){
            $searchBy = "lat=" . $_array["latitude"] . "lng=" . $_array["longitude"];
        } 
        elseif(!empty($_array["ville"])){
            $searchBy = $_array["ville"];
        } else {
            return FALSE;
        }
        
        $searchApi = self::$_urlApi . $searchBy;
        
        try {
            $start = microtime(true);
            $data = @json_decode(@file_get_contents($searchApi),true);
        } catch (Exception $e) {
            $return["url"] = $searchApi;
            $return["data"] = NULL;
        } finally {
            if(empty($data["errors"])){
                $return["url"] = $searchApi;
                $return["data"] = $data;
            } else {
                $return["url"] = $searchApi;
                $return["data"] = NULL;
            }
            
        }
        
        $return["microtime"] = microtime(true)-$start;
        return $return;
    }
    
    public static function mapping($_array) {
        
        if($_array["data"] == NULL){
            return "ERROR : " . $_array["url"];
        } else {
            $return["execution"] = array(
                "timestamp" => time(),
                "date" => date("d/m/Y H:i:s"),
                "url" => $_array["url"],
                "microtime" => $_array["microtime"],
            );

            $return["infos_localisation"] = array(
                "date" => $_array["data"]["current_condition"]["date"],
                "heure" => $_array["data"]["current_condition"]["hour"],
                "ville" => $_array["data"]["city_info"]["name"],
                "pays" => $_array["data"]["city_info"]["country"],
                "latitude" => $_array["data"]["city_info"]["latitude"],
                "longitude" => $_array["data"]["city_info"]["longitude"],
                "levee_soleil" => $_array["data"]["city_info"]["sunrise"],
                "couche_soleil" => $_array["data"]["city_info"]["sunset"],
            );

            for ($i = 0; $i < (self::$_nbJour); $i++) {
                foreach ($_array["data"]["fcst_day_".$i] as $key => $value) {
                    
                    $return["jours"][$i]["date"] = $date = $_array["data"]["fcst_day_".$i]["date"];
                    
                    if($key == "hourly_data"){
                        for ($z = 0; $z <= 23; $z++) {
                            $return["jours"][$i]["heures"][$z."H"] = array(
                                "timestamp" => self::dateToTimestamp($date, $z),
                                "date" => self::getDate($date, $z),
                                "id_condition" => $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["CONDITION_KEY"],
                                "temperature" => $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["TMP2m"],
                                "point_rosee" => $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["DPT2m"],
                                "humidite_pourc" => $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["RH2m"],
                                "pression" => $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["PRMSL"],
                                "vent_10m" => $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["WNDSPD10m"],
                                "rafale_10m" => $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["WNDGUST10m"],
                                "dir_vent_deg" => $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["WNDDIR10m"],
                                "dir_vent_cadran" => $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["WNDDIRCARD10"],
                                "moy_altitude_nuages" => $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["MCDC"],
                                "isotherme_zero" => $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["HGT0C"],
                                "k_index" => $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["KINDEX"],
                            );
                            
                            if($_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["ISSNOW"] == 0 AND $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["APCPsfc"] > 0){
                                $return["jours"][$i]["heures"][$z."H"]["pluie"] = $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["APCPsfc"];
                                $return["jours"][$i]["heures"][$z."H"]["neige"] = 0;
                            } elseif($_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["ISSNOW"] == 1 AND $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["APCPsfc"] > 0) {
                                $return["jours"][$i]["heures"][$z."H"]["neige"] = $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["APCPsfc"];
                                $return["jours"][$i]["heures"][$z."H"]["pluie"] = 0;
                            } else {
                                $return["jours"][$i]["heures"][$z."H"]["pluie"] = 0;
                                $return["jours"][$i]["heures"][$z."H"]["neige"] = 0;
                            }
                            
                            if($_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["WNDCHILL2m"] == NULL){
                                $return["jours"][$i]["heures"][$z."H"]["refroidissement-eolien"] = 0;
                            } else {
                                $return["jours"][$i]["heures"][$z."H"]["refroidissement-eolien"] = $_array["data"]["fcst_day_".$i]["hourly_data"][$z."H00"]["WNDCHILL2m"];
                            }
                        }
                    }
                }
            }

            return $return;
        }
        
    }
    
    public static function dateToTimestamp($_date, $_heure){
        $date = explode(".", $_date);

        return mktime($_heure, 0, 0, intval($date[1]), intval($date[0]), intval($date[2]));
    }
    
    public static function getDate($_date, $_heure){
        $date = explode(".", $_date);
        
        if($_heure < 10){ $heure = "0".$_heure; }
        else { $heure = $_heure; }

        return intval($date[2].$date[1].$date[0].$heure);
    }
    
}
