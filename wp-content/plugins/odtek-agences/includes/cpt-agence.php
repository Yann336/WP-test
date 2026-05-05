<?php

/**
 * Custom Post Type : Agence
 * Enregistre le type de contenu "Agence" dans WordPress
 * et ses champs personnalisés (méta-données).
 */

if (! define ('ABSPATH')){
    exit;
}

// quand WordPress initialise ses types de contenu (hook 'init'), appelle la fonction odtek_register_cpt_agence".
add_action('init', 'odtek_register_cpt_agences');

function odtek_register_cpt_agences(){
    // $labels définit tous les textes affichés dans l'interface WordPress
    // pour ce type de contenu (menu, boutons, messages...).
    $labels = array(
        'name' => 'Agences',
        'singular_name' => 'Agence',
        'add_new' => 'Ajouter une agence',
        'add_new_item' => 'Ajouter une nouvelle agence',
        'edit_item' => 'Modifier l agence',
        'new_item' => 'Nouvelle agence',
        'view_item' => 'Voir l agence',
        'not-found' => 'Aucune agences trouvée',
        'menu_name' => 'Agences',
    );

               
    $args = array(
        'labels' => $labels,
        'public' => true,                           // Le CPT est visible (dans l'admin ET sur le site)
        'show_in_menu' => true,                     // Apparaît dans le menu latéral du tableau de bord
        'menu_icon' => 'dashicons-location-alt',    // Icône WordPress (style carte/localisation)
        'supports' => array('title'),               // Champs WordPress natifs qu'on active. On garde juste 'title' (le nom de l'agence).
        'has_archive' => false,                     // Pas de page "liste de toutes les agences" auto
        'rewrite' => false,                         // Pas d'URL publique type /agences/bordeaux
    );

    // register_post_type() est la fonction WordPress qui crée officiellement le CPT.
    // 1er argument: l'identifiant interne unique (slug), sans majuscules ni espaces
    // 2ème argument: la configuration
    register_post_type( 'odtek_agence', $args );
}


// META BOX : les champs personnalisés dans l'interface d'édition

// Une "meta box" est une boîte qu'on ajoute sur la page d'édition d'un post.
// Elle contient nos champs : adresse, téléphone, Place ID Google, etc.

add_action( 'add_meta_boxes', 'odtek_add_meta_box_agence');

function odtek_add_meta_box_agence(){
    // add_meta_box() ajoute une boîte sur la page d'édition.
    // Arguments : id, titre, fonction de rendu, type de post, position, priorité
    add_meta_box(
        'odtek_agence_details', //ID unique de la meta box
        'Details de l agence', //Titre affiché dans la box
        'odtek_render_meta_box_agence', //Fonction qui affiche le contenu HTML de la box
        'odtek-agence', //sur quel type de post l'afficher (CPT)
        'normal', // Position centre
        'high' //Priorité high: afficher en premier
    );
}

// Fonction de rendu : le HTML à l'intérieur de la meta box
// $post est l'objet WordPress du post en cours d'édition.
// Il est automatiquement passé par WordPress 
function odtek_render_meta_box_agence($post){
    // wp_nonce_field() génère un champ de sécurité caché dans le formulaire.
    // Le "nonce" (number used once) vérifie que la soumission vient bien de notre formulaire et pas d'un site malveillant (protection CSRF).
    wp_nonce_field( 'odtek_save_agence', 'odtek_agence_nonce' );

}