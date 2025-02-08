$(document).ready(function(){

  // UPDATE FILE NAME
  $('body').on('change', '#vrt-upload-form input[name="attachment"]', function() {
    if( $(this)[0].files[0]['name'] != '' ) {
      $('#vrt-upload-form .attachment .att-box .wrap > span').text( $(this)[0].files[0]['name'] );
    }
  });


  // USER ACCOUNT TABS FUNCTIONALITY
  $('body').on('click', '#vrt-tab-menu > a', function(e){
    e.preventDefault();
    var tabId = $(this).attr('data-tab');
    $('#vrt-tab-menu > a').removeClass('vrt-active');
    $(this).addClass('vrt-active');
    $('div.vrt-tab').removeClass('vrt-active').hide(0);
    $('div.vrt-tab[data-tab="' + tabId + '"]').addClass('vrt-active').show(0);
  });

});