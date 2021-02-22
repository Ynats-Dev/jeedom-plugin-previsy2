<?php

require_once __DIR__ . "/previsy2.require_once.php";

class previsy2_json extends eqLogic {
    
    public static $_jsonFolder = __DIR__ . "/../../data/json/";
    public static $_jsonLocalize = __DIR__ . "/../../data/json/localisation/";
    public static $_jsonDatas = __DIR__ . "/../../data/json/datas/";
    
    public static function getJson($_file) {
        
        try {
            $return = @json_decode(@file_get_contents($_file),true);
        } catch (Exception $e) {
            $rerurn = NULL;
        }
        
        return $return;
    }
    
    public static function recordInJson($_file, $_data) {
        //log::add('scan_ip', 'debug', 'recordInJson :.  Lancement');
        
        self::prepareJsonFolder();
        self::createJsonFile($_file, $_data);

        //log::add('scan_ip', 'debug', 'recordInJson :. Enregistrement du Json : mapping.json');
    }
    
    public static function prepareJsonFolder(){ 
        if (!is_dir(self::$_jsonFolder)) {
            mkdir(self::$_jsonFolder, 0777);
        }
        
        if (!is_dir(self::$_jsonLocalize)) {
            mkdir(self::$_jsonLocalize, 0777);
        }
        
        if (!is_dir(self::$_jsonDatas)) {
            mkdir(self::$_jsonDatas, 0777);
        }
    }
    
    public static function createJsonFile($_file, $_data){
        //log::add('scan_ip', 'debug', 'createJsonFile :. Lancement');
        
        $fichier = fopen($_file.'.temp', 'w');
        fputs($fichier, json_encode($_data));
        fclose($fichier);

        @unlink($_file.'.json');
        rename($_file.'.temp', $_file.'.json');
        chmod($_file.'.json', 0777);
    }
    
}
