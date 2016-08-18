<?php

namespace WP_Metabox;

add_filter("Metabox/render_field/textarea/value", function ($value) {

    if (is_array($value))
        $value = htmlentities(current($value), ENT_QUOTES);

    return $value;

});

function textarea_template()
{
    return '
        <p class="field">
            '.Metabox::FIELD_TEMPLATE_LABEL.'
            <textarea name="%2$s" id="%1$s" class="widefat">%4$s</textarea>
        </p>';
}
\add_filter("Metabox/render_field/textarea/template", __NAMESPACE__.'\textarea_template', 9, 0);

function textarea_save_value($value) {

    return html_entity_decode($value, ENT_QUOTES);

}
\add_filter("Metabox/save_field/textarea", __NAMESPACE__.'\textarea_save_value', 9, 1);
