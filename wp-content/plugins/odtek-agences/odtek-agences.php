<?php
/**
 * Plugin Name: ODtek Agences
 * Plugin URI:  https://odtek.fr
 * Description: Affiche dynamiquement les informations des agences ODtek / Docteur Ordinateur, synchronisées avec Google Business Profile via l'API Google Places.
 * Version:     1.0.0
 * Author:      Yann
 * License:     GPL-2.0-or-later
 */

// SÉCURITÉ : empêcher l'accès direct au fichier
// Si quelqu'un tape l'URL de ce fichier directement dans son navigateur,
// la constante ABSPATH n'est pas définie → on coupe immédiatement.
// C'est une règle de sécurité de base dans TOUT plugin WordPress.

if ( ! defined( 'ABSPATH' ) ) {
    exit;
};


// CONSTANTES DU PLUGIN
// define() crée une "constante" 
// __FILE__ = chemin absolu vers CE fichier sur le serveur.
// plugin_dir_path() en extrait le dossier parent.
define( 'ODTEK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
 
// plugin_dir_url() fait pareil mais en URL HTTP (pour charger CSS/JS côté navigateur).
define( 'ODTEK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
 
// Numéro de version stocké en constante → utile pour forcer le rechargement du cache CSS/JS.
define( 'ODTEK_VERSION', '1.0.0' );
 
// Clé utilisée pour stocker la clé API Google dans les options WordPress (wp_options en BDD).
define( 'ODTEK_OPTION_API_KEY', 'odtek_google_api_key' );



// ACTIVATION / DÉSACTIVATION DU PLUGIN
// register_activation_hook : WordPress appelle cette fonction quand on clique
// "Activer" dans la liste des plugins. C'est ici qu'on initialise ce qui est nécessaire.
register_activation_hook( __FILE__, 'odtek_activation' );
 
function odtek_activation() {
    // flush_rewrite_rules() est indispensable après l'enregistrement d'un Custom Post Type (CPT)
    // → elle dit à WordPress de recalculer toutes ses URLs "propres" (permaliens).
    flush_rewrite_rules();
}
 
// register_deactivation_hook : appelé quand on clique "Désactiver".
// Bonne pratique : supprimer les tâches planifiées pour ne pas laisser de résidus.
register_deactivation_hook( __FILE__, 'odtek_deactivation' );
 
function odtek_deactivation() {
    // wp_clear_scheduled_hook supprime la tâche cron qu'on programmera plus tard.
    wp_clear_scheduled_hook( 'odtek_cron_sync' );
    flush_rewrite_rules();
}