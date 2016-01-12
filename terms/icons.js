jQuery(function($) {
   
    $("select.icon-selector").each(function() {
       
        var $self = $(this),
            $options = $self.find("option"),
            $selector = $("<div>");
                
        $self.hide();
        
        $selector.addClass("icon-selector");
        
        $options.each(function() {
           
            var $option = $(this),
                $selection = $("<span>");
            
            // SKIP STEPS ONE AND THREEEEEEE
            
        });
        
    });
    
});