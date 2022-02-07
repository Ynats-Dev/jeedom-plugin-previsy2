<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('previsy2');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());

$type_degre = config::byKey('type_degre', 'previsy2', "°C");
?>

<div class="row row-overflow">
    <div class="col-xs-12 eqLogicThumbnailDisplay">
        <legend><i class="fas fa-cog"></i>{{Gestion}}</legend>
        <div class="eqLogicThumbnailContainer">
            <div class="cursor eqLogicAction logoPrimary" data-action="add">
                <i class="fas fa-plus-circle"></i>
                <br>
                <span>{{Ajouter une ville}}</span>
            </div>
            <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
                <i class="fas fa-wrench"></i>
                <br>
                <span>{{Configuration}}</span>
            </div>
<?php if(previsy2_config::getConfigMode() == "debug") { ?>
            <div class="cursor logoSecondary" id="bt_previsy2Debug">
                <i class="fas fa-medkit"></i>
                <br>
                <span>{{Debug}}</span>
            </div>
<?php } ?>
        </div>
        <legend><i class="fas fa-table"></i> {{Mes villes}}</legend>
        <input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
        <div class="eqLogicThumbnailContainer">
            <?php
            foreach ($eqLogics as $eqLogic) {
                $opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
                echo '<div class="eqLogicDisplayCard cursor ' . $opacity . '" data-eqLogic_id="' . $eqLogic->getId() . '">';
                echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
                echo '<br>';
                echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <div class="col-xs-12 eqLogic" style="display: none;">
        <div class="input-group pull-right" style="display:inline-flex">
            <span class="input-group-btn">
                <a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
            </span>
        </div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
            <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
            <li role="presentation"><a href="#alertestab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-exclamation-triangle"></i> {{Les alertes}}</a></li>
            <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
        </ul>
        <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">



            <!-- 
            L'équipement
            -->

            <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                <br/>
                <form class="form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Nom du lieu à sonder}}</label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                                <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom du lieu à sonder}}"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                            <div class="col-sm-3">
                                <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                                    <option value="">{{Aucun}}</option>
                                    <?php
                                    foreach (jeeObject::all() as $object) {
                                        echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{Catégorie}}</label>
                            <div class="col-sm-9">
                                <?php
                                foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                    echo '<label class="checkbox-inline">';
                                    echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                                    echo '</label>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-9">
                                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                                <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Nom de la ville}}</label>
                            <div class="col-sm-3">
                                <input type="text" onkeyup="this.value = previsy2Normalizer(this.value);" onfocus="this.value = previsy2Normalizer(this.value);" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="ville" placeholder="ville" />
                            </div>
                            <span style="float:left">
                                <span id="previsy2LinkVille"></span>
                            </span>
                            <div><a href="" target="_blank"></a></div>
                        </div>
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Coordonnées du point (prend le dessus sur la ville)}}</label>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="latitude" placeholder="latitude" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" />
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="longitude" placeholder="longitude" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" />
                            </div>
                            <div><a href="" target="_blank"></a></div>
                        </div>

                    </fieldset>
                </form>
            </div>

            <!-- 
            Les alertes
            -->

            <div role="tabpanel" class="tab-pane" id="alertestab">
                
                <div class="form-group" style="margin: 15px;">
                    <div class="alert alert-info">Les paramètres se cumulent et vous permettent de créer des filtres sur mesure (ex. Générer une alerte quand il pleut et que le vent souffle du Sud ou Sud-Est à au moins 30 km/h.</div>
                </div>
                
                <form class="form-horizontal">
                    
                    <fieldset>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <div class="col-sm-7">
                                <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">Message à afficher au moment de l'alerte.</div>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Message}}</label>
                            <div class="col-sm-3">
                                <textarea type="text" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="message_alerte"></textarea>
                            </div>
                        </div>

                    </fieldset>
                    
                    <fieldset>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <div class="col-sm-7">
                                <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">Alertes liées à la pluie</div>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte s'il pleut}}</label>
                            <div class="col-sm-3">
                                <input type="checkbox" class="eqLogicAttr form-control previsy2_pluie" data-l1key="configuration" data-l2key="pluie" onclick="on_pluie()">
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte s'il ne pleut pas}}
                            <sup><i class="fa fa-question-circle tooltips" title="{{A ne renseigner que si vous souhaitez recevoir une alerte s'il ne pleut pas (ex. Revevoir une alerte pour faire de la voile quand il y a du vent et qu'il ne pleut pas)}}"></i></sup>
                            </label>
                            <div class="col-sm-3">
                                <input type="checkbox" class="eqLogicAttr form-control previsy2_no_pluie" data-l1key="configuration" data-l2key="no_pluie" onclick="on_no_pluie()">
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <div class="col-sm-7">
                                <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">Alertes liées à la neige</div>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte s'il neige}}</label>
                            <div class="col-sm-3">
                                <input type="checkbox" class="eqLogicAttr form-control previsy2_neige" data-l1key="configuration" data-l2key="neige" onclick="on_neige()">
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte s'il ne neige pas}}
                            <sup><i class="fa fa-question-circle tooltips" title="{{A ne renseigner que si vous souhaitez recevoir une alerte s'il ne neige pas (ex. Revevoir une alerte pour allez faire du ski quand il ne neige pas.)}}"></i></sup>
                            </label>
                            <div class="col-sm-3">
                                <input type="checkbox" class="eqLogicAttr form-control previsy2_no_neige" data-l1key="configuration" data-l2key="no_neige" onclick="on_no_neige()">
                            </div>
                        </div>

                    </fieldset>
                    
                    <fieldset>
                        
                        <div class="form-group">
                            <div class="col-sm-7">
                                <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">Alertes liées au vent (conditions cumulables)</div>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte liée au seuil du vent (échelle de Beaufort)}}</label>
                            <div class="col-sm-3">
                                <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="seuilVent">
                                    <option value="">{{Pas d'alertes liées à la forte du vent}}</option>
                                    <option value="2">{{[Force 2] Alerte à partir de 6 km/h (Légère brise)}}</option>
                                    <option value="3">{{[Force 3] Alerte à partir de 12 km/h (Petite brise)}}</option>
                                    <option value="4">{{[Force 4] Alerte à partir de 20 km/h (Jolie brise)}}</option>
                                    <option value="5">{{[Force 5] Alerte à partir de 29 km/h (Bonne brise)}}</option>
                                    <option value="6">{{[Force 6] Alerte à partir de 39 km/h (Vent frais)}}</option>
                                    <option value="7">{{[Force 7] Alerte à partir de 50 km/h (Grand frais)}}</option>
                                    <option value="8">{{[Force 8] Alerte à partir de 62 km/h (Coup de vent)}}</option>
                                    <option value="9">{{[Force 9] Alerte à partir de 75 km/h (Fort coup de vent)}}</option>
                                    <option value="10">{{[Force 10] Alerte à partir de 89 km/h (Tempête)}}</option>
                                    <option value="11">{{[Force 11] Alerte à partir de 103 km/h (Violente tempête)}}</option>
                                    <option value="12">{{[Force 12] Alerte à partir de 118 km/h (Ouragan)}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte liée à la direction du vent (1)}}</label>
                            <div class="col-sm-3">
                                <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="directionVent">
                                    <option value="">{{Pas d'alertes liées à la direction du vent}}</option>
                                    <option value="S">{{[N] Vent du Nord}}</option>
                                    <option value="NE">{{[NE] Vent du Nord Est}}</option>
                                    <option value="E">{{[E] Vent de l'Est}}</option>
                                    <option value="SE">{{[SE] Vent du Sud Est}}</option>
                                    <option value="S">{{[S] Vent du Sud}}</option>
                                    <option value="SO">{{[SO] Vent du Sud Ouest}}</option>
                                    <option value="O">{{[O] Vent de l'Ouest}}</option>
                                    <option value="NO">{{[NO] Vent du Nord Ouest}}</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte liée à la direction du vent (2)}}</label>
                            <div class="col-sm-3">
                                <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="directionVent2">
                                    <option value="">{{Pas d'alertes liées à la direction du vent}}</option>
                                    <option value="S">{{[N] Vent du Nord}}</option>
                                    <option value="NE">{{[NE] Vent du Nord Est}}</option>
                                    <option value="E">{{[E] Vent de l'Est}}</option>
                                    <option value="SE">{{[SE] Vent du Sud Est}}</option>
                                    <option value="S">{{[S] Vent du Sud}}</option>
                                    <option value="SO">{{[SO] Vent du Sud Ouest}}</option>
                                    <option value="O">{{[O] Vent de l'Ouest}}</option>
                                    <option value="NO">{{[NO] Vent du Nord Ouest}}</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte liée à la direction du vent (3)}}</label>
                            <div class="col-sm-3">
                                <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="directionVent3">
                                    <option value="">{{Pas d'alertes liées à la direction du vent}}</option>
                                    <option value="S">{{[N] Vent du Nord}}</option>
                                    <option value="NE">{{[NE] Vent du Nord Est}}</option>
                                    <option value="E">{{[E] Vent de l'Est}}</option>
                                    <option value="SE">{{[SE] Vent du Sud Est}}</option>
                                    <option value="S">{{[S] Vent du Sud}}</option>
                                    <option value="SO">{{[SO] Vent du Sud Ouest}}</option>
                                    <option value="O">{{[O] Vent de l'Ouest}}</option>
                                    <option value="NO">{{[NO] Vent du Nord Ouest}}</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte liée à la direction du vent (4)}}</label>
                            <div class="col-sm-3">
                                <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="directionVent4">
                                    <option value="">{{Pas d'alertes liées à la direction du vent}}</option>
                                    <option value="S">{{[N] Vent du Nord}}</option>
                                    <option value="NE">{{[NE] Vent du Nord Est}}</option>
                                    <option value="E">{{[E] Vent de l'Est}}</option>
                                    <option value="SE">{{[SE] Vent du Sud Est}}</option>
                                    <option value="S">{{[S] Vent du Sud}}</option>
                                    <option value="SO">{{[SO] Vent du Sud Ouest}}</option>
                                    <option value="O">{{[O] Vent de l'Ouest}}</option>
                                    <option value="NO">{{[NO] Vent du Nord Ouest}}</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte liée à la direction du vent (5)}}</label>
                            <div class="col-sm-3">
                                <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="directionVent5">
                                    <option value="">{{Pas d'alertes liées à la direction du vent}}</option>
                                    <option value="S">{{[N] Vent du Nord}}</option>
                                    <option value="NE">{{[NE] Vent du Nord Est}}</option>
                                    <option value="E">{{[E] Vent de l'Est}}</option>
                                    <option value="SE">{{[SE] Vent du Sud Est}}</option>
                                    <option value="S">{{[S] Vent du Sud}}</option>
                                    <option value="SO">{{[SO] Vent du Sud Ouest}}</option>
                                    <option value="O">{{[O] Vent de l'Ouest}}</option>
                                    <option value="NO">{{[NO] Vent du Nord Ouest}}</option>
                                </select>
                            </div>
                        </div>
                        
                    </fieldset>
                    
                    <fieldset>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <div class="col-sm-7">
                                <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">Alertes liées à la température</div>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;display: none;" id="previsy2_verif_temp">
                            <div class="col-sm-6" style="color: red;" id="previsy2_verif_temp_txt"></div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte si la température est en dessous de ...}}</label>
                            <div class="col-sm-3">
                                <input onchange="verif_temp()" type="text" class="eqLogicAttr form-control" style="width:100px;" data-l1key="configuration" data-l2key="temperatureMin" placeholder="10" /> <?php echo $type_degre ?> 
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte si la température est au dessus de ...}}</label>
                            <div class="col-sm-3">
                                <input onchange="verif_temp()" type="text" class="eqLogicAttr form-control" style="width:100px;" data-l1key="configuration" data-l2key="temperatureMax" placeholder="30" /> <?php echo $type_degre ?> 
                            </div>
                        </div>

                    </fieldset>
                    
                    <fieldset>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <div class="col-sm-7">
                                <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">Alertes liées au refroidissement éolien (ou température ressentie)</div>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;display: none;" id="previsy2_verif_temp_refroid">
                            <div class="col-sm-6" style="color: red;" id="previsy2_verif_temp_refroid_txt"></div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte si la température ressentie est en dessous de ...}}</label>
                            <div class="col-sm-3">
                                <input onchange="verif_temp_refroid()" type="text" class="eqLogicAttr form-control" style="width:100px;" data-l1key="configuration" data-l2key="refroidissementMin" placeholder="10" /> <?php echo $type_degre ?>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte si la température ressentie est au dessus de ...}}</label>
                            <div class="col-sm-3">
                                <input onchange="verif_temp_refroid()" type="text" class="eqLogicAttr form-control" style="width:100px;" data-l1key="configuration" data-l2key="refroidissementMax" placeholder="30" /> <?php echo $type_degre ?> 
                            </div>
                        </div>

                    </fieldset>
                    
                    <fieldset>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <div class="col-sm-7">
                                <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">Alertes liées au pourcentage d'humidité</div>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;display: none;" id="previsy2_verif_humide">
                            <div class="col-sm-6" style="color: red;" id="previsy2_verif_humide_txt"></div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte si en dessous de ...}}</label>
                            <div class="col-sm-3">
                                <input onchange="verif_humide()" type="text" class="eqLogicAttr form-control" style="width:100px;" data-l1key="configuration" data-l2key="humiditeMin" placeholder="60" /> % d'humidité 
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte si au dessus de ...}}</label>
                            <div class="col-sm-3">
                                <input onchange="verif_humide()" type="text" class="eqLogicAttr form-control" style="width:100px;" data-l1key="configuration" data-l2key="humiditeMax" placeholder="80" /> % d'humidité 
                            </div>
                        </div>

                    </fieldset>
                    
                    <fieldset>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <div class="col-sm-7">
                                <div style="background-color: #039be5; padding: 2px 5px; color: white; margin: 10px 0; font-weight: bold;">Alertes liées à la pression atmosphérique</div>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;display: none;" id="previsy2_verif_pression">
                            <div class="col-sm-6" style="color: red;" id="previsy2_verif_pression_txt"></div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte si en dessous de hPa de Pression}}</label>
                            <div class="col-sm-3">
                                <input onchange="verif_pression()" type="text" class="eqLogicAttr form-control" style="width:100px;" data-l1key="configuration" data-l2key="pressionMin" placeholder="1000" /> hPa 
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label class="col-sm-3 control-label">{{Alerte si au dessus de hPa de Pression}}</label>
                            <div class="col-sm-3">
                                <input onchange="verif_pression()" type="text" class="eqLogicAttr form-control" style="width:100px;" data-l1key="configuration" data-l2key="pressionMax" placeholder="1050" /> hPa 
                            </div>
                        </div>

                    </fieldset>
                    <br />
                </form>
            </div>

            <!-- 
            Les commandes 
            -->

            <div role="tabpanel" class="tab-pane" id="commandtab">
                <table id="table_cmd" class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>{{Nom}}</th><th>{{Historique}}</th><th>{{Action}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<?php include_file('desktop', 'previsy2', 'js', 'previsy2'); ?>
<?php include_file('desktop', 'previsy2_equ', 'js', 'previsy2'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>