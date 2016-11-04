<?php

namespace WP_Metabox;

include "class.Metabox.php";

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

function theme_url($path, $theme_dir=null)
{
    if (is_null($theme_dir))
        $theme_dir = THEME_DIR;

    $path = str_replace($theme_dir, "", $path);
    $path = trim($path, "/");

    $uri = get_template_directory_uri() . "/" . $path;

    return $uri;
}


autoload_folder(__DIR__."/fields");

add_action("admin_enqueue_scripts", function()
{
    wp_enqueue_style("wp-metaboxes", theme_url(__DIR__."/style.css"));
    wp_enqueue_script("wp-metaboxes", theme_url(__DIR__."/fancy.js"), array("jquery"), null, true);
    wp_register_script('metabox-term-icons', theme_url(__DIR__."/terms/icons.js"), array("jquery"), null, true);
});
