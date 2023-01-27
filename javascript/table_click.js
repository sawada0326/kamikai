$(function() {
    //trをクリックするとページ遷移する
    $('tr[data-href]').addClass('click_table')
      .click(function(e) {  
        if(!$(e.target).is('a')){
          window.location = $(e.target).closest('tr').data('href');}
    });
});









