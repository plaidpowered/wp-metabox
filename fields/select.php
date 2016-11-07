<?php

namespace WP_Metabox;

function select_template($template, $value, $name, $id, $field)
{
    $empty_label = isset($field["attrs"]["empty_label"]) ? $field["attrs"]["empty_label"] : "";

    $options = isset($field["attrs"]["options"]) ? $field["attrs"]["options"] : array();
    $template = sprintf('<option value="">%s</option>', $empty_label);

    foreach($options as $key => $option) {

        if (is_numeric($key))
            $key = $option;

        $template .= sprintf('<option value="%1$s" %3$s>%2$s</option>',
            esc_attr($key),
            $option,
            selected($key, $value, false));
    }

    return '
        <p class="field">
            <label for="'.$id.'">'.$field["label"].'</label>
            <select id="'.$id.'" name="'.$name.'" class="widefat">'.$template.'</select>
        </p>';

}
\add_filter("Metabox/render_field/select", __NAMESPACE__.'\select_template', 9, 5);
