
function previsy2_mode_plugin() { 
    var mode = $( "#previsy2_mode" ).val();
    if(mode == "normal"){
        //$('#show_commandes_plus').hide();
    } else {
        //$('#show_commandes_plus').show();
    }
}

setTimeout(function(){
    previsy2_mode_plugin();
}, 150);
