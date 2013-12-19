/**
 * jQuery bootstrap
 * 
 * @version     1.0
 * @copyright   2001-2012 Universite catholique de Louvain (UCL)
 * @author      Frederic Minne <frederic.minne@uclouvain.be>
 * @license     http://www.fsf.org/licensing/licenses/agpl-3.0.html
 *              GNU AFFERO GENERAL PUBLIC LICENSE version 3
 */
$(function(){
    
    // Tabs
    
    $("ul.tabs").tabs("div.panes > div");
    
    // Links
    
    $('.overlayTrigger').overlay();
    
    $('a[rel=external]').attr('target','_blank');
    
    // Date formating and localization
    
    $('.dateLong').each(function(){
        var _date = Date.fromDatetime($(this).text());
        $(this).text(ucfirst(_date.format("EEEEE d MMMM y à HH'h'mm", 'fr')));
    });
    
    $('.dateShort').each(function(){
        var _date = Date.fromDatetime($(this).text());
        $(this).text(ucfirst(_date.format('EEEEE d MMMM y', 'fr')));
    });

    if ( $('#login_login') ) {
        $('#login_login').focus();
    }
    
    // Client side form validation
    
    if ( $('#creationCompte') ) {
        $.validator.addMethod(
            'passwordMustMatch',
            function() {
                return ($('#motdepasse').val() == $('#motdepasse_confirme').val());
            },
            "Doit être identique à votre mot de passe"
        );
        
        $.validator.addMethod(
            'notChecked',
            function() {
                return !$('#conf_humain').is(':checked');
            }
        );
        
        $.validator.addMethod(
            'typeInstitutionSelected',
            function() {
                return ( $('#type_institution').val().trim() != '' );
            }
        );
        
        $('#creationCompte').validate({
            rules: {
                utilisateur: {
                    required: true
                },
                motdepasse: {
                    required: true,
                    minlength: 8
                },
                motdepasse_confirme: {
                    required: true,
                    passwordMustMatch: true
                },
                mail_contact: {
                    required: true,
                    email: true
                },
                nom: {
                    required: true
                },
                prenom: {
                    required: true
                },
                institution: {
                    required: true
                },
                type_institution: {
                    typeInstitutionSelected: true
                },
                faculte: {
                    required: true
                },
                mail_travail: {
                    email: true
                },
                conf_humain: {
                    notChecked: true
                }
            },
            messages: {
                nom: {
                    required: "Vous devez introduire votre nom"
                },
                prenom: {
                    required: "Vous devez introduire votre prénom"
                },
                utilisateur: {
                    required: "Vous devez choisir un identifiant"
                },
                motdepasse: {
                    required: "Vous devez choisir un mot de passe",
                    minlength: "Votre mot de passe doit faire au moins 8 caractères"
                },
                motdepasse_confirme: {
                    required: "Vous devez confirmer votre mot de passe",
                    passwordMustMatch: "Différent de votre mot de passe"
                },
                institution: {
                    required: "Vous devez indiquer une institution"
                },
                type_institution: {
                    typeInstitutionSelected: "Vous devez indiquer un type d'institution"
                },
                mail_contact: {
                    required: "Vous devez introduire une adresse email de contact",
                    email: "Format de l'adresse non valide"
                },
                mail_travail: {
                    email: "Format de l'adresse non valide"
                },
                conf_humain : {
                    notChecked: "Vous devez décocher cette case"
                },
                faculte : {
                    required: "Vous devez spécifier votre faculté ou section"
                }
            }
        });
    }
});

