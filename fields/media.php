<?php

namespace Metabox;

function media_template($template, $field, $post, $value)
{

    wp_enqueue_media();
    wp_enqueue_script('metabox-media-selector', OUW\theme_url(__DIR__."/media.js"), array("jquery"), null, true);

    if (!empty($value)) {
        $value = unserialize($value[0]);
    }

    $attachments = "";
    $attachmentIds = "";
    if (!empty($value))
    {
        foreach($value as $attId)
        {
            if (!empty($attId)) {
                //$att = basename(get_attached_file($attId));

                $image = wp_get_attachment_image($attId, 'thumbnail', true);
                $filename = basename(get_attached_file($attId));

                $attachments .= "<li data-id='{$attId}'><figure>$image<figcaption>$filename</figcaption></figure></li>";
                $attachmentIds .= sprintf('{%s}', $attId);
            }
        }
    }

    return '
        <div class="field">
            <input type="hidden" name="%2$s" class="media-selection" value="'.$attachmentIds.'">
            <button class="button button-primary media-selector" type="button">%3$s</button>
            <ul class="media-filenames">
            '.$attachments.'
            </ul>
        </div>';

}
\add_filter("Metabox/render_field/media/template", __NAMESPACE__.'\media_template', 9, 4);

function media_save_value($value)
{

    $values = explode("}{", trim($value, '{}'));

    return $values;

}
\add_filter("Metabox/save_field/media", __NAMESPACE__."\media_save_value", 9, 1);

function get_post_media($post_id, $key, $size="thumbnail", $attrs=array())
{
    $value = get_post_meta($post_id, $key, true);

    if (!empty($value)) {
        $value = unserialize($value[0]);
    } else {
        return array();
    }

    $attachments = array();
    foreach($value as $id)
    {
        $post = get_post($id);
        if (!empty($post)) {
            $attachments[$id] = array(
                "post" => $post,
                "img" => wp_get_attachment_image($id, $size, true, $attrs),
                "src" => wp_get_attachment_src($id, $size),
                "url" => wp_get_attachment_url($id)
            );
        }
    }

    return $attachments;
}
