<?php

namespace OUW\MetaBox;

function taxonomy_value($value) 
{
                
    return !empty($value[0]) && $value[0] === "yes" ? 'checked="checked"' : '';
    
}
\add_filter("MetaBox/render_field/taxonomy/value", __NAMESPACE__.'\taxonomy_value', 9, 1);

function taxonomy_template($template, $field, $post) 
{
    
    if (isset($field["attrs"]["multiple"]) && $field["attrs"]["multiple"] === false)
    {
        
    } 
    else 
    {
    
        ob_start();
        \post_categories_meta_box($post, array("args" => array("taxonomy" => $field["attrs"]["taxonomy"])));
        $template = ob_get_clean();
        
    }
    
    return '    
        <p class="field">
            <label for="%1$s">%3$s</label>
            '.$template.'
        </p>';
    
}
\add_filter("MetaBox/render_field/taxonomy/template", __NAMESPACE__.'\taxonomy_template', 9, 3);

function taxonomy_save_value($value, $field, $metabox, $post_id) 
{
    $terms = array();
    
    if (isset($field["attrs"]["multiple"]) && $field["attrs"]["multiple"] === false)
    {
           
    }
    else
    {
        foreach($_POST["tax_input"][$field["attrs"]["taxonomy"]] as $term_id)
        {
            $term_id = absint(filter_var($term_id, FILTER_SANITIZE_NUMBER_INT));
            if ($term_id) 
            {
                $terms[] = $term_id;
            }
        }
        
        wp_set_object_terms($post_id, $terms, $field["attrs"]["taxonomy"]);
    }
    return $terms;
}
\add_filter("MetaBox/save_field/taxonomy", __NAMESPACE__.'\taxonomy_save_value', 9, 4);
