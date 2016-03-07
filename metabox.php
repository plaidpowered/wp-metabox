<?php

include "class.MetaBox.php";

OUW\autoload_folder(__DIR__."/fields");


add_action("admin_enqueue_scripts", function() 
{
    wp_enqueue_style("ouw-metaboxes", OUW\theme_url(__DIR__."/style.css"));
    wp_register_script('metabox-term-icons', OUW\theme_url(__DIR__."/terms/icons.js"), array("jquery"), null, true);
});