<?php

namespace WP_Metabox;

function label_template($template) {

    return '
        <p class="field label">
            %4$s
        </p>';

}
\add_filter("Metabox/render_field/label/template", __NAMESPACE__."\label_template", 9, 1);

\add_filter("Metabox/render_field/label/value", function ($value, $field) {

    return $field["value"];

}, 10, 2);

function label_save_value($value) {

    return "";

}
\add_filter("Metabox/save_field/label", __NAMESPACE__."\label_save_value", 9, 1);
