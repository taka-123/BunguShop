// jQuery
$(function(){
    // ソートプルダウン・自動submit
    $("#sort_select").change(function(){
        $("#sort_form").submit();
    });

    $(function () {
        $('.productImg').hover(function() {
          $(this).next('p').show();
        }, function(){
          $(this).next('p').hide();
        });
    });

    let i = 1;
    while(i <= 10) {
        const amountOp = `<option value=${i}>${i}個</option>`;
        $('.add_amount').append(amountOp);
        i ++;
    }
    i = 1;
});
