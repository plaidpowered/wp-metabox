<?php

namespace WP_Metabox;

add_filter("Metabox/render_field/textarea/value", function ($value) {

    if (is_array($value))
        $value = htmlentities(current($value), ENT_QUOTES);

    return $value;

});

function textarea_template($template, $field, $post, $value)
{
    return '
        <p class="field">
            <label for="%1$s">%3$s</label>
            <textarea name="%2$s" id="%1$s" class="widefat">%4$s</textarea>
        </p>';
}
\add_filter("Metabox/render_field/textarea/template", __NAMESPACE__.'\textarea_template', 9, 4);

function textarea_save_value($value) {

    return html_entity_decode($value, ENT_QUOTES);

}
\add_filter("Metabox/save_field/textarea", __NAMESPACE__.'\textarea_save_value', 9, 1);
