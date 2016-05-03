<?php

namespace Metabox;

function datetime_convert($meta_value)
{
    preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $meta_value, $date);
    preg_match('/[0-9]{2}:[0-9]{2}:[0-9]{2}/', $meta_value, $time);

    $date = !empty($date) ? $date[0] : "";
    $time = !empty($time) ? $time[0] : "";
    
    return array($date, $time, 
                 "date" => $date, 
                 "time" => $time, 
                 "datetime" => trim("$date $time"),
                 "unixtime" => strtotime($meta_value));
}

function datetime_format($datetime, $datef="F j", $timef="h:i a") 
{

    $date = array();
    if (!empty($datetime["date"]))
        $date[] = date($datef, $datetime["unixtime"]);
    if (!empty($datetime["time"]))
        $date[] = date($timef, $datetime["unixtime"]);
    $date = implode(", ", $date);
    
    return $date;
}

function datetime_template($template, $field, $post, $value) 
{
    
    
    if (!empty($value)) 
    {
        list($date, $time) = datetime_convert($value[0]);
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
\add_filter("Metabox/render_field/datetime/template", __NAMESPACE__."\datetime_template", 9, 4);

function datetime_save_value($value) 
{
    
    $datetime = array();
    if (!empty($value["date"])) {
        $datetime[] = date("Y-m-d", strtotime($value["date"]));
    }
    if (!empty($value["time"]))
        $datetime[] = date("H:i:s", strtotime($value["time"]));
    
    return implode(" ", $datetime);
    
}
\add_filter("Metabox/save_field/datetime", __NAMESPACE__."\datetime_save_value", 9, 1);

\add_filter("Metabox/field_filter/datetime", "__return_false");