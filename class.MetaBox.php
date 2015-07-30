<?php

/*******************************************
   MetaBox
   desc: Build metaboxes really fast
 *******************************************/

namespace OUW\MetaBox;

class MetaBox {

    private $fields, $post_types, $perms;    
    public $id, $title, $atts;
    
    const FIELD_TEMPLATE  = '<p class="field"><label for="%1$s">%3$s</label><input id="%1$s" name="%2$s" value="%4$s" %5$s></p>';
        
    public function __construct($id, $title, $atts = array()) {
        
        $this->fields = array();
        
        $post_types = isset($atts["post_types"]) ? $atts["post_types"] : "post";
        
        if (is_string($post_types))
            $this->post_types = array($post_types);
        else if (is_array($post_types))
            $this->post_types = $post_types;
        else
            throw new Exception('Fatal error: optional attribute `$post_types` must be string or array');
        
        $this->id = $id;
        $this->title = $title;
        $this->atts = $atts;
        
        $this->perms = array();
        foreach($this->post_types as $post_type) 
        {
            if (isset($atts["perms"]) && is_string($atts["perms"]))
                $this->perms[$post_type] = $atts["perms"];
            else if (isset($atts["perms"]) && is_array($atts["perms"]) && isset($atts["perms"][$post_type]))
                $this->perms[$post_type] = $atts["perms"][$post_type];
            else
                $this->perms[$post_type] = "edit_{$post_type}";
        }
        
        add_action( 'add_meta_boxes', array($this, 'build'));
        add_action( 'save_post', array($this, 'save'));
        
    }
    
    public function add_field($name, $label, $attrs = array()) {
                    
        $newfield = array(
            "label" => $label,
            "name" => $name,
            "type" => "text",
            "attrs" => $attrs,
            "value" => ""
        );
        
        if (isset($newfield["attrs"]["type"])) 
        {
            $newfield["type"] = $newfield["attrs"]["type"];
            unset($newfield["attrs"]["type"]);
        }
        
        if (isset($newfield["attrs"]["value"]))
        {
            $newfield["value"] = $newfield["attrs"]["value"];
            unset($newfield["attrs"]["value"]);            
        }
        
        if (!isset($newfield["attrs"]["class"]))
        {
            $newfield["attrs"]["class"] = "widefat";
        }
        
        
        $newfield = \apply_filters("MetaBox/add_field/{$newfield["type"]}", $newfield);
        $newfield = \apply_filters("MetaBox/add_field/$name", $newfield);
        
        $this->fields[$name] = $newfield;
        
    }
        
    public function build() {
        
        \do_action("MetaBox/before_registration", $this);
            
        foreach ($this->post_types as $post_type) 
        {
            add_meta_box(
                $this->id, 
                $this->title,
                array($this, 'render'),
                $post_type,
                isset($this->atts["context"]) ? $this->atts["context"] : 'advanced',
                isset($this->atts["priority"]) ? $this->atts["priority"] : 'default'
            );
        }
        
        \do_action("MetaBox/after_registration", $this);
        
    }
    
    public function render($post) {
        
        $formoutput = "";
        
        \do_action("MetaBox/before_render", $this, $post);
        
        $meta = \get_post_meta($post->ID);
        
        foreach($this->fields as $name => $field) 
        {
            $field = \apply_filters("MetaBox/render_field/properties", $field);
            $id = $this->field_name($field);
            
            $value = isset($meta[$name]) ? $meta[$name] : $field["value"];
            $value = \apply_filters("MetaBox/render_field/{$field["type"]}/value", $value, $field);
            
            $template = \apply_filters("MetaBox/render_field/{$field["type"]}/template", self::FIELD_TEMPLATE, $field);
            
            if (is_array($value))
                $value = current($value);
            $output = sprintf($template, 
                              $id,
                              $id, 
                              $field["label"],
                              $value,
                              self::split_to_input($field["attrs"]));
          
            $output = apply_filters("MetaBox/render_field/output", $output, $field, $this);
            $output = apply_filters("MetaBox/render_field/{$field["type"]}", $output, $field, $this);
            $output = apply_filters("MetaBox/render_field/{$this->id}/$name", $output, $field, $this);
                                
            $formoutput .= $output;
          
        }
        
        $formoutput .= wp_nonce_field("MetaBox/save", "{$this->id}_nonce", true, false);
        
        $formoutput = apply_filters("MetaBox/form_output/{$this->id}", $formoutput, $this, $post);
        $formoutput = apply_filters("MetaBox/form_output", $formoutput, $this, $post);
        
        echo $formoutput;
        
        \do_action("MetaBox/after_render", $this, $post);        
        
        
    }
    
    static function split_to_input($attrs) 
    {
        $output = "";
        foreach($attrs as $key => $value)
        {
            $key = sanitize_title($key);
            if (!is_bool($value))
                $output .= ' ' . $key . '="' . esc_attr($value) . '"';
            else if ($value === true)
                $output .= ' ' . $key . '="' . $key . '"';
        }
      
        return $output;
    }
    
    private function field_name($field) 
    {
        return apply_filters("MetaBox/field_name/{$this->id}/{$field["name"]}", $this->id . "-" . $field["name"], $field, $this);
    }
    
    public function save($post_id) 
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
	   
        if (defined('DOING_AJAX') && DOING_AJAX)
            return;
        
        if (!isset($_POST["{$this->id}_nonce"]))
            return;
        
        if (!wp_verify_nonce($_POST["{$this->id}_nonce"], "MetaBox/save"))
            return;
        
        $post = get_post($post_id);
        if (!$post)
            return;
        
        if (!current_user_can($this->perms[$post->post_type], $post_id))
            return;
        
        foreach($this->fields as $name => $field)
        {
            $id = $this->field_name($field);            
            
            //if (!isset($_POST[$id]))
            //    continue;
            
            $filter = apply_filters("MetaBox/field_filter/{$field["type"]}", FILTER_SANITIZE_STRING);
                        
            $value = filter_input(INPUT_POST, $id, $filter);
            
            $value = apply_filters("MetaBox/save_field", $value, $field, $this);
            $value = apply_filters("MetaBox/save_field/{$field["type"]}", $value, $field, $this);
            $value = apply_filters("MetaBox/save_field/{$this->id}/{$field["name"]}", $value, $field, $this);
            
            do_action("MetaBox/before_field_saved", $this, $field, $value, $post_id);
            
            $meta_id = update_post_meta($post_id, $name, $value);
            
            do_action("MetaBox/after_field_saved", $this, $field, $value, $post_id);
            
        }
        
    }
                             
}
