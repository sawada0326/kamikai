$(function(){
    itemcount = $('.card').length;
    console.log(itemcount);
    if(itemcount < 3) {
        $(".cards").css('animation','none');
    }
});
