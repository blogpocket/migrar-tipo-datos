<?php
/*
Plugin Name: Migrar Entradas Personalizadas
Description: Añade una opción "Migrar" a los tipos de entradas seleccionados para copiarlas como borradores de entradas normales.
Version: 1.1
Author: A. Cambronero Blogpocket.com
*/

// Evitar el acceso directo al archivo
if ( !defined( 'ABSPATH' ) ) exit;

// Registrar la configuración del plugin
add_action('admin_init', 'migrar_entradas_registrar_ajustes');
function migrar_entradas_registrar_ajustes() {
    register_setting('migrar_entradas_ajustes', 'migrar_entradas_tipos');
}

// Añadir la página de ajustes al menú
add_action('admin_menu', 'migrar_entradas_menu');
function migrar_entradas_menu() {
    add_options_page(
        'Migrar Entradas',
        'Migrar Entradas',
        'manage_options',
        'migrar-entradas',
        'migrar_entradas_pagina_ajustes'
    );
}

// Renderizar la página de ajustes
function migrar_entradas_pagina_ajustes() {
    ?>
    <div class="wrap">
        <h1>Migrar Entradas Personalizadas</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('migrar_entradas_ajustes');
            do_settings_sections('migrar_entradas_ajustes');

            $tipos_seleccionados = get_option('migrar_entradas_tipos', array());
            $tipos_seleccionados = is_array($tipos_seleccionados) ? $tipos_seleccionados : array();

            $args = array(
                'public'   => true,
                '_builtin' => false,
            );
            $tipos_personalizados = get_post_types($args, 'objects');

            if (!empty($tipos_personalizados)) {
                echo '<table class="form-table"><tr valign="top"><th scope="row">Selecciona los tipos de entrada a habilitar:</th><td>';
                foreach ($tipos_personalizados as $tipo) {
                    $checked = in_array($tipo->name, $tipos_seleccionados) ? 'checked' : '';
                    echo '<label><input type="checkbox" name="migrar_entradas_tipos[]" value="' . esc_attr($tipo->name) . '" ' . $checked . '> ' . esc_html($tipo->labels->singular_name) . '</label><br>';
                }
                echo '</td></tr></table>';
            } else {
                echo '<p>No se encontraron tipos de entrada personalizados públicos.</p>';
            }

            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Añadir la opción "Migrar" en la lista de entradas de los tipos seleccionados
add_filter('post_row_actions', 'migrar_entradas_agregar_opcion', 10, 2);
function migrar_entradas_agregar_opcion($acciones, $post) {
    $tipos_seleccionados = get_option('migrar_entradas_tipos', array());
    $tipos_seleccionados = is_array($tipos_seleccionados) ? $tipos_seleccionados : array();

    if (in_array($post->post_type, $tipos_seleccionados)) {
        $url = wp_nonce_url(
            admin_url( 'edit.php?post_type=' . $post->post_type . '&action=migrar&post=' . $post->ID ),
            'migrar_post_' . $post->ID
        );
        $acciones['migrar'] = '<a href="' . esc_url($url) . '">Migrar</a>';
    }
    return $acciones;
}

// Manejar la acción de migración
add_action('admin_init', 'migrar_entradas_manejar_accion');
function migrar_entradas_manejar_accion() {
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

    if (!$post) {
        wp_die('Entrada no válida.');
    }

    $tipos_seleccionados = get_option('migrar_entradas_tipos', array());
    $tipos_seleccionados = is_array($tipos_seleccionados) ? $tipos_seleccionados : array();

    if (!in_array($post->post_type, $tipos_seleccionados)) {
        wp_die('Este tipo de entrada no está habilitado para migración.');
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
