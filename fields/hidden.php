<?php

namespace WP_Metabox;

\add_filter("Metabox/multiples_allowed/hidden", "__return_false", 99);


function hidden_template($template) {

    return '<input type="hidden" id="%1$s" name="%2$s" value="%4$s" %5$s>';

}
\add_filter("Metabox/render_field/hidden/template", __NAMESPACE__."\hidden_template", 9, 1);
