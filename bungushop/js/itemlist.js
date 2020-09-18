// jQuery(ソートプルダウン・自動submit)
$(function(){
    $("#sort_select").change(function(){
        $("#sort_form").submit();
    });
});