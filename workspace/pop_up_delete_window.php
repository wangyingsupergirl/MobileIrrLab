<style type="text/css">
    #popupDeleteConfirm{
        display:none;  
        position:fixed;  
        /*_position:absolute; /* hack for internet explorer 6*/  
        height:300px;  
        width:408px;  
        background:#FFFFFF;  
        border:2px solid #cecece;  
        z-index:2;  
        padding:12px;  
        /*font-size:13px;*/  
    } 

    #backgroundPopup{  
        display:none;  
        position:fixed;  
        /*_position:absolute; /* hack for internet explorer 6*/  
        height:100%;  
        width:100%;  
        top:0;  
        left:0;  
        background:#000000;  
        border:1px solid #cecece;  
        z-index:1;  
    } 

    #popupClose{  
        font-size:14px;  
        line-height:14px;  
        right:6px;  
        top:4px;  
        position:absolute;  
        color:#6fa5fd;  
        font-weight:700;  
        display:block;  
    }  

</style>
<!--Pop up delete confirmation window-->
<div id="popupDeleteConfirm">
    <a id="popupClose" onclick="disablePopup()">x</a>
    <div id="delete_msg_box" style="vertical-align:middle; padding:20px 5px"></div>
    <form id ="delete_form" method="post" action="<?php echo MIL_SERVER_ROOT; ?>workspace/workspace_control.php">
        <!--<input type="submit" id="delete_yes" name="" value="Yes"/>
        <input type="button" id="delete_cancel" name="delete_cancel" value="Cancel"/>-->
    </form>
</div>
<!--End of Pop up delete confirmation window-->
<!--Background Popup-->
<div id="backgroundPopup" onclick="disablePopup()"></div> 
<script type="text/javascript">
    var popupStatus = 0;
//    $(":button").click(function(){  
//        //centering with css  
//        var id = $(this).attr('name');
//        var arr = id.split(':');
//        if(arr.length==2){
//            var operation = arr[0];
//                    
//            if(operation=='delete_evaluation'){
//                $.post("<?php echo MIL_SERVER_ROOT; ?>workspace/workspace_control.php",
//                {button_name: id, request_name:"get_delete_msg"},
//                function(data){
//                    if(data!=false&&data!=''){
//                        $("#delete_msg_box").html('<h4>Are you sure to delete this evaluation?</h4>  '+data+' will be deleted too.');
//                        //$("#delete_yes").attr("disabled", "disabled");
//                    }else{
//                         $("#delete_msg_box").html('<h4>Are you sure to delete this evaluation?</h4>');   
//                    }
//                    $('#delete_yes').attr('name', id);
//                    centerPopup();
//                    //load popup
//                    loadPopup(); 
//                });
//            }else{
//                $("#delete_msg_box").html('<h4>Are you sure to delete this evaluation?</h4>');     
//                $('#delete_yes').attr('name', id);
//                centerPopup();
//                //load popup
//                loadPopup();
//
//        }}
//      }); 
            
    //centering popup  
    function centerPopup(){  
        //request data for centering  
        var windowWidth = document.documentElement.clientWidth;  
        var windowHeight = document.documentElement.clientHeight;  
        var popupHeight = $("#popupDeleteConfirm").height();  
        var popupWidth = $("#popupDeleteConfirm").width();  
        //centering  
        $("#popupDeleteConfirm").css({  
            "position": "absolute",  
            "top": windowHeight/2-popupHeight/2,  
            "left": windowWidth/2-popupWidth/2  
        });  


        $("#backgroundPopup").css({  
            "height": windowHeight  
        });  

    }
            
    //loading popup with jQuery magic!  
    function loadPopup(){  
        //loads popup only if it is disabled  
        if(popupStatus==0){  
            $("#backgroundPopup").css({  
                "opacity": "0.7"  
            });  
            $("#backgroundPopup").fadeIn("slow");  
            $("#popupDeleteConfirm").fadeIn("slow");  
            popupStatus = 1;  
        }  
    }
//    $("#popupClose").click(function(){  
//        disablePopup();  
//    });  
//    //Click out event!  
//    $("#backgroundPopup").click(function(){  
//        disablePopup();  
//    });  
//    $('#delete_cancel').click(function(){  
//        disablePopup();  
//    });
    //disabling popup with jQuery magic!  
    function disablePopup(){  
        //disables popup only if it is enabled  
        if(popupStatus==1){  
            $("#backgroundPopup").fadeOut("slow");  
            $("#popupDeleteConfirm").fadeOut("slow");  
            popupStatus = 0;  
        }  
    }
</script>

