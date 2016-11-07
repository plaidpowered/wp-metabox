# Wordpress Metabox Utility

Requires: PHP 5.5 or higher  
License: GPL v3

A tool for easily adding custom fields to your post editor, not intended as a standalone plugin, but to be shipped along with your theme. In some ways, it is meant to be the procedural equivalent of the popular Advanced Custom Fields plugin, it provides no settings or configuration for the end user, but instead all metaboxes and field definitions must be created through your theme code.

## Example usage

Of course, start by including it - probably in `functions.php`

```
require_once "metabox\metabox.php";
```

Then you'll just need to create a new metabox to attach fields to. It's best if you do this inside the `admin_init` hook

```
add_action('admin_init', function () {
    $metabox = new WP_Metabox\Metabox("custom_meta", "Meta", array(
            "post_types" => array("post"),
            "context" => "normal",
            "priority" => "high",
            "perms" => "edit_post"
        ));
});
```
The first parameter of the `Metabox` constructor is an ID to assign to the meta box, the second parameter is a label that will be visible on the admin editor screen, and the third is an array of options. None are required, the following are available:

* `post_types` - by default, the metabox will be applied on the Posts editor. You can assign an array of post types this metabox will be visible on using this parameter.

* `context` - Translates to the `$context` parameter of [add_meta_box](https://developer.wordpress.org/reference/functions/add_meta_box/), Wordpress currently allows *normal*, *advanced*, or *side*, defaults to *advanced*. The Metabox utility also adds a fourth parameter option of *deck*; a meta box assigned to the *deck* context will appear directly below the Post Title field on the post editor.

* `priority` - Translates to the `$priority` parameter of [add_meta_box](https://developer.wordpress.org/reference/functions/add_meta_box/), Wordpress currently allows *high*, *low*, or *core*, defaults to *default*.

* `perms` - A single role, or an array of user roles the user must be a member of to access the meta box. If parameter is an array, it can be used to define different roles per post type, for instance 
   ```
   array("page" => "edit_page", "post" => "edit_post", "custom" => "edit_custom")
   ```

After creating a meta box, you need to assign some fields to it. You do so by calling the `add_field` method of the Metabox object you just created, for example:
```
$metabox->add_field("external_url", "URL", array(
        "type"        => "url",
        "placeholder" => "URL of site this post is attached to",
    ));
```
The `add_field` method has three parameters, **$key**, the meta_key the field will be assigned to in the postmeta table, **$label**, a label that will appear to the left of the field on the posts editor, and **$params**, an optional array of arguments to be passed to the field. Field arguments will vary based on field type, but in general, you can expect to use the following arguments:

* `type` - The field type, defaults to *text*. Any number of custom field types can be hooked into the Metabox class, but if no hooks are provided, the `type` argument will be inserted into **type** attribute of the `<input>` field. A number of field types are provided with the Metabox package, and are documented below.

* `value` - A default value to assign to the field, no default.

* `class` - A default CSS class to assign to the field `<input>` tag. Defaults to *widefat*

* `placeholder` - What to put in the placeholder attribute of the `<input>` tag. No default.

* `multiple` - Most field types have the option of having multiple values. Assign **multiple** to *true* (boolean) if you would like the meta box to provide this functionality on the post editor. **Note, this feature is experimental and not currently available in the master branch, please use the unstable branch to utilitize this functionality.**

* `desc` - A string outputted directly under the input field to provide instructions or other information.

To access content managed by the Metabox class, simply use the normal `post_meta` functions provided by wordpress. If your meta field is a single value, you could use `get_post_meta(get_the_ID(), "external_url", true)` to return a single value, or if it has multiple values, use `get_post_meta(get_the_ID(), "external_url")` to return an array.

That's all! See below for the field types available by default.

## Field Types Provided Out Of The Box

**text**  
    A basic text input. Alternately, you can enter any non-provided field type and it will be inserted into the `type=` attribute of `<input>`, like *tel*, *url*, or *color*. Uses default options.

**checkbox**  
    A checkbox field. The **$label** parameter of `add_field` is displayed to the right of the checkbox input. Does not support the **multiple** option. The value stored in the database will be *yes* or *no*.

**custom**  
    This will create a dropdown filled with a post type of your choice. Provide the **post_type** option to specify the post type desired, for example:

    ```
    $metabox->add_field("page", "Assign to page", array(
            "type"      => "custom",
            "post_type" => "page"
        ));
    ```
    
**datetime**  
    Provides two input fields designed for storing dates and times. A helper function of `WP_Metabox\datetime_convert` is also available for extracting the date, time, and unix epoch from a meta field generated by this field type. Simply pass the string value returned by `get_post_meta` into `datetime_convert` and an associative array containing **date** (the date), **time** (the time), **datetime** (the original value), and **unixtime** (the number of seconds since the unix epoch) will be returned.

**hidden**  
    A hidden field with a value assigned from the field parameters. Does not support the **multiple** parameter.

**label**  
    A label with no input field, useful for headings. All parameters will be ignored.

**media**  
    Attaches posts from the Media library to the post. **$label** will be used for the "Add media" button label, does not support the **multiple** parameter.

**select**  
    Show a dropdown of defined options. Use the **options** parameter to define options available, for example:
    
    ```
    $metabox->add_field("option", "Made with", array(
            "type"    => "select",
            "options" => array(
                "coffee" => "Coffee",
                "tea"    => "Tea",
                "etc"    => "Another brown liquid"
            )
        ));
    ```
    
**taxonomy**  
    A dropdown or a taxonomy selection tab filled with terms from the taxonomy of your choice. Although this field does assign a value to the `postmeta` table, the proper way to access data stored by this field type would be through standard Wordpress term/taxonomy functions like `get_the_terms` or `wp_get_object_terms`. Also of note, the default **multiple** value is reversed on this field type, if **multiple** is omitted or assigned to *true*, multiple terms can be assigned through this field. Set **multiple** to *false* (boolean) to show a dropdown of available terms.

**textarea**  
    A standard `<textarea>` field.

## Hooks, filters, and making your own fields

Documentation coming soon. In the meantime, read the source!
