<?php

namespace WP_Metabox;

function taxonomy_value($value)
{

    return !empty($value[0]) && $value[0] === "yes" ? 'checked="checked"' : '';

}
\add_filter("Metabox/render_field/taxonomy/value", __NAMESPACE__.'\taxonomy_value', 9, 1);

function taxonomy_template($template, $field, $post)
{
    if (isset($field["attrs"]["multiple"]) && $field["attrs"]["multiple"] === false)
    {
        $my_terms = wp_get_object_terms($post->ID, $field["attrs"]["taxonomy"], array('fields'=>'ids'));

        $terms = get_terms($field["attrs"]["taxonomy"], array('hide_empty' => false));
        $template = '<select id="%1$s" name="%2$s" class="widefat">';
        $template .= '<option value="">(none)</option>';
        foreach($terms as $term)
        {
            $template .= sprintf('<option value="%d" %s>%s</option>',
                                 $term->term_id,
                                 in_array($term->term_id, $my_terms) ? "selected" : "",
                                 $term->name);
        }
        $template .= '</select>';
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
\add_filter("Metabox/render_field/taxonomy/template", __NAMESPACE__.'\taxonomy_template', 9, 3);

function taxonomy_save_value($value, $field, $metabox, $post_id)
{

    if (isset($field["attrs"]["multiple"]) && $field["attrs"]["multiple"] === false)
    {

        $terms = empty($value) ? null : absint(filter_var($value, FILTER_SANITIZE_NUMBER_INT));

    }
    else
    {
        if (empty($_POST["tax_input"][$field["attrs"]["taxonomy"]]))
        {
            $terms = null;
        }
        else
        {
            $terms = array();
            foreach($_POST["tax_input"][$field["attrs"]["taxonomy"]] as $term_id)
            {
                $term_id = absint(filter_var($term_id, FILTER_SANITIZE_NUMBER_INT));
                if ($term_id)
                {
                    $term = get_term($term_id, $field["attrs"]["taxonomy"]);
                    $terms[] = $term->slug;
                }
            }
        }
    }
    wp_set_object_terms($post_id, $terms, $field["attrs"]["taxonomy"]);

    return $terms;
}
\add_filter("Metabox/save_field/taxonomy", __NAMESPACE__.'\taxonomy_save_value', 9, 4);
