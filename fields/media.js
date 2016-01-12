/*global jQuery,wp*/

jQuery(function ($) {
    "use strict";
   
    $("button.media-selector").on('click', function (e) {
                
        var file_frame, image_data, $attachmentIds, $attachmentFns;
        
        $attachmentIds = $(this).prev(".media-selection");
        $attachmentFns = $(this).next(".media-filenames");
 
        if (undefined !== file_frame) {
            file_frame.open();
            return;
        }

        file_frame = wp.media.frames.file_frame = wp.media({
            title:   'Choose or upload a file',
            frame: 'select',
            multiple: true,
            button: {
                text: 'Select'
            }
        });

        file_frame.on('select', function () {

            var attachments = file_frame.state().get('selection');
                
            $attachmentFns.empty();
            $attachmentIds.val('');
            attachments.map(function (attachment) {
                var attObj = attachment.toJSON(),
                    $li = $("<li>").append($("<figure>"));
                
                //console.log(attObj);
                
                $li.data("id", attObj.id);
                if (attObj.sizes) {
                    $li.find("figure").append(
                        $("<img>").attr("src", attObj.sizes.thumbnail.url)
                                  .attr("width", attObj.sizes.thumbnail.width)
                                  .attr("height", attObj.sizes.thumbnail.height)
                    );
                } else {
                    $li.find("figure").append($("<img>").attr("src", attObj.icon));
                }
                $li.find("figure").append($("<figcaption>").html(attObj.filename));
                
                $attachmentIds.val($attachmentIds.val() + '{' + attObj.id + '}');
                $attachmentFns.append($li);
            });
                    
            

        });

        // Now display the actual file_frame
        file_frame.open();


    });
    
});