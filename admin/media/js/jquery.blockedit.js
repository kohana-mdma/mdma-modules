/**
 * Plugin for block edit
 * @author dvc.dev
 * @version 1.0.1
 */
(function($){
    /**
     * Get class begins from string
     * @param {Object} beginStr
     */
    $.fn.getClassBegins = function(beginStr){
        if(beginStr!="" && $(this).attr("class")!=""){
            var classes = $(this).attr("class").split(" ");
            for (var i = 0; i < classes.length; i++){
                if ( classes[i].substr(0, beginStr.length) == beginStr ){
                    return classes[i];
                }
            }
        }
        else{
            return false;
        }
    };

    $.fn.blockedit = function(method) {
        var $blockedit, $blockId;
        var options;
        var defaults = {
            setTextUrl : "/admin/block/edit/",
            elementId : "",
            editor: {}
        };
        var methods = {
            /**
             * Init of our plugin
             */
            init : function( option ) {
                return this.each(function() {
                    options = $.extend(defaults, option);
                    $blockedit = $(this);
                    //alert(options.elementId);
                    $blockedit.one("dblclick.blockedit", function(event){
                            event.stopPropagation();
                            $blockedit.unbind("dblclick.blockedit");
                            openTextarea(event);
                    });
                    $blockId = $blockedit.getClassBegins("blockedit-");
                    $blockId = $blockId.replace("blockedit-","id-");
                    options.setTextUrl = options.setTextUrl+$blockId.replace("id-","");
                });
            },
            /**
             * Destroy of our plugin
             */
            destroy : function(){
                return this.each(function(){
                    $blockedit.unbind(".blockedit");
                });
            }
        }

        // Method calling logic
        if ( methods[method] ) {
            if(method.indexOf("_")==0){
                $.error( 'Method ' +  method + ' is private' );
            }
            else{
                return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
            }
        } else if ( typeof method === 'object' || ! method ) {
            return methods.init.apply( this, arguments );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
        }

        /**
         * Open redactor textarea
         */
        function openTextarea(e){
            e.preventDefault();
            if (!$blockedit.hasClass("active")) {
                $blockedit.addClass("active");
                //$blockeditText = $blockedit.clone().wrap("<div />").removeClass("block-admin").parent();
                //options.editor.oldText = $.trim($blockedit.html());
                $overlay = $('<div class="blockedit-overlay"></div>').appendTo("body");
                $overlay.css("height", $("html").height()).fadeIn("300");
                $popup = $('<div class="blockedit-popup"></div>').appendTo("body");
                $popup.html('<textarea>' + $.trim($blockedit.html()) + '</textarea>').append('<ul class="blockedit-menu"></ul>');
                $("textarea", $popup).editor({
                    toolbar: 'blockedit',
                    resize: false,
                    body_class: "model-block "+$blockId
                });

                $("ul.blockedit-menu", $popup).append('<li><a href="' + options.setTextUrl + '" class="blockedit-menu-edit">Сохранить</a></li>').append('<li><a href="' + options.setTextUrl + '" class="blockedit-menu-close">Закрыть</a></li>').append('<li><a href="' + options.setTextUrl + '" class="blockedit-menu-adm">Открыть в админке</a></li>');
                //save button
                $("a.blockedit-menu-edit", $popup).bind("click.blockedit", function(e){
                    e.preventDefault();
                    sendTextarea();
                });
                //close button
                $("a.blockedit-menu-close", $popup).bind("click.blockedit", function(e){
                    e.preventDefault();
                    closeTextarea();
                });
                $overlay.bind("click.blockedit", function(){
                    closeTextarea();
                });
            }
        }

        /**
         * Send redactor textarea and close
         */
        function sendTextarea(){
            //send ajax
            $popupText = $("textarea", $popup).val();
            $.ajax({
                url: options.setTextUrl,
                dataType: "json",
                type: "POST",
                data: {"body": $popupText},
                success: function(data){
                    $blockedit.html(data.data.body);
                },
                error :function(data) {
                    alert(data.status+" "+data.statusText+" - извините, запрос не может быть выполнен, обратитесь, пожалуйста, к администратору сайта");
                }
            });
            $blockedit.removeClass("active");
            $overlay.fadeOut("300", function(){$overlay.remove();});
            $popup.fadeOut("300", function(){$popup.remove();});
            $blockedit.one("dblclick.blockedit", function(e){openTextarea(e);});
        }

        /**
         * Close redactor textarea
         */
        function closeTextarea(){
            $blockedit.removeClass("active");
            //$blockedit.html(options.editor.oldText);
            $overlay.fadeOut("300", function(){$overlay.remove();});
            $popup.fadeOut("300", function(){$popup.remove();});
            $blockedit.one("dblclick.blockedit", function(e){openTextarea(e);});
        }
    };
})(jQuery);

$(function(){
    /**
     * Add blockedit plugin
     */
    $('div[class*="blockedit-"]').each(function(){
        $(this).blockedit();
        $(this).addClass("blockedit-admin");
    });
});