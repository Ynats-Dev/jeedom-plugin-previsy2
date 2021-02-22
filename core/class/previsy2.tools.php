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
    
}