$(function() {
    document.addEventListener('click', (e) => {
      if(!e.target.closest('.menu_btn')) {
        if(!$('.menu_btn').hasClass('active'))return;
          $('.headerUL').toggleClass('is-active');
          $('.menu_btn').toggleClass('active');
      } else {
          $('.headerUL').toggleClass('is-active');
          $('.menu_btn').toggleClass('active');
      }
    })
});