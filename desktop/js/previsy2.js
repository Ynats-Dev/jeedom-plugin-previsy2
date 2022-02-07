
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */


$("#table_cmd").sortable({axis: "y", cursor: "move", items: ".cmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

/*
 * Fonction pour l'ajout de commande, appellé automatiquement par plugin.template
 */

// Sur la partie Debug
$('#bt_previsy2Debug').off('click').on('click', function () {
    $('#md_modal').dialog({title: "{{Debug}}"});
    $('#md_modal').load('index.php?v=d&plugin=previsy2&modal=debug').dialog('open');
});

function on_neige(){
    $('input[data-l2key=no_neige]').prop('checked', false);
}

function on_no_neige(){
    $('input[data-l2key=neige]').prop('checked', false);
}

function on_pluie(){
    $('input[data-l2key=no_pluie]').prop('checked', false);
}

function on_no_pluie(){
    $('input[data-l2key=pluie]').prop('checked', false);
}

function verif_temp(){
    if($('input[data-l2key=temperatureMin]').val() > 0 & $('input[data-l2key=temperatureMax]').val() > 0 & $('input[data-l2key=temperatureMin]').val() > $('input[data-l2key=temperatureMax]').val()){
        $('#previsy2_verif_temp').show();
        $('#previsy2_verif_temp_txt').text("Cette condition n'est pas possible. L'alerte ne peut pas se déclancher si la température est au dessous de " + $('input[data-l2key=temperatureMin]').val() + "° ET au dessus de " + $('input[data-l2key=temperatureMax]').val() + "°.");
    } else {
        $('#previsy2_verif_temp').hide();
    }
}

function verif_temp_refroid(){
    if($('input[data-l2key=refroidissementMin]').val() > 0 & $('input[data-l2key=refroidissementMax]').val() > 0 & $('input[data-l2key=refroidissementMin]').val() > $('input[data-l2key=refroidissementMax]').val()){
        $('#previsy2_verif_temp_refroid').show();
        $('#previsy2_verif_temp_refroid_txt').text("Cette condition n'est pas possible. L'alerte ne peut pas se déclancher si la température ressentie est au dessous de " + $('input[data-l2key=refroidissementMin]').val() + "° ET au dessus de " + $('input[data-l2key=refroidissementMax]').val() + "°.");
    }
    else {
        $('#previsy2_verif_temp_refroid').hide();
    }
}

function verif_humide(){
    if($('input[data-l2key=humiditeMin]').val() > 0 & $('input[data-l2key=humiditeMax]').val() > 0 & $('input[data-l2key=humiditeMin]').val() > $('input[data-l2key=humiditeMax]').val()){
        $('#previsy2_verif_humide').show();
        $('#previsy2_verif_humide_txt').text("Cette condition n'est pas possible. L'alerte ne peut pas se déclancher si l'humidité est au dessous de " + $('input[data-l2key=humiditeMin]').val() + "% ET au dessus de " + $('input[data-l2key=humiditeMax]').val() + "%.");
    }
    else {
        $('#previsy2_verif_humide').hide();
    }
}

function verif_pression(){
    if($('input[data-l2key=pressionMin]').val() > 0 & $('input[data-l2key=pressionMax]').val() > 0 & $('input[data-l2key=pressionMin]').val() > $('input[data-l2key=pressionMax]').val()){
        $('#previsy2_verif_pression').show();
        $('#previsy2_verif_pression_txt').text("Cette condition n'est pas possible. L'alerte ne peut pas se déclancher si la pression est au dessous de " + $('input[data-l2key=pressionMin]').val() + "hPa ET au dessus de " + $('input[data-l2key=pressionMax]').val() + "hPa.");
    }
    else {
        $('#previsy2_verif_pression').hide();
    }
}