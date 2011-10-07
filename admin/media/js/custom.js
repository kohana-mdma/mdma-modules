$(function(){

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

    var fixHelper = function (e, ui) {
        ui.children().each(function () {
            $(this).width($(this).width())
        });
        return ui
    };

    $('table.sortable tbody').sortable({
        handle: 'img.move',
        helper: fixHelper,
        placeholder: 'ui-state-highlight',
        forcePlaceholderSize: true
    }).disableSelection();

    $('ul.sortable').sortable({
        placeholder: 'ui-state-highlight',
        forcePlaceholderSize: true
    });

    /**
     * Check all checkboxes on table
     */
    var togel = false;
    $('#table1 .checkall').click(function () {
        $('#table1 :checkbox').attr('checked', !togel);
        togel = !togel
    });

    var togel2 = false;
    $('#table2 .checkall').click(function () {
        $('#table2 :checkbox').attr('checked', !togel2);
        togel2 = !togel2
    });

    $('table.detailtable tr.detail').hide();
    $('table.detailtable > tbody > tr:nth-child(4n-3)').addClass('odd');
    $('table.detailtable > tbody > tr:nth-child(4n-1)').removeClass('odd').addClass('even');
    $('a.detail-link').click(function () {
        $(this).parent().parent().next().fadeToggle();
        return false
    });

    /**
     * Top menu
     */
    $('ul.sf-menu').superfish({
        delay: 107,
        animation: false,
        dropShadows: false
    });
    $('ul.sf-menu li.current').each(function(){
        $(this).parent("ul").parent("li").addClass("current");
    });

    $('.msg').click(function () {
        $(this).fadeTo('slow', 0);
        $(this).slideUp(341)
    });

    $('#dob').datepicker({
        changeMonth: true,
        changeYear: true
    });
    $('#newsdate').datepicker();
    $('.accordion > h3:first-child').addClass('active');
    $('.accordion > div').hide();
    $('.accordion > h3:first-child').next().show();
    $('.accordion > h3').click(function () {
        if ($(this).hasClass('active')) {
            return false
        }
        $(this).parent().children('h3').removeClass('active');
        $(this).addClass('active');
        $(this).parent().children('div').slideUp(200);
        $(this).next().slideDown(200)
    });

    $('.tabcontent > div').hide();
    $('.tabcontent > div:first-child').show();
    $('.tabs > li:first-child').addClass('selected');
    $('.tabs > li a').click(function () {
        var tab_id = $(this).attr('href');
        $(tab_id).parent().children().hide();
        $(tab_id).fadeIn();
        $(this).parent().parent().children().removeClass('selected');
        $(this).parent().addClass('selected');
        return false
    });
    $('#myForm').validate();
    //$('.uniform input[type="checkbox"], .uniform input[type="radio"], .uniform input[type="file"]').uniform();

	$(".table-rbac").each(function(){
		var levelClass = "table-rbac-l",
			$thisTable = $(this),
			$allRow = $thisTable.find("tbody tr");
		$allRow.find("input").bind("click", function(){
			var $this = $(this),
                level = $this.parents("tr").attr("class").substring(levelClass.length, $this.parents("tr").attr("class").indexOf(" ")==-1 ? $this.parents("tr").attr("class").length : $this.parents("tr").attr("class").indexOf(" ")),
				col  = $this.parents("td").index(),
				thisRow = $this.parents("tr").index();
            //FIX
            if($this.parents("tr").next("tr."+levelClass+(level-1)).size()){
                var nextRow = thisRow;
            }
            else{
                var nextRow = $this.parents("tr").nextAll("tr."+levelClass+level).eq(0).index();
                nextRow = nextRow == -1 ? $allRow.size() : nextRow;
            }
			$allRow.slice(thisRow, nextRow)
				.children("td:nth-child("+(col+1)+")")
				.children("input")
				.attr("checked", $this.attr("checked"));
		});
	});

    /**
     * jsTree
     **************************/
    /**
     * Create new node
     * @param {Object} data
     */
    function createNode(data){
        var parent = getNodeId(data);
        var model = getNodeMT(data).model;
        var type = getNodeMT(data).type;
        //TODO create function for types and models
        if(model=="root"){
            location.href = "/admin/page/add/node/"+parent;
        }
        if(model=="step"){
            //do nothing (:
        }
        if(model=="page"){
            if(type=="node"){
                location.href = "/admin/"+model+"/add/node/"+parent;
            }
        }
        //var title = data.rslt.name;
        /*$.post(
            "/admin/node/add",
            {
                "parent" : parent,
                "title" : title
            },
            function (r) {
                if(r.type=="success"){
                    location.href = "/admin/page/add/node/"+r.data.id;
                }
                if(r.type=="error"){

                }
            },
            "json"
        );*/
    };

    /**
     * Get model and type of node (rel param)
     * @param {Object} rel
     */
    function getNodeMT(node){
        var types = node.attr("rel");
        types = types.split(".");
        return {"model":types[0], "type":types[1]};
    }

    function getNodeId(node){
        return node.attr("id").replace("node_","");
    }

    function showNode(node){
        var id = $(node).attr("id").replace("node_","");
        $.ajax({
            async : false,
            type: 'POST',
            url: "/admin/node/url/",
            data : {
                "id" : id
            },
            "dataType":"json",
            success : function (r) {
                if(r.type=="success"){
                    window.open(r.data.url);
                }
            }
        });
    }

    if($.fn.jstree){
        $(".menu section").jstree({
            "plugins" : [ "themes", "html_data", "ui", "crrm", "contextmenu","dnd", "types", "cookies"],
            "themes" : {
                "theme" : "default"
            },
            "core" : {
                "initially_open" : ["node_0"]
            },
           "types" : {
                "max_depth" : -2,
                "max_children" : -2,
                "valid_children" : [ "root" ],
                "types" : {
                    "default" : {
                        "valid_children" : "all"
                    },
                    "root" : {
                        "icon" : {
                            "image" : "/favicon.ico"
                        },
                        "valid_children" : "default",
                        "start_drag" : false,
                        "delete_node" : false,
                        "remove" : false,
                        "close_node" : false
                    },
                    "page.node" :{

                    },
                    "step.root": {
                        "icon" : { "image" : "/js/jstree/themes/default/icon-folder.png"},
                        "delete_node" : false,
                        "remove" : false,
                        "valid_children" : "step.leaf"
                    },
                    "step.leaf": {
                        "icon" : { "image" : "/js/jstree/themes/default/icon-blue-document-text-image.png"},
                        "valid_children" : "none",
                        "delete_node" : false,
                        "remove" : false,
                        "start_drag" : false
                    },
                    "term.root": {
                        "icon" : { "image" : "/js/jstree/themes/default/icon-blue-document-attribute.png"},
                        "delete_node" : false,
                        "remove" : false,
                        "valid_children" : "none"
                    },
                }
            },
            "contextmenu": {
                "items": function(node){
                    //get context menu of node
                    switch (node.attr("rel")){
                        case "root":
                            return {
                                "create" : {
                                    "label"             : "Создать страницу",
                                    "action"            : function (obj) { createNode(obj); }
                                }
                            }
                            break;
                        case "step.root":
                            return {
                                "rename" : {
                                    "label"             : "Переименовать",
                                    "action"            : function (obj) { this.rename(obj); }
                                }
                            }
                            break;
                        case "step.leaf":
                        case "step.node":
                            return {
                                "rename" : {
                                    "label"             : "Переименовать",
                                    "action"            : function (obj) { this.rename(obj); }
                                },
                                "show": {
                                    "label"     : "Посмотреть на сайте",
                                    "action"   : function (obj) { showNode(obj); }
                                }
                            }
                            break;
                        case "term.root":
                            return {

                            }
                            break;
                        default:
                             return {
                                "create" : {
                                    "label"             : "Создать подстраницу",
                                    "action"            : function (obj) { createNode(obj); }
                                },
                                "rename" : {
                                    "label"             : "Переименовать",
                                    "action"            : function (obj) { this.rename(obj); }
                                },
                                "remove" : {
                                    "label"             : "Удалить",
                                    "action"            : function (obj) { this.remove(obj); }
                                },
                                "show": {
                                    "label"     : "Посмотреть на сайте",
                                    "action"   : function (obj) { showNode(obj); }
                                }
                            }
                            break;
                    }
                    ///get context menu of node
                }
            }
        })
        .bind("create.jstree", function (e, data) {
            var parent = data.rslt.parent.attr("id").replace("node_","");
            var title = data.rslt.name;
            $.post(
                "/admin/node/add",
                {
                    "parent" : parent,
                    "title" : title
                },
                function (r) {
                    if(r.type=="success"){
                        location.href = "/admin/page/add/node/"+r.data.id;
                    }
                    if(r.type=="error"){

                    }
                },
                "json"
            );
        })
        .bind("remove.jstree", function (e, data) {
            data.rslt.obj.each(function () {
                $.ajax({
                    async : false,
                    type: 'POST',
                    url: "/admin/node/remove",
                    data : {
                        "id" : this.id.replace("node_","")
                    },
                    success : function (r) {

                    }
                });
            });
        })
        .bind("rename.jstree", function (e, data) {
            var nodeId = data.rslt.obj.attr("id").replace("node_","");
            $.post(
                "/admin/node/rename",
                {
                    "id" : nodeId,
                    "title" : data.rslt.new_name
                },
                function (r) {
                    //TODO rewrite this, because its glooobal
                    if(location.href.indexOf("/node/"+nodeId)!=-1){
                        $("#node-title").val(data.rslt.new_name);
                    }
                }
            );
        })
        .bind("move_node.jstree", function (e, data) {
            data.rslt.o.each(function (i) {
                //console.log(data);
                var id = $(this).attr("id").replace("node_","");
                var parent = data.rslt.np.attr("id").replace("node_","");
                console.log(i)
                var position = data.rslt.cp + i +1;
                var name = $.trim(data.rslt.o.find("a").text());
                $.ajax({
                    async : false,
                    type: 'POST',
                    url: "/admin/node/move",
                    data : {
                        "id" : id,
                        "parent" : parent,
                        "position" : position
                    },
                    success : function (r) {

                    },
                    "dataType":"json"
                });
            });
        })
        .bind("select_node.jstree", function(e, data){
            //check selected node from cookie
            if(decodeURIComponent(readCookie("jstree_select"))=="#"+data.rslt.obj.attr("id")){
               return;
            }
            else{
                //console.log(data.rslt.obj.children("a"))
                location.href = data.rslt.obj.children("a").attr("href");
            }
        })
        .bind("show_contextmenu.jstree", function(){
//            console.log("!")
        });
    }

    /**
     * Toggle version sidebar block
     */
    if($(".block-version li.current").size()){
        $(".block-version a.block-version-more").hide();
    }else{
        $(".block-version a.block-version-more").hide().parent().children("ul").children("li").hide().slice(0,5).show();
    }
    if($(".block-version ul").children("li:hidden").size()!=0){
        $(".block-version a.block-version-more").show();
        $(".block-version a.block-version-more").bind("click", function(){
            $(this).parent().children("ul").children("li").slideDown(200);
            $(this).hide();
            return false;
        });
    }

    /**
     * Set wysiwyg
     */
    if($.fn.editor){
        //default editor
        if($("textarea.editor").size()){
            $("textarea.editor").each(function(){
                var $this = $(this);
                $this.editor({
                    toolbar: 'blockedit',
                    skin: 'carrara_white'
                    //body_class:  $this.parents("form").getClassBegins("model-")+" "+$("textarea.editor").getClassBegins("id-")
                });
            });
        };

    }
});

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}