/*global jQuery, document*/

jQuery(function ($) {
    "use strict";

    var $button = $('<button class="remove-field"><span class="dashicons dashicons-dismiss"></span></button>').on('click', function (e) {
        e.preventDefault();

        $(this).parent().remove();
    });

    $("button.add-field").on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var field = $(this).siblings(".field").last()[0].outerHTML,
            index = parseInt($(this).parent().attr('data-count'), 10);

        field = field.replace(/_[0-9]+/g, '_' + index);
        field = field.replace(/\[[0-9]+\]/g, '[' + index + ']');

        $(this).siblings(".field").last().after($(field));

        $(this).parent().attr('data-count', index + 1);
    });



    $(".multifield .field:not(:first-child)").append($button);

});
