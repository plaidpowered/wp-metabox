<?php

namespace OUW\MetaBox;


function datetime_template($template, $field, $post, $value) {
    
    
    if (!empty($value)) 
    {
        preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $value[0], $date);
        preg_match('/[0-9]{2}:[0-9]{2}:[0-9]{2}/', $value[0], $time);
        
        $date = !empty($date) ? $date[0] : "";
        $time = !empty($time) ? $time[0] : "";
            
    }
    else
    {
        $date = "";
        $time = "";
    }
    
    return '    
        <p class="field">
            <label for="%1$s">%3$s</label>
            <input id="%1$s_date" name="%2$s[date]" type="date" class="widefat" value="'.$date.'" placeholder="Date">
            <input id="%1$s_time" name="%2$s[time]" type="time" class="widefat" value="'.$time.'" placeholder="Time">
        </p>';
    
}
\add_filter("MetaBox/render_field/datetime/template", __NAMESPACE__."\datetime_template", 9, 4);

function datetime_save_value($value) {
    
    $datetime = array();
    if (!empty($value["date"])) {
        $datetime[] = date("Y-m-d", strtotime($value["date"]));
    }
    if (!empty($value["time"]))
        $datetime[] = date("H:i:s", strtotime($value["time"]));
    
    return implode(" ", $datetime);
    
}
\add_filter("MetaBox/save_field/datetime", __NAMESPACE__."\datetime_save_value", 9, 1);

\add_filter("MetaBox/field_filter/datetime", "__return_false");