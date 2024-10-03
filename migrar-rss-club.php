<?php
/*
Plugin Name: Migrar RSS Club Posts
Description: Añade una opción "Migrar" a las entradas de RSS Club para copiarlas como borradores de entradas normales.
Version: 1.0
Author: Tu Nombre
*/

// Evitar el acceso directo al archivo
if ( !defined( 'ABSPATH' ) ) exit;

// Añadir la opción "Migrar" en la lista de entradas, por ejemplo, del tipo 'rss-club'
// Cambiar el slug del tipo de datos personalizado 'rss-club' es un ejemplo
add_filter('post_row_actions', 'agregar_opcion_migrar', 10, 2);
function agregar_opcion_migrar($acciones, $post) {
    if ($post->post_type == 'rss-club') {
        $url = wp_nonce_url(
            admin_url( 'edit.php?post_type=rss-club&action=migrar&post=' . $post->ID ),
            'migrar_post_' . $post->ID
        );
        $acciones['migrar'] = '<a href="' . esc_url($url) . '">Migrar</a>';
    }
    return $acciones;
}

// Manejar la acción de migración
add_action('admin_init', 'manejar_accion_migrar');
function manejar_accion_migrar() {
    if (!isset($_GET['action']) || $_GET['action'] != 'migrar') {
        return;
    }

    if (!isset($_GET['post']) || !isset($_GET['_wpnonce'])) {
        return;
    }

    $post_id = intval($_GET['post']);
    if (!wp_verify_nonce($_GET['_wpnonce'], 'migrar_post_' . $post_id)) {
        wp_die('Fallo en la verificación de seguridad');
    }

    // Verificar permisos del usuario
    if (!current_user_can('edit_post', $post_id)) {
        wp_die('No tienes permiso para editar esta entrada.');
    }

    // Obtener la entrada original
    $post = get_post($post_id);

    if (!$post || $post->post_type != 'rss-club') {
        wp_die('Entrada no válida.');
    }

    // Preparar los datos para la nueva entrada
    $nueva_entrada = array(
        'post_title'    => $post->post_title,
        'post_content'  => $post->post_content,
        'post_status'   => 'draft',
        'post_author'   => $post->post_author,
        'post_type'     => 'post', // Entrada normal del blog
        'post_excerpt'  => $post->post_excerpt,
        'post_date'     => $post->post_date,
        'post_date_gmt' => $post->post_date_gmt,
        'post_category' => wp_get_post_categories($post_id),
        'tags_input'    => wp_get_post_tags($post_id, array('fields' => 'names')),
    );

    // Insertar la nueva entrada
    $nuevo_post_id = wp_insert_post($nueva_entrada);

    if (is_wp_error($nuevo_post_id)) {
        wp_die('Error al crear la nueva entrada.');
    }

    // Copiar meta datos
    $meta_datos = get_post_meta($post_id);
    if (!empty($meta_datos)) {
        foreach ($meta_datos as $meta_key => $meta_values) {
            foreach ($meta_values as $meta_value) {
                add_post_meta($nuevo_post_id, $meta_key, maybe_unserialize($meta_value));
            }
        }
    }

    // Copiar imagen destacada
    $imagen_destacada_id = get_post_thumbnail_id($post_id);
    if ($imagen_destacada_id) {
        set_post_thumbnail($nuevo_post_id, $imagen_destacada_id);
    }

    // Redireccionar a la pantalla de edición de la nueva entrada
    wp_redirect(admin_url('post.php?action=edit&post=' . $nuevo_post_id));
    exit;
}
