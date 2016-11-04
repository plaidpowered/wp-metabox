<?php

namespace WP_Metabox;

function custom_template($template, $field, $post, $value)
{
    $empty_label = isset($field["attrs"]["empty_label"]) ? $field["attrs"]["empty_label"] : "";

    $posts = get_posts(["post_type"=>$field["attrs"]["post_type"],"nopaging"=>true]);
    $options = sprintf('<option value="">%s</option>', $empty_label);
    foreach($posts as $option) {
        $options .= sprintf('<option value="%d">%s</option>', $option->ID, $option->post_title);
    }

    $template = '';

    return '
        <p class="field">
            <label for="%1$s">%3$s</label>
            <select id="%1$s" name="%2$s" class="widefat">'.$options.'</select>
        </p>';

}
\add_filter("Metabox/render_field/custom/template", __NAMESPACE__.'\custom_template', 9, 4);
