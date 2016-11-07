<?php

namespace WP_Metabox;

function custom_template($template, $value, $name, $id, $field)
{
    $empty_label = isset($field["attrs"]["empty_label"]) ? $field["attrs"]["empty_label"] : "";

    $posts = get_posts(["post_type"=>$field["attrs"]["post_type"],"nopaging"=>true]);
    $options = sprintf('<option value="">%s</option>', $empty_label);
    foreach($posts as $option) {
        $options .= sprintf('<option value="%1$d" %3$s>%2$s</option>',
            $option->ID,
            $option->post_title,
            selected($value, $option->ID, false));
    }

    $template = '';

    return '
        <p class="field">
            <label for="'.$id.'">'.$field["label"].'</label>
            <select id="'.$id.'" name="'.$name.'" class="widefat">'.$options.'</select>
        </p>';

}
\add_filter("Metabox/render_field/custom", __NAMESPACE__.'\custom_template', 9, 5);
