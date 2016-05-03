<?php

namespace Metabox;

include "class.MetaBox.php";

function autoload_folder($dir)
{
    $contained = scandir($dir);

    foreach($contained as $file)
    {
        if (substr($file,0,1) === '.')
            continue;

        if (is_dir($dir.'/'.$file) && is_file("$dir/$file/$file.php"))
        {
            include_once "$dir/$file/$file.php";
            continue;
        }

        if (!is_file("$dir/$file"))
            continue;

        if (pathinfo("$dir/$file", PATHINFO_EXTENSION) != 'php')
            continue;

        include_once $dir . '/' . $file;
    }
}

autoload_folder(__DIR__."/fields");

add_action("admin_enqueue_scripts", function()
{
    wp_enqueue_style("ouw-metaboxes", OUW\theme_url(__DIR__."/style.css"));
    wp_register_script('metabox-term-icons', OUW\theme_url(__DIR__."/terms/icons.js"), array("jquery"), null, true);
});
