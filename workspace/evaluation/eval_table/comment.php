
<fieldset>
<table border="0" cellpadding="0" cellspacing="0" class="mainContactForm">
<tr><td colspan='2'></td></tr>
<tr>
    <td align="right">Additional Comment Box*: </td>
    <td>
    <!--Recommend link
    http://www.mediacollege.com/internet/javascript/form/limit-characters.html
    -->
    <TEXTAREA id="comments" name="comments" COLS=80 ROWS=2 maxlength="500"><?php 
    $comments = $eval->getProperty('comments');
    echo $comments;?></TEXTAREA>
    <br />Maximum characters: 500<br />
    You have <input type="text" id="countdown" size="3" value="500"/> characters left.
    </td>
</tr>
</table>
</fieldset>

<script>

function setLeftCharsNum(selector){
     var cbox = $(selector);
        var val = cbox.val();
        var length = val.length;
        var maxlength = cbox.attr('maxlength');
        if(length > maxlength){
            cbox.val(cbox.val().substring(0,maxlength));
        }else{
            var left = maxlength-length;
            $('#countdown').val(left);
        }
}
setLeftCharsNum('#comments');
$('#comments').keyup(function(){
   setLeftCharsNum('#comments')
});
</script>