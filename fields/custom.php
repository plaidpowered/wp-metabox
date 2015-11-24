<?php

namespace OUW\MetaBox;

function custom_template($template, $field, $post, $value) 
{
    $posts = get_posts(["post_type"=>$field["attrs"]["post_type"],"nopaging"=>true]);
    $options = "";
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
\add_filter("MetaBox/render_field/custom/template", __NAMESPACE__.'\custom_template', 9, 4);

