<?php

namespace WP_Metabox;

function checkbox_value($value) {

    return !empty($value[0]) && $value[0] === "yes" ? 'checked="checked"' : '';

}
\add_filter("Metabox/render_field/checkbox/value", __NAMESPACE__."\checkbox_value", 9, 1);

function checkbox_template($template) {

    return '
        <p class="field">
            <label for="%1$s"><input id="%1$s" name="%2$s" type="checkbox" value="true" %4$s %5$s> %3$s</label>
        </p>';

}
\add_filter("Metabox/render_field/checkbox/template", __NAMESPACE__."\checkbox_template", 9, 1);

function checkbox_save_value($value) {

    return !empty($value) ? "yes" : "no";

}
\add_filter("Metabox/save_field/checkbox", __NAMESPACE__."\checkbox_save_value", 9, 1);
