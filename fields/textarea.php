<?php

namespace OUW\MetaBox;

function textarea_template($template, $field, $post, $value)
{
    if (is_array($value))
        $value = array_pop($value);

    return '
        <p class="field">
            <label for="%1$s">%3$s</label>
            <textarea name="%2$s" id="%1$s" class="widefat">'.$value.'</textarea>
        </p>';

}
\add_filter("MetaBox/render_field/textarea/template", __NAMESPACE__.'\textarea_template', 9, 4);
