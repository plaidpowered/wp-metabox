<?php

namespace OUW\MetaBox;

function select_template($template, $field, $post, $value)
{
    $empty_label = isset($field["attrs"]["empty_label"]) ? $field["attrs"]["empty_label"] : "";

    $options = isset($field["attrs"]["options"]) ? $field["attrs"]["options"] : array();
    $template = sprintf('<option value="">%s</option>', $empty_label);

    if (empty($value))
        $value = array();
    if (!is_array($value))
        $value = array($value);

    foreach($options as $key => $option) {

        if (is_numeric($key))
            $key = $option;

        $template .= sprintf('<option value="%1$s" %3$s>%2$s</option>',
            esc_attr($key),
            $option,
            selected(in_array($option, $value), true, false));
    }

    return '
        <p class="field">
            <label for="%1$s">%3$s</label>
            <select id="%1$s" name="%2$s" class="widefat">'.$template.'</select>
        </p>';

}
\add_filter("MetaBox/render_field/select/template", __NAMESPACE__.'\select_template', 9, 4);
