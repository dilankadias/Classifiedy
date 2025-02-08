$(document).ready(function() {
  // INITIAL SETUP
  zetLazyLoadImages();
  zetManageScroll();
  zetManageScrollEnd();
  zetShowUsefulScrollButtons();


  // ON RESIZE SHOW OR HIDE NICE SCROLL BUTTONS
  $(window).on('resize', function(){
    zetShowUsefulScrollButtons();
  });


  // SIMPLE LINK CHECKBOX FUNCTIONALITY
  $('body').on('click', '.simple-list .link-check-box > a', function(e) {
    $(this).siblings('a').removeClass('active');
    $(this).addClass('active');
    
    var id = $(this).attr('data-val');
    $(this).closest('.simple-list').find('input.input-hidden').val(id); //.change();    
  });


  // TABS ON HOME PAGE
  $('body').on('click', '#main-search .tabs a', function(e) {
    e.preventDefault();
    
    if(!$(this).hasClass('active')) {
      var id = $(this).attr('data-id');
      
      $('#main-search .tabs a, #main-search .tab-data').removeClass('active');
      $(this).addClass('active');
      $('#main-search .tab-data[data-id="' + id + '"]').addClass('active');
    }
  });
  
  
  // SHOW MORE SUBCATEGORIES
  $('body').on('click', '.cat-list a.show-more', function(e) {
    e.preventDefault();
    $(this).hide(0);
    $(this).siblings('.hide').fadeIn(200).css('display', 'flex');
  });
  
  
  // SHOW SHOWCASE BOX ON MOBILE
  $('body').on('click', '#showcase-button', function(e) {
    e.preventDefault();
    $(this).toggleClass('active');
    $('#showcase-box').slideToggle(200);
  });


  // CLEAN ALL RECENTLY VIEWED
  $('body').on('click', '.clear-recently-viewed', function(e) {
    e.preventDefault();
    $(this).closest('.recent-ads').slideUp(200, function() {
      $(this).remove();
    });
    
    
    $.ajax({
      type: 'GET',
      url: baseAjaxUrl + '&ajaxCleanRecentlyViewedAll=1',
      success: function(data) {
        //console.log(data);
      }
    });
  });
  
  
  // CLEAN LOCATION IF CHANGED IN LOCATION BOX
  $('body').on('keyup change', 'form[name="item"] .picker.location input[name="sLocation"], form.profile .picker.location input[name="sLocation"]', function() {
    var form = $(this).closest('form');
    form.find('input[name="countryId"], input[name="regionId"], input[name="cityId"]').val('');
  });
  
  
  // CHECK IF HAS SHOWCASE
  if($('#showcase-box').length) {
    $('body').addClass('demo');
  }
  

  // SEARCH FREE PRICE CHECKBOX
  $('body').on('change', '.filter-menu input[name="bPriceCheckWithSeller"], .filter-menu input[name="bPriceFree"]', function(e) {
    e.preventDefault();
    var form = $(this).closest('form');
    
    if($(this).is(':checked')) {
      //form.find('input[name="sPriceMin"], input[name="sPriceMax"]').val('').attr('disabled', true);
      form.find('input[name="sPriceMin"], input[name="sPriceMax"]').val('');
      form.find('input[name="bPriceFree"], input[name="bPriceCheckWithSeller"]').not(this).prop('checked', false);
    } else {
      //form.find('input[name="sPriceMin"], input[name="sPriceMax"]').val('').attr('disabled', false);
      form.find('input[name="sPriceMin"], input[name="sPriceMax"]').val('');
    }
    
    $(form).find('input.ajaxRun').change();
  });

  
  // UPDATE PRICE CHECKBOX IN SEARCH BOX IF PRICE IS SET
  $('body').on('click keyup change', '.filter-menu input[name="sPriceMin"], .filter-menu input[name="sPriceMax"]', function(e) {
    var form = $(this).closest('form');
    
    if($(this).val() != '') {
      form.find('input[name="bPriceFree"], input[name="bPriceCheckWithSeller"]').prop('checked', false);
    }
  });


  // SHOW-HIDE SCROLL TO TOP
  $(window).on('scroll', function(){
    if($(document).scrollTop() > 720) {
      $('#scroll-to-top').fadeIn(200);
    } else {
      $('#scroll-to-top').fadeOut(200);
    }  
  });


  // ITEM RATING
  $('body').on('click', '.is-rating-item', function(e) {
    e.preventDefault();
    $('input[name="rating"]').val($(this).attr('data-value'));
    $('.comment-rating-selected').text('(' + $(this).attr('data-value') + ' of 5)');
    $(this).parent().find('i.is-rating-item').addClass('fill');
    $(this).nextAll('i.is-rating-item').removeClass('fill');
  })


  // SWITCH LIGHT DARK MODE
  $('body').on('click', 'a.switch-light-dark-mode', function(e) {
    e.preventDefault();
    var mode = $('html').attr('mode');
    var newMode = '';
    var newText = '';
    
    if(mode == 'light') {
      newMode = 'dark';
      newText = $(this).attr('data-label-light');
    } else {
      newMode = 'light';
      newText = $(this).attr('data-label-dark');
    }
    
    $('html').attr('mode', newMode);
    $('a.switch-light-dark-mode').find('> span').text(newText);     // Not $(this) to update all the buttons
    
    $.ajax({
      type: 'GET',
      url: baseAjaxUrl + '&ajaxLightDarkMode=' + newMode,
      success: function(data) {
        //console.log(data);
      }
    });
  });
  

  // PRINT ITEM
  $('body').on('click', 'a.print', function(e){
    e.preventDefault();
    $('body').addClass('print');
    $('.phone, #item-main .description .read-more-desc').click();
    $(window).scrollTop(0);
    window.print();
    $('body').removeClass('print');
  });
  

  // SCROLL TO TOP
  $('body').on('click', '#scroll-to-top', function(e) {
    e.preventDefault();
    $('html, body').animate({scrollTop: 0}, 500);
  });
  
  
  // FANCYBOX - OPEN ITEM FORM (COMMENT / SEND FRIEND / PUBLIC CONTACT / SELLER CONTACT)
  $('body').on('click', '.open-form', function(e) {
    e.preventDefault();
    var height = 600;
    var url = $(this).attr('href');
    var formType = $(this).attr('data-type');

    if(url.indexOf('loginRequired') !== -1) {
      window.location.href = url;
      return;
    }
    
    if(formType == 'comment') {
      height = (userLogged == 1 ? 465 : 625);
      height += ($(this).hasClass('has-rating') ? 55 : 0);
    } else if(formType == 'comment-reply') {
      height = (userLogged == 1 ? 535 : 625);
      height += ($(this).hasClass('has-rating') ? 55 : 0);
    } else if(formType == 'contact') {
      height = (userLogged == 1 ? 465 : 625);
    } else if(formType == 'friend') {
      height = (userLogged == 1 ? 535 : 685);
    } else if(formType == 'contact_public') {
      height = (userLogged == 1 ? 465 : 625);
    }
    
    zetModal({
      width: 480,
      height: height,
      content: url, 
      wrapClass: 'item-extra-form',
      closeBtn: true, 
      iframe: true, 
      fullscreen: 'mobile',
      transition: 200,
      delay: 0,
      lockScroll: true
    });
  });


  // OPEN REPORT BOX
  $('body').on('click', '.report-button', function(e) {
    e.preventDefault();

    zetModal({
      width: 420,
      height: 490,
      content: $('.report-wrap').html(), 
      wrapClass: 'report',
      closeBtn: true, 
      iframe: false, 
      fullscreen: 'mobile',
      transition: 200,
      delay: 0,
      lockScroll: true
    });
  });


  // SHOW FULL ITEM DESCRIPTION
  $('body').on('click', 'a.read-more-desc', function(e) { 
    e.preventDefault();
    var box = $(this).closest('.description');
    
    $(this).hide(0);
    box.find('.text.visible').hide(0);
    box.find('.text.hidden').show(0);
  });
  
  
  // LIGHTBOX GALLERY
  if(typeof $.fn.lightGallery !== 'undefined') {
    $('#item-image .swiper-container').lightGallery({
      mode: 'lg-slide',
      thumbnail: true,
      cssEasing : 'cubic-bezier(0.25, 0, 0.25, 1)',
      selector: 'li > a',
      getCaptionFromTitleOrAlt: true,
      download: false,
      thumbWidth: 90,
      thumbContHeight: 80,
      share: false
    }); 
  }
  
  
  // OPEN ITEM IMAGE GALLERY WHEN CLICK ON GALLERY LINK
  $('body').on('click', '#item-image .mlink.gallery', function(e) {
    e.preventDefault();
    $("#item-image .swiper-container li:first-child a > img").trigger("click");
  });


  // WHEN LIGHTBOX IS LOADED, MAKE SURE LAZYLOAD DOES NOT BLOCK THUMBNAILS
  var urlHash = window.location.hash;
  
  if(urlHash !== '' && urlHash.startsWith("#lg")) {
    setTimeout(function() {
      zetFixImgSources();
    }, 600);
  }


  // OPEN CONTACT SELLER BOX BASED ON HASH
  if(urlHash !== '' && urlHash.startsWith("#contact") && $('#item-side .master-button').length) {
    $('#item-side .master-button').click();
  }
  
  $('body').on('click', '#item-image li > a', function(){
    zetFixImgSources();
  });

  
  // SWIPER INITIATE
  if(typeof(Swiper) !== 'undefined') { 
    var swpSlidePerView = 3;
    
    if(($(window).width() + scrollCompensate()) < 768) {
      swpSlidePerView = 1;
    } else if(($(window).width() + scrollCompensate()) < 1200) {
      swpSlidePerView = 2;
    }
    
    var swiper = new Swiper(".swiper-container", {
      //loop: true,
      slideClass: "swiper-slide",
      slidesPerView: swpSlidePerView,
      spaceBetween: 4,  // px
      navigation: {
        nextEl: ".swiper-next",
        prevEl: ".swiper-prev",
      },
      pagination: {
        el: ".swiper-pg",
        dynamicBullets: true,
        dynamicMainBullets: 1
      },
      on: {
        afterInit: function() {
        },
        activeIndexChange: function(swp) {
          zetLazyLoadImages('item-gallery');

          $('.swiper-thumbs li').removeClass('active');
          $('.swiper-thumbs li[data-id="' + swp.activeIndex + '"]').addClass('active');
        }
      }
    });
  }

  
  // NICE SCROLL - ACTION BUTTONS
  $('body').on('click', '.nice-scroll-next, .nice-scroll-prev', function(e) {
    e.preventDefault();
    
    if($(this).hasClass('scrolling')) {
      return false;
    }
    
    var scrollCards = 4;
    var btn = $(this);
    var elem = $(this).siblings('.nice-scroll');
    var pos = elem.scrollLeft();
    var len = elem.find(' > *').width() + parseFloat((elem.find(' > *').css('margin-left')).replace('px', '')) +  + parseFloat((elem.find(' > *').css('margin-right')).replace('px', ''));

    $(this).addClass('scrolling');
    
    ($(window).width() - 10 < scrollCards*len ? scrollCards = 3 : '');
    ($(window).width() - 10 < scrollCards*len ? scrollCards = 2 : '');
    ($(window).width() - 10 < scrollCards*len ? scrollCards = 1 : '');
  
    len = len*scrollCards;
    
    if($(this).hasClass('nice-scroll-prev')) {
      len = -len;
    }
    
    elem.stop(false,false).animate({scrollLeft: pos + len}, 100 + scrollCards*80, function() {
      btn.removeClass('scrolling');
    });
  });
  
  
  if(ajaxSearch == '1') {
    // AJAX SEARCH - initialize change events
    $('body#search').on('change', '.sort-type select, form.search-side-form input, form.search-side-form select', function(event) {
      zetAjaxSearch($(this), event);
    });
    
    // AJAX SEARCH - initialize keyp events
    // $('body#search').on('keyup', 'form.search-side-form input[name="sPattern"]', function(event) {
      // zetAjaxSearch($(this), event);
    // });

    // AJAX SEARCH - initialize click events
    $('body#search').on('click', '.breadcrumb a, #search-quick-bar .simple-sort a.option, #search-category-box a, #search-filters a, #search-cat a, #filter-user-type a, #filters-remove a, #latest-search a, .paginate a, a.reset, .paginate-alt a, form.search-side-form .link-check-box a', function(event) {
      zetAjaxSearch($(this), event);
      return false;
    });
  }
  
  // MASKED ELEMENT UNHIDE
  $('body').on('click', '.masked', function(e) {
    if(!$(this).hasClass('revealed')) {
      e.preventDefault();
      
      if($(this).find('.help').length) {
        $(this).find('.help').remove();
      }
      
      var text = String($(this).attr('data-part1')) + String($(this).attr('data-part2'));

      $(this).attr('href', $(this).attr('data-prefix')+ ':' + text).attr('title', '').addClass('revealed');
      $(this).find('span').text(text);
    }
  });

  
  // SHOW HEADER QUICK INFOBAR ON MOBILE
  if($('body#item').length) {
    $(window).on('scroll', function(){
      if(zetIsMobile()) {
        var elem = $('header .container.alt.citem');
        
        if($(this).scrollTop() > 400) {
          elem.hasClass('hidden') ? elem.removeClass('hidden').stop(true,true).slideDown(200) : '';
        } else {
          !elem.hasClass('hidden') ? elem.addClass('hidden').stop(true,true).slideUp(200) : '';
        }
      }
    });
  }
  
  
  // DYNAMICALLY GENERATED LABELS ON MOBILE DOES NOT WORK
  $('body').on('click', '#side-menu .filter-menu .input-box-check label', function(e) {
    if(($(window).width() + scrollCompensate()) < 768) {
      e.preventDefault();
      var checkbox = $(this).siblings('input[type="checkbox"]');
      checkbox.length ? checkbox.prop('checked', !checkbox.prop('checked')).change() : '';
    }
  });
  

  $('body').on('change', '#search-quick-bar .sort-type select', function(e) {
    if(ajaxSearch != 1) {
      e.preventDefault();
      window.location.href = $(this).find(':selected').attr('data-link');
      return false;
    }
  });
  

  // OPEN SEARCH FILTERS ON MOBILE
  $('body').on('click', '#open-search-filters, .action.open-filters, #filter-line .all-filters, #navi-bar a.all-filters', function(e) {
    e.preventDefault();
    height = Math.min(Math.max(Math.round($(window).height()*0.8), 640), 1200);
    
    zetModal({
      width: 540,
      height: height,
      content: $('.filter-menu').html(), 
      wrapClass: 'filter-menu-modal',
      closeBtn: true, 
      iframe: false, 
      fullscreen: 'mobile',
      transition: 200,
      delay: 0,
      lockScroll: true
    });
  });
  
  
  // OPEN CATEGORY MODAL BOX
  $('body').on('click', '.open-category-box', function(e) {
    e.preventDefault();
    $(this).closest('form').find('.cat-box').fadeIn(200);
    $(this).closest('.wrap').scrollTop(0).addClass('ohidden subbox-opened subbox-category');
    $(this).closest('#zetModal').addClass('subbox-opened subbox-category');
    $('.cat-box a.close-category-box').attr('data-id', $(this).closest('form').find('[name="sCategory"]').val());
    $(this).closest('form').find('[name="sCategory"]').val('');
  });


  // NAVIGATE IN CATEGORY MODAL BOX
  $('body').on('click', '.cat-box .cat-one a', function(e) {
    var id = $(this).attr('data-id');
    var form = $(this).closest('form');
    var one = $(this).closest('.cat-one');
    var box = $(this).closest('.cat-box');
    
    if(box.find('.cat-one[data-id="' + id + '"]').length) {
      // Subcategories found
      one.hide(0);
      form.find('[name="sCategory"]').val(id);    // no change here!
      box.find('.cat-one[data-id="' + id + '"]').show(0);
      
      // Update footer link "Select category"
      if(id == 0) {
        var txt = box.find('.cat-foot .confirm-category-box').attr('data-orig-text');
        box.find('.cat-foot .confirm-category-box').text(txt);
        
      } else {
        var txt = box.find('.cat-foot .confirm-category-box').attr('data-alt-text');
        box.find('.cat-foot .confirm-category-box').text(txt.replace('{CAT}', $(this).find('span').text()));
      }

    } else {
      // No subcategories, fill category input
      box.hide(0);
      form.find('[name="sCategory"]').val(id).change();
      $(this).closest('.wrap').removeClass('ohidden subbox-opened subbox-category');
      $(this).closest('#zetModal').removeClass('subbox-opened subbox-category');
    }
  });


  // CLOSE CATEGORY MODAL BOX FROM FOOTER
  $('body').on('click', '.close-category-box', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');
    id = (id > 0 ? id : '');
    
    $(this).closest('form').find('.cat-box').hide(0);
    $(this).closest('form').find('[name="sCategory"]').val(id);
    $(this).closest('.wrap').removeClass('ohidden subbox-opened subbox-category');
    $(this).closest('#zetModal').removeClass('subbox-opened subbox-category');
  });


  // SUBMIT CATEGORY MODAL BOX FROM FOOTER
  $('body').on('click', '.confirm-category-box', function(e) {
    e.preventDefault();
    $(this).closest('form').find('.cat-box').hide(0);
    $(this).closest('form').find('[name="sCategory"]').change();
    $(this).closest('.wrap').removeClass('ohidden subbox-opened subbox-category');
    $(this).closest('#zetModal').removeClass('subbox-opened subbox-category');
  });


  // OPEN LOCATION MODAL
  $('body').on('click', '.ssfrm input[name="sLocation"]', function(e) {
    if(zetIsMobile()) {
      $(this).closest('.row.lc').addClass('opened');
      $(this).closest('.wrap').scrollTop(0).addClass('ohidden subbox-opened subbox-location');
      $(this).closest('#zetModal').addClass('subbox-opened subbox-location');
    }
  });
  

  // CLOSE CATEGORY-LOCATION SUBBOX IN MODAL
  $('body').on('click', 'a.subbox-close', function(e) {
    e.preventDefault();
    var modal = $(this).closest('#zetModal');
    
    if(modal.hasClass('subbox-category')) {
      modal.find('.close-category-box').click();
    }
    
    modal.removeClass('subbox-opened subbox-location subbox-category');
    modal.find('.row.lc').removeClass('opened');
    modal.find('.wrap').removeClass('ohidden subbox-opened subbox-location subbox-category');
  });
  
  
  // OPEN ALERT BOX
  $('body').on('click', '.open-alert-box', function(e) {
    e.preventDefault();

    zetModal({
      width: 420,
      height: 300,
      content: $('.alert-box').html(), 
      wrapClass: 'alert-box-search',
      closeBtn: true, 
      iframe: false, 
      fullscreen: 'mobile',
      transition: 200,
      delay: 0,
      lockScroll: true
    });
  });
  
  
  // LIST, GRID, DETAIL VIEW TYPE SWITCH
  $('body').on('click', '#search-quick-bar .view-type a', function(e) { 
    e.preventDefault();
    var viewType = $(this).attr('data-view');

    if(!$(this).hasClass('active')) {

      $(this).closest('.view-type').find('a').removeClass('active');
      $(this).addClass('active');
      $('#search-items > .products').removeClass('list grid detail');
      $('#search-items > .products').addClass(viewType);
      
      $('input[name="sShowAs"]').val(viewType);

      // UPDATE CURRENT LINK
      var href = $(this).attr('href');

      if(href != '') {
        var newNaviUrl = href;
      } else {
        //var newUrl = baseDir + 'index.php?' + $('form.search-side-form :input[value!=""], form.search-side-form select, form.search-side-form textarea').serialize();
        var newNaviUrl = baseDir + "index.php?" + $('form.search-side-form').find(":input").filter(function () { return $.trim(this.value).length > 0}).serialize();
      }

      window.history.pushState(null, null, newNaviUrl);


      // UPDATE PAGINATION AND OTHER LINKS
      $('.paginate a, .user-type a, .sort-it a, #search-filters a, select.orderSelect option').each(function() {
        var type = (typeof $(this).attr('data-link') !== 'undefined' ? 'data-link' : 'href');
        var url = $(this).attr(type);

        if(!url.indexOf("index.php") >= 0 && url.match(/\/$/)) {
          url += (url.substr(-1) !== '/' ? '/' : '');
        }

        if(url.indexOf("sShowAs") >= 0) {
          url += (url.substr(-1) !== '/' ? '/' : '');
          var newUrl = url.replace(/(sShowAs,).*?(\/)/,'$1' + viewType + '$2').replace(/(sShowAs,).*?(\/)/,'$1' + viewType + '$2');

        } else {
          if(url.indexOf("index.php") >= 0) {
            var newUrl = url + '&sShowAs=' + viewType;
          } else {
            var newUrl = url + '?sShowAs=' + viewType;
          }
        }

        newUrl = (newUrl.substr(-1) == '/' ? newUrl.slice(0, -1) : newUrl);
        $(this).attr(type, newUrl);
      });
    }
  });
  
  
  // USER MENU HIGHLIGHT ACTIVE
  var url = window.location.toString();

  $('#user-menu a').each(function(){
    if(!$('#user-menu a.active').length) {
      var myHref = $(this).attr('href');

      if(url == myHref) {
        if(myHref.indexOf(url) >= 0)
        $(this).addClass('active');
        return;
      }
    }
  });


  // MOVE TO CHANGE EMAIL
  // SHOW TECHNICAL DETAILS ON ALERTS PAGE
  $('body').on('click', '.profile-box label a.change-email', function(e) {
    e.preventDefault();
    $('html, body').animate({
      scrollTop: $('.profile-box.change-mail').offset().top - 72
    }, 1000);
  });
  

  // SHOW TECHNICAL DETAILS ON ALERTS PAGE
  $('body').on('click', '.show-technical-details', function(e) {
    e.preventDefault();
    $(this).closest('.alert').find('.details').slideToggle(300); 
  });

  
  // DOUBLE ARROW PAGINATION FIX
  $('.paginate').each(function() {
    $(this).find('.searchPaginationNext').html('<i class="fas fa-angle-right"></i>');
    $(this).find('.searchPaginationPrev').html('<i class="fas fa-angle-left"></i>');
    $(this).find('.searchPaginationFirst').html('<i class="fas fa-step-backward"></i>');
    $(this).find('.searchPaginationLast').html('<i class="fas fa-step-forward"></i>');
  });


  // CLEAN INPUT WHEN CLEAN BUTTON CLICKED
  $('body').on('click', '.picker .clean', function(e) {
    e.preventDefault();
    var form = $(this).closest('form');

    $(this).closest('.input-box').find('input').val('').focus().change();
    //$(this).closest('form').find('.results').hide(0);
    $(this).hide(0);
    
    if($(this).closest('.input-box').hasClass('location')) {
      form.find('input[name="sCity"], input[name="sRegion"], input[name="sCountry"]').val('');
      
    } else if ($(this).closest('.input-box').hasClass('pattern')) {
      if($(this).closest('.input-box').hasClass('type-advanced')) {
        form.find('input[name="sPattern"], input[name="sCategory"]').val('');

      } else {
        form.find('input[name="sPattern"]').val('');
        
      }
    }
  });
  

  // CLEAN INPUT WHEN CLEAN BUTTON CLICKED
  $('body').on('click', '.input-box .clean', function(e) {
    e.preventDefault();
    $(this).siblings('input').val('').focus().change();
    $(this).hide(0);
  });
  
  
  // ADD CLEAN BUTTON
  $('body').on('click keyup focus', '.input-box input', function(e) {
    //if(ajaxSearch == 0 && $(this).closest('.input-box').find('.clean').length) {
    if($(this).closest('.input-box').find('.clean').length) {
      if($(this).val() != '') {
        $(this).closest('.input-box').find('.clean').fadeIn(100);
      } else {
        $(this).closest('.input-box').find('.clean').fadeOut(100);
      }
    }
  });


  // SHOW BANNERS
  $('body').on('click', 'a.show-banners', function(e) {
    e.preventDefault();
    $('.banner-theme#banner-theme.is-demo:not(.is-visible), .home-container.banner-box.is-demo:not(.is-visible)').stop(true, true).slideToggle(300);
    
    var newText = $(this).attr('data-alt-text');
    var oldText = $(this).text();
    
    $(this).attr('data-alt-text', oldText).text(newText);    
  });
  
  
  // ON FOUCS OUT INPUT BOX, HIDE CLEAN BUTTON WITH DELAY
  $('body').on('focusout', '.input-box input', function(e) {
    if($(this).closest('.input-box').find('.clean').length) {
      $(this).closest('.input-box').find('.clean').delay(100).fadeOut(100);
    }
  });
  
  
  // LATEST SEARCH TO INPUT BOX
  $('.latest-search a').click(function(e) {
    e.preventDefault();
    $('body#home .home-search input[name="sPattern"]').val($(this).attr('data-text'));    
  });
  
  
  // IDENTIFY IF ITEM DESC ON MOBILE SHOULD BE TRUNCATED
  if($('#item-main .details2 > .elem.ds').outerHeight() > 200) {
    $('#item-main .details2 .item-mob-desc').addClass('shorten');
  }
  
  
  // READ MORE DESCRIPTION ON MOBILE
  $('a.desc-show-more').click(function(e) {
    e.preventDefault();
    $(this).closest('.item-mob-desc').removeClass('shorten');    
  });


 // SIMPLE SELECT - CLICK ELEMENT FUNCTIONALITY
  $('body').on('click', '.simple-select:not(.disabled) .option:not(.info):not(.nonclickable)', function() {
    $(this).parent().parent().find('input.input-hidden').val($(this).attr('data-id')).change();
    
    if($(this).closest('.simple-sort').length) {
      $(this).parent().parent().find('.text strong.kind').html($(this).text());
    } else {
      $(this).parent().parent().find('.text span').html($(this).html());
    }
    
    $(this).parent().parent().find('.option').removeClass('selected');
    $(this).addClass('selected');
    $(this).parent().hide(0).removeClass('opened');

    $(this).closest('.simple-select').removeClass('opened');
    
    // Search - fitler line
    if($(this).closest('#filter-line').length) {
      var form = $('form.search-side-form');
      var inputName = $(this).parent().parent().find('input.input-hidden').attr('name');
      form.find('[name="' + inputName + '"]').val($(this).attr('data-id')).change();
    }
  });


  // TEXT/NUMBER INPUT OR SELECT CHANGE IN FILTER LINE
  $('body').on('change', '.simple-select .list.inputs input, .simple-select .list.inputs select, .filter-list select', function() {
    var form = $('form.search-side-form');
    var inputName = $(this).attr('name');
    form.find('[name="' + inputName + '"]').val($(this).val()).change();
  });
  

  // SIMPLE SELECT - OPEN MENU
  $('body').on('click', '.simple-select', function(e) {
    if(!$(this).hasClass('disabled') && !$(this).hasClass('opened') && !$(e.target).hasClass('option')) {
      $('.simple-select').not(this).removeClass('opened');

      $('.simple-select .list').hide(0);
      $(this).addClass('opened');
      $(this).find('.list').show(0);
    }
  });


  // SIMPLE SELECT - HIDE WHEN CLICK OUTSIDE
  $(document).mouseup(function(e){
    var container = $('.simple-select');

    if (!container.is(e.target) && container.has(e.target).length === 0) {
      $('.simple-select').removeClass('opened');
      $('.simple-select .list').hide(0);
    }
  });


  // SIMPLE SELECT - NONCLICKABLE, ADD TITLE
  $('.simple-select .option.nonclickable').attr('title', delTitleNc);


  // OPEN LOCATION SELETOR FROM HEADER MENU
  $('header .links .mini-btn.location, .defloc2.not-located, .btn.change-location, .nobtn.change-location').click(function(e) {
    if(!zetIsMobile()) {
      e.preventDefault();

      zetModal({
        width: 480,
        height: 640,
        content: '<div id="def-location" class="def-loc-box">' + $('#side-menu .box.location > .section').html() + '</div>', 
        wrapClass: 'location-select',
        closeBtn: true, 
        iframe: false, 
        fullscreen: 'mobile',
        transition: 200,
        delay: 0,
        lockScroll: true
      });
    }
  });
  
  
  // ITEM PUBLISH UPDATE LOCATION
  $('body').on('click', '.location-link .link-update.location, .change-location, .change-search-location', function(e) {
    if(!zetIsMobile()) {
      if(!$(e.target).hasClass('input-clean')) {
        e.preventDefault();

        if (($(window).width() + scrollCompensate()) >= 768) {
          $('header .links .btn.location').click();
        } else {
          $('#navi-bar a.location').click();
        }
      }
    }
  });
  

  // OPEN LOCATION BOX FROM BOTTOM NAV BAR
  $('#navi-bar a.location').on('click', function(e) {
    e.preventDefault();
    var menu = $('#side-menu');
    $('#menu-cover').fadeIn(200);
    $('#side-menu .box').hide(0);
    $('#side-menu .box.location').show(0);
    
    $('body').css('overflow', 'hidden').addClass('scroll-locked');
    menu.css({'margin-bottom': '-100px', 'opacity': 0, 'height': '85vh'}).show(0).stop(false,false).animate({'margin-bottom': 0, 'opacity': 1}, 300, 'linear');

    $('#side-menu').addClass('box-open');
  });


  // Prepare variables for touch events
  var tapBox;
  var tapBoxId = 0;
  var tapIsIframe = false;
  var tapStart = 0;
  var tapEnd = 0;
  var tapDiff = 0;
  var tapThreshold = 140;
  var tapIsScrolling = false;
  var tapElem;
  var tapScrollableElem;
  

  // On touch start, save position of cursor
  $(document).on('touchstart', '#side-menu, #zetModal.is-inline, #item-forms', function(e) {
    if(zetIsMobile()) {
      tapStart = e.originalEvent.targetTouches[0].screenY;        // pageY jumps on iFrame
      tapDiff = 0;
      tapBoxId = 0;
      tapBox = $(this);
      tapIsIframe = false;
      
      tapElem = $(e.originalEvent.targetTouches[0].target).closest('.wrap-scrollable, .nice-scroll');
      tapScrollableElem = $(e.originalEvent.targetTouches[0].target).closest('.wrap-scrollable, .nice-scroll');
      
      //console.log(e.originalEvent.targetTouches[0]);
      
      // Check if there is scrollable element
      tapIsScrolling = false;
      
      if(tapScrollableElem.length && !tapElem.hasClass('head') && !tapElem.hasClass('lead')) {
        if(tapScrollableElem[0].scrollHeight > tapScrollableElem.outerHeight() && tapScrollableElem.scrollTop() > 15) {
          tapIsScrolling = true;
        }
      }

      // Identify box if its modal with iframe
      if($(this).hasClass('is-iframe') || $(this).attr('id') == 'item-forms') {
        tapIsIframe = true;
        tapBoxId = $(window.frameElement, window.parent.document).attr('data-modal-id');
        tapBox = $('.modal-box[data-modal-id="' + tapBoxId + '"]', window.parent.document);
      }
    }
  });

  
  // On touch move, update box position. If threshold is reached, hide box
  $(document).on('touchmove', '#side-menu, #zetModal.is-inline, #item-forms', function(e) {
    if(zetIsMobile()) {
      tapEnd = e.originalEvent.targetTouches[0].screenY;        // pageY jumps on iFrame
      tapDiff = (tapEnd - tapStart);
      
      //console.log(tapEnd, tapStart, tapEnd - tapStart, tapDiff, tapIsScrolling);

      // Was not scrolling on init, but now is scrolling
      if(tapScrollableElem.length && tapIsScrolling == false && !tapElem.hasClass('head') && !tapElem.hasClass('lead')) {
        if(tapScrollableElem[0].scrollHeight > tapScrollableElem.outerHeight() && tapScrollableElem.scrollTop() > 15) {
          tapBox.stop(false, false).animate({'margin-bottom': 0}, 200, 'linear');
          tapIsScrolling = true;
        }
      }
      
      if(!tapIsScrolling) {

        // Just move position of box. At the end, it's going to be reset
        if(tapDiff > 0 && tapDiff <= tapThreshold) {
          tapBox.css('margin-bottom', -tapDiff + 'px');
        }

        // If threshold reached, hide box
        if(tapDiff > tapThreshold) {
          $('#menu-cover, .modal-cover').fadeOut(200);
          $('body').css('overflow', 'initial').removeClass('scroll-locked');

          if(tapIsIframe) {
            $('#menu-cover, .modal-cover', window.parent.document).fadeOut(200);
            $('body', window.parent.document).css('overflow', 'initial');
          }
          
          tapBox.addClass('closing');
          
          tapBox.stop(false, false).animate({'margin-bottom': -(tapDiff + 100) + 'px', 'opacity': 0}, 200, 'easeOutCubic', function() {
            tapBox.css({marginBottom: 0, opacity: 1}).hide(0);
            tapBox.removeClass('closing');
          });
        }
        
      } else {
        tapBox.css('margin-bottom', 0);
      }
    }
  });


  // On touch end, reset position of box
  $(document).on('touchend', '#side-menu, #zetModal.is-inline, #item-forms', function(e) {
    if(zetIsMobile()) {
      tapDiff = (tapEnd - tapStart);

      // If box was shifted, reset it
      if(tapDiff != 0) {
        if(!tapBox.hasClass('closing')) {
          tapBox.stop(false, false).animate({'margin-bottom': '0'}, 300, 'linear');
        }
      }
    }
  });
  

  // CLEAN LOCATION SEARCH FORM
  $('body').on('click', '.picker.v2 .input-clean', function(e) {
    e.preventDefault();
    $(this).closest('.row').slideUp(200);
    $(this).closest('form').find('input.loc-inp').remove();
  });
  
  
  // PATTERN PICKER LOADER KEYUP
  $('body').on('keyup click change', '.picker.pattern.v2 input', function(event) {
    var timeout = '';
    zetLoadPatternAdvanced($(this), event);
  });


  // HIDE PATTERN RESULTS ON OUTSIDE CLICK
  $(document).mouseup(function (e){
    var container = $('.picker.pattern');

    if(!container.is(e.target) && container.has(e.target).length === 0) {
      container.find('.results').hide(0);
      //container.find('.results .loaded').html('').hide(0);
    }
  });
  
  
  // PATTERN PICKER LOADER KEYUP
  $('body').on('keyup click change', '.picker.location.v2 input', function(event) {
    var timeout = '';
    zetLoadLocationsAdvanced($(this), event);
  });


  // HIDE LOCATION RESULTS ON OUTSIDE CLICK
  $(document).mouseup(function (e){
    var container = $('.picker.location.v2');

    if(!container.is(e.target) && container.has(e.target).length === 0) {
      container.find('.results').hide(0);
      //container.find('.results .loaded').html('').hide(0);
    }
  });


  // LOCATION LOADER KEYUP
  $('body').on('keyup click change', '.picker.location:not(.v2) input', function(event) {
    var timeout = '';

    if($(this).closest('.picker').hasClass('only-search')) {
      zetLoadLocationsSimple($(this), event);
    } else {
      zetLoadLocationsSimple($(this), event, 'COOKIE');
    }
  });
  

  // LOCATION LOADER KEYUP
  $('body').on('keyup click change', '.picker.category input', function(event) {
    var timeout = '';

    if(!$(this).closest('.picker').hasClass('create-link')) {
      zetLoadCategoriesSimple($(this), event);
    } else {
      zetLoadCategoriesSimple($(this), event, 'LINK');
    }
  });
  
  
  // LOCATION CLICK ON MOBILE IN MENU
  $('body').on('click', '#side-menu .box.location input[name="location-pick"]', function(e) {
    if(zetIsMobile()) {
      var input = $(this);
      
      if(input.closest('.wrap').find('.row.current').length) {
        var height = input.closest('.wrap').find('.row.current').outerHeight();
        input.closest('.section').scrollTop(height + 6);
      }
    }
  });


  // SELECT OPTION FROM LOCATION BOX
  $('body').on('click', '.picker.location .option, .option.standalone.defloc2', function(e) {
    
    // Remove opened class in search modal
    $(this).closest('.row.lc').removeClass('opened');
    var modal = $(this).closest('#zetModal');
    
    if(modal.length) {
      modal.removeClass('subbox-opened subbox-location subbox-category');
      modal.find('.row.lc').removeClass('opened');
      modal.find('.wrap').removeClass('ohidden subbox-opened subbox-location subbox-category');
    }
    
    
    // Get correct wrap
    if($(this).closest('#filter-line').length) {
      var form = $(this).closest('.simple-select');
    } else {
      var form = $(this).closest('form');
    }
    
    var isAllLoc = $(this).hasClass('all-loc');
    var isDefLoc = $(this).hasClass('defloc2');
    var input = $(this).closest('.picker').find('input[type="text"]');
    
    if($(this).closest('.picker').hasClass('v2')) {
      e.preventDefault();
    }

    if(isAllLoc == true) {
      form.find('input[name="sCity"], input[name="sRegion"], input[name="sCountry"]').val('');
      
    } if($(this).closest('.picker').hasClass('is-publish') || !$('body#search').length) {
      form.find('input[name="sCountry"], input[name="countryId"]').val($(this).attr('data-country'));
      form.find('input[name="sRegion"], input[name="regionId"]').val($(this).attr('data-region'));
      form.find('input[name="sCity"], input[name="cityId"]').val($(this).attr('data-city'));
      
    } else {
      if($(this).attr('data-city') != '' && typeof $(this).attr('data-city') !== 'undefined') {
        form.find('input[name="sCity"], input[name="cityId"]').val($(this).attr('data-city'));
        form.find('input[name="sRegion"], input[name="sCountry"]').val('');

      } else if ($(this).attr('data-region') != '' && typeof $(this).attr('data-region') !== 'undefined') {
        form.find('input[name="sRegion"], input[name="regionId"]').val($(this).attr('data-region'));
        form.find('input[name="sCity"], input[name="sCountry"]').val('');
        
      } else if ($(this).attr('data-country') != '' && typeof $(this).attr('data-country') !== 'undefined') {
        form.find('input[name="sCountry"], input[name="countryId"]').val($(this).attr('data-country'));
        form.find('input[name="sRegion"], input[name="sCity"]').val('');
      }
    }
    
    // Trigger change on publish page
    if($(this).closest('.picker').hasClass('is-publish')) {
      form.find('input[name="countryId"]').change();
    }

    // Update input with text
    if($(this).find('span').length) {
      var text = $(this).find('span').text();
    } else {
      var text = $(this).text();
    }
    
    // Remove everything after comma (format City, Region)
    if(text.indexOf(',') > -1) {
      text = text.split(',')[0];
    }

    // Remove everything before > (format Region > City)
    if(text.indexOf(' > ') > -1) {
      text = text.split(' > ').slice(-1);
    }
    
    // Update input value or just placeholder
    if(isAllLoc) {
      input.attr('placeholder', text);
    } else {
      input.val(text);
      input.attr('placeholder', input.attr('data-placeholder'));
    }
    
    
    // Close box based on placement - simple select in filters list or standard
    if($(this).closest('.simple-select').length) {
      $(this).closest('.simple-select').removeClass('opened');
      $(this).closest('.simple-select').find('> .list').hide(0);
      
      if(isDefLoc || isAllLoc) {
        $(this).closest('.results').find('> .default').show(0);
      }
    } else {
      $(this).closest('.results').hide(0);
    }

    // Search - fitler line - copy values
    if($(this).closest('#filter-line').length) {
      var form2 = $('form.search-side-form');
      
      if(isAllLoc) {
        form2.find('input[name="sCountry"], input[name="sRegion"], input[name="sCity"], input[name="sLocation"]').val('');
        form.find('input[name="sCountry"], input[name="sRegion"], input[name="sCity"], input[name="sLocation"]').val('');
        
      } else {
        form2.find('input[name="sCountry"]').val(form.find('input[name="sCountry"]').val());
        form2.find('input[name="sRegion"]').val(form.find('input[name="sRegion"]').val());
        form2.find('input[name="sCity"]').val(form.find('input[name="sCity"]').val());
        form2.find('input[name="sLocation"]').val(form.find('input[name="sLocation"]').val());
      }
      
      form2.find('input[name="sCity"]').change();
    }
    
    // Trigger change for ajax
    if($(this).closest('.search-side-form').length) {
      form.find('input[name="sCity"]').change();
    }
  });
  

  // SELECT OPTION FROM PATTERN BOX
  $('body').on('click', '.picker.pattern .option', function(e) {
    var form = $(this).closest('form');
    var input = $(this).closest('.picker').find('input[type="text"]');
    var isAdvanced = $(this).closest('.picker').hasClass('type-advanced');

    // Mobile header serach
    if($(this).closest('.alt.csearch').length) {
      // Do nothing, let redirect to link
      
    } else {
      //if($(this).closest('.picker').hasClass('v2') && ($(this).closest('.row').hasClass('patterns') || $(this).closest('.row').hasClass('searches'))) {
      e.preventDefault();

      // Get option text
      if($(this).find('span').length) {
        var text = $(this).find('span').text();
      } else {
        var text = $(this).text();
      }

      
      // It's pattern
      if($(this).closest('.row').hasClass('patterns') || $(this).closest('.row').hasClass('searches')) {
        input.val($(this).attr('data-pattern')).attr('placeholder', input.attr('data-placeholder'));
        
        if(isAdvanced == true) {
          form.find('input[name="sCategory"]').val('');
        }
        
      // It's category
      } else if ($(this).closest('.row').hasClass('categories')) {
        // Pick last level of category (For sale > Animals)
        if(text.indexOf(' > ') > -1) {
          text = text.split(' > ').slice(-1);
        }
      
        form.find('input[name="sCategory"]').val($(this).attr('data-category'));
        input.val('').attr('placeholder', text);
        // input.val(text);
      }
      
      // Hide results
      $(this).closest('.results').hide(0);

      // Trigger input change
      if($(this).closest('.search-side-form').length) {
        form.find('input[name="sPattern"]').change();
      }
    }
  });
  
  
  // SELECT OPTION FROM CATEGORY BOX
  $('body').on('click', '.picker.category .option', function(event) {
    var form = $(this).closest('form');

    if($(this).attr('data-category') != '') {
      form.find('input[name="catId"], input[name="sCategory"]').val($(this).attr('data-category'));
    }

    $(this).closest('.picker').find('input[type="text"]').val($(this).find('span').text());
    $(this).closest('.results').hide(0);

    if($(this).closest('.search-side-form').length) {
      form.find('input[name="sCategory"]').change();
    }
    
    if($(this).closest('form[name="item"]').length) {
      form.find('input[name="catId"]').change();
    }
  });
  

  // HIDE LOCATION RESULTS ON OUTSIDE CLICK
  $(document).mouseup(function (e){
    var container = $('.picker.location');

    if(!container.is(e.target) && container.has(e.target).length === 0) {
      container.find('.results').hide(0);
      //container.find('.results').html('').hide(0);
    }
  });
  
  
  // LOCATE USING GEOLOCATION
  $('body').on('click', '.navigator a.locate-me', function(e) {
    if(!$(this).hasClass('completed')) {
      e.preventDefault();
      zetGeoLocate($(this).find('span.status'));
    }
  });
  
  
  // OPEN SIDE MENU
  $('header a.menu').click(function(e) {
    e.preventDefault();
    var menu = $('#side-menu');
    $('#menu-cover').fadeIn(300);
    $('#side-menu').removeClass('box-open');
    $('#side-menu .box').hide(0);
    $('body').css('overflow', 'hidden').addClass('scroll-locked');

    menu.css({'margin-bottom': '-100px', 'opacity': 0, 'height': 'auto'}).stop(true,true).show(0).animate({'marginBottom': 0, 'opacity': 1}, 300, 'linear');
  });
  

  // CLOSE SIDE MENU
  $('#menu-cover, #side-menu svg.close').click(function(e) {
    e.preventDefault();
    var menu = $('#side-menu');
    $('#menu-cover').fadeOut(300);
    $('body').css('overflow', 'initial').removeClass('scroll-locked');
    
    menu.stop(true, true).animate({'margin-bottom': '-100px', 'opacity': 0}, 300, 'linear', function() {
      menu.css({marginBottom: 0, opacity: 1}).hide(0).css('height', 'auto');
    });
  });


  // SIDE MENU OPEN SUBSECTION
  $('#side-menu .open-box').click(function(e) {
    e.preventDefault();
    var boxId = $(this).attr('data-box');
    var box = $('#side-menu .box[data-box="' + boxId + '"]');

    box.show(0).css({'margin-bottom': '-100px', 'opacity': 0, 'height': '100%'}).stop(false,false).animate({marginBottom: 0, opacity: 1}, 300, 'linear');
    
    $('#side-menu').addClass('box-open');
    
    // Expand box for location data
    if($(this).hasClass('location')) {
      $('#side-menu').animate({height: '85vh'}, 300, 'easeOutCubic');
    }
  });


  // SIDE MENU SUBSECTION BACK BUTTON
  $('#side-menu .back').click(function(e) {
    e.preventDefault();
    if($(this).hasClass('close')) {
      $('#menu-cover').click();
      $(this).closest('.box').delay(300).hide(0);
      
    } else {
      var box = $(this).closest('.box');

      box.animate({'margin-bottom': '-100px', 'opacity': 0}, 300, 'linear', function() {
        box.css({'margin-bottom': 0, 'opacity': 1}).hide(0);
      });
        
      $('#side-menu').removeClass('box-open');
    }
  });
  
  
  // REMOVE EMPTY SECTIONS OF MOBILE SIDE MENU
  $('#side-menu > .section').each(function() {
    if(!$(this).find('a').length) {
      $(this).hide(0); 
    }
  });


  // SUBMIT PUBLISH FORM VIA MIDDLE BUTTON
  $('#navi-bar a.active.post').click(function(e) {
    if($('form[name="item"]').length) {
      e.preventDefault();
      $('form[name="item"]').submit();
    }
  });
  
  
  // MOBILE BACK BUTTON
  $('header .csearch a.back').click(function(e) {
    e.preventDefault();
    $(this).closest('.container').fadeOut(100);
  });


  // SHOW SEARCH BAR ON MOBILE
  $('header input.search-init').click(function(e) {
    e.preventDefault();
    var box = $('header .csearch');
    var input = box.find('input[type="text"]');
    box.fadeIn(100);
    
    var oldVal = input.val();
    input.val('').val(oldVal).focus().click();
  });


  // PUBLISH PAGE - SWITCH PRICE
  $('.item-publish .selection a').click(function(e) {
    e.preventDefault();
    var price = $(this).attr('data-price');

    $('.item-publish .selection a').removeClass('active');
    $(this).addClass('active');
    $('.item-publish .enter').addClass('disable');
    $('.item-publish .enter #price').val(price).attr('placeholder', '').attr('readonly', true);
  });

  $('.item-publish .enter .input-box').click(function(e) {
    if($(this).closest('.enter').hasClass('disable')) {
      $('.item-publish .selection a').removeClass('active');
      $(this).parent().removeClass('disable');
      $('.item-publish .enter #price').val('').attr('placeholder', '').attr('readonly', false);
    }
  });
  
  
  // SHOW HIDE PASSWORD
  $('.toggle-pass').click(function(e) {
    e.preventDefault();

    $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    var input = $(this).siblings('input');
    
    if (input.attr('type') == 'password') {
      input.prop('type', 'text');
    } else {
      input.prop('type', 'password');
    }
  });
});


// CUSTOM MODAL BOX
function zetModal(opt) {
  width = (typeof opt.width !== 'undefined' ? opt.width : 480);
  height = (typeof opt.height !== 'undefined' ? opt.height : 480);
  content = (typeof opt.content !== 'undefined' ? opt.content : '');
  wrapClass = (typeof opt.wrapClass !== 'undefined' ? ' ' + opt.wrapClass : '');
  closeBtn = (typeof opt.closeBtn !== 'undefined' ? opt.closeBtn : true);
  iframe = (typeof opt.iframe !== 'undefined' ? opt.iframe : true); 
  fullscreen = (typeof opt.fullscreen !== 'undefined' ? opt.fullscreen : false); 
  transition = (typeof opt.transition !== 'undefined' ? opt.transition : 300); 
  delay = (typeof opt.delay !== 'undefined' ? opt.delay : 0);
  lockScroll = (typeof opt.lockScroll !== 'undefined' ? opt.lockScroll : true); 

  var id = Math.floor(Math.random() * 100) + 10;
  width = zetAdjustModalSize(width, 'width') + 'px';
  height = zetAdjustModalSize(height, 'height') + 'px';

  var fullscreenClass = '';
  if(fullscreen === 'mobile') {
    if(($(window).width() + scrollCompensate()) < 768) {
      width = 'auto'; height = 'auto'; fullscreenClass = ' modal-fullscreen';
    }
    
  } else if (fullscreen === true) {
    width = 'auto'; height = 'auto'; fullscreenClass = ' modal-fullscreen';
  }

  var html = '';
  html += '<div class="modal-cover" data-modal-id="' + id + '" onclick="zetModalClose(\'' + id + '\');"></div>';
  html += '<div id="zetModal" class="modal-box' + wrapClass + fullscreenClass + (iframe === true ? ' is-iframe' : ' is-inline') + '" style="width:' + width + ';height:' + height + ';" data-modal-id="' + id + '">';

  if(closeBtn) {
    html += '<div class="modal-close-alt" onclick="zetModalClose(\'' + id + '\');"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="32px" height="32px"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" class=""></path></svg></div>';
  }
  
  html += '<div class="modal-inside">';
  
  if(closeBtn) {
    html += '<div class="modal-close" onclick="zetModalClose(\'' + id + '\');"><svg class="close" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="32px" height="32px"><path fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z" class=""></path></svg></div>';
  }
    
  html += '<div class="modal-body ' + (iframe === true ? 'modal-is-iframe' : 'modal-is-inline') + '">';
  
  if(iframe === true) {
    html += '<div class="modal-content nice-scroll"><iframe class="modal-iframe" data-modal-id="' + id + '" src="' + content + '"/></div>';
  } else {
    html += '<div class="modal-content nice-scroll">' + content + '</div>';
  }
  
  html += '</div>';
  html += '</div>';
  html += '</div>';
  
  if(lockScroll) {
    $('body').css('overflow', 'hidden').addClass('scroll-locked');
  }
  
  $('body').append(html);
  $('div[data-modal-id="' + id + '"].modal-cover').fadeIn(transition);
  
  if(($(window).width() + scrollCompensate()) < 768) {
    $('div[data-modal-id="' + id + '"].modal-box').delay(delay).show(0).css({'margin-bottom': '-100px', 'opacity': 0}).animate({marginBottom: 0, opacity: 1}, 300, 'linear');
  } else {
    $('div[data-modal-id="' + id + '"].modal-box').delay(delay).fadeIn(transition);
  }
}


// Close modal by clicking on close button
function zetModalClose(id = '', elem = null) {
  if(id == '') {
    id = $(elem).closest('.modal-box').attr('data-modal-id');
  }
  
  $('body').css('overflow', 'initial').removeClass('scroll-locked');
  
  if(($(window).width() + scrollCompensate()) < 768) {
    $('div[data-modal-id="' + id + '"].modal-cover').fadeOut(300);
    $('div[data-modal-id="' + id + '"].modal-box').css('margin-bottom', 0).stop(false,false).animate({marginBottom: '-100px', opacity: 0}, 300, 'linear', function(e) {
      $(this).remove(); 
    });
  } else {
    $('div[data-modal-id="' + id + '"]').fadeOut(200, function(e) {
      $(this).remove(); 
    });
  }
  
  return false;
}


// Close modal by some action inside iframe
function zetModalCloseParent() {
  var boxId = $(window.frameElement, window.parent.document).attr('data-modal-id');
  window.parent.zetModalClose(boxId);
}


// Calculate maximum width/height of modal in case original width/height is larger than window width/height
function zetAdjustModalSize(size, type = 'width') {
  var size = parseInt(size);
  var windowSize = (type == 'width' ? $(window).width() : $(window).height());
  
  if(size <= 0) {
    size = (type == 'width' ? 640 : 480);  
  }
  
  if(size*0.9 > windowSize) {
    size = windowSize*0.9;
  }
  
  return Math.floor(size);
}


// Update lazy images source when printing page
window.addEventListener('DOMContentLoaded', () => {
  var isPrinting = window.matchMedia('print');
  isPrinting.addListener((media) => {
    $('img.lazy').each(function() {
      $(this).attr('src', $(this).prop('data-src'));
    });
  })
});


// CHECK IF IS MOBILE DEVICE
function zetIsMobile() {
  if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
    return true;
  } else if(($(window).width() + scrollCompensate()) < 768) {
    return true;
  }
  
  return false;
}


// CALCULATE SCROLL WIDTH
function scrollCompensate() {
  var inner = document.createElement('p');
  inner.style.width = "100%";
  inner.style.height = "200px";

  var outer = document.createElement('div');
  outer.style.position = "absolute";
  outer.style.top = "0px";
  outer.style.left = "0px";
  outer.style.visibility = "hidden";
  outer.style.width = "200px";
  outer.style.height = "150px";
  outer.style.overflow = "hidden";
  outer.appendChild(inner);

  document.body.appendChild(outer);
  var w1 = inner.offsetWidth;
  outer.style.overflow = 'scroll';
  var w2 = inner.offsetWidth;
  if (w1 == w2) w2 = outer.clientWidth;

  document.body.removeChild(outer);

  return (w1 - w2);
}


// HTML5 GEOLOCATION
function zetGeoLocate(elem) {
  function success(position) {
    const latitude  = position.coords.latitude;
    const longitude = position.coords.longitude;
    
    $.ajax({
      type: 'GET',
      dataType: 'json',
      url: baseAjaxUrl + "&ajaxFindCity=1&latitude=" + latitude + "&longitude=" + longitude,
      success: function(data) {
        //console.log(data);
        //console.log(baseAjaxUrl + "&ajaxFindCity=1&latitude=" + latitude + "&longitude=" + longitude);
        
        if(data['success'] !== undefined) {
          if(data['success'] == true) {
            data['url'] = baseAjaxUrl + "&ajaxFindCity=1&latitude=" + latitude + "&longitude=" + longitude;
            elem.find('span').hide(0);
            elem.find('span.success').text(data['s_location']).show(0);

            if(!elem.closest('.navigator-fill-selects').length) {
              elem.find('span.refresh').show(0);
              elem.closest('a').attr('href', (window.location.href).replace('#', '')).text(elem.closest('a').attr('data-alt-text')).addClass('completed');
              elem.closest('a').find('strong').text(elem.closest('a').find('strong').attr('data-alt-text'));
            } else {
              zetGeoToSelects(elem, data);
            }
            
            return;
          }
        }
        
        elem.find('span').hide(0);
        elem.find('span.failed-unfound').show(0);
        return;
      },
      error: function(data) {
        console.log(data);
        elem.find('span').hide(0);
        elem.find('span.failed-unfound').show(0);
        return;
      }
    });
  }

  function error() {
    elem.find('span').hide(0);
    elem.find('span.failed').show(0);
  }

  if(!navigator.geolocation) {
    elem.find('span').hide(0);
    elem.find('span.not-supported').show(0);
  } else {
    elem.find('span').hide(0);
    elem.find('span.loading').show(0);

    navigator.geolocation.getCurrentPosition(success, error);
  }
}


// UPDATE LOCATION INPUTS
function zetGeoToSelects(elem, data) {
  var box = elem.closest('.navigator-fill-selects');
  var country = box.find('select[name="countryId"]');  
  var region = box.find('select[name="regionId"]');  
  var city = box.find('select[name="cityId"]');  
  
  if(country.length && data['fk_c_country_code'] !== undefined && data['fk_c_country_code'] != '') {
    country.val(data['fk_c_country_code']);
  }
  
  if(region.length && data['fk_i_region_id'] !== undefined && data['fk_i_region_id'] != '') {
    region.find('option').remove().end().append('<option value="' + data['fk_i_region_id'] + '">' + data['s_region'] + '</option>').val(data['fk_i_region_id']);
    region.attr('disabled', false);
  }

  if(city.length && data['fk_i_city_id'] !== undefined && data['fk_i_city_id'] != '') {
    city.find('option').remove().end().append('<option value="' + data['fk_i_city_id'] + '">' + data['s_city'] + '</option>').val(data['fk_i_city_id']);
    city.attr('disabled', false);
  }
}

var zetLoadLocationsSimpleTimeout = '';
var zetLoadLocationsSimpleValue = '';


// SIMPLE LOCATION LOADER
function zetLoadLocationsSimple(elem, event, type = '') {
  var min = 1;
  var box = elem.closest('.picker').find('.results');
  var term = $(elem).val().trim();

  term = (term.indexOf(',') > 1 ? term.substr(0, term.indexOf(',')) : term);
  term = (term.indexOf('-') > 1 ? term.substr(0, term.indexOf('-')) : term);
  term = encodeURIComponent(term).trim();


  if(zetLoadLocationsSimpleValue == term) {
    if(box.find('a, div').length) {
      box.show(0);
    }
    
    return false;
  } else {
    zetLoadLocationsSimpleValue = term; 
  }
  
  (typeof(zetLoadLocationsSimpleTimeout) !== undefined) ? clearTimeout(zetLoadLocationsSimpleTimeout) : ''; 
  (term.length > 0) ? elem.siblings('.clean').show(0) : elem.siblings('.clean').hide(0);
  (term != '' && term.length >= min) ? elem.closest('.picker').addClass('loading') : '';
  
  zetLoadLocationsSimpleTimeout = setTimeout(function() {
    if(term != '' && term.length >= min) {
      $.ajax({
        type: 'GET',
        url: baseAjaxUrl + '&ajaxLoc=1&dataType=' + type + '&term=' + term,
        success: function(data) {
          //console.log(data);
          
          elem.closest('.picker').removeClass('loading');
          box.html(data).show(0);
          box.find('fieldset').remove();   // DB debug
          
          if(box.find('a, div').length <= 0) {
            box.html('').hide(0);
          }
        },
        error: function(data) {
          elem.closest('.picker').removeClass('loading');
          box.html('').hide(0);
        }
      });
    } else {
      elem.closest('.picker').removeClass('loading');
      box.html('').hide(0); 
    }
  }, 300);
}


var zetLoadCategoriesSimpleTimeout = '';
var zetLoadCategoriesSimpleValue = '';

// SIMPLE CATEGORY LOADER
function zetLoadCategoriesSimple(elem, event, type = '') {
  var min = 1;
  var box = elem.closest('.picker').find('.results');
  var term = $(elem).val().trim();

  term = (term.indexOf(',') > 1 ? term.substr(0, term.indexOf(',')) : term);
  term = (term.indexOf('-') > 1 ? term.substr(0, term.indexOf('-')) : term);
  term = encodeURIComponent(term).trim();


  if(zetLoadCategoriesSimpleValue == term) {
    if(box.find('a, div').length) {
      box.show(0);
    }
    
    return false;
  } else {
    zetLoadCategoriesSimpleValue = term; 
  }
  
  (typeof(zetLoadCategoriesSimpleTimeout) !== undefined) ? clearTimeout(zetLoadCategoriesSimpleTimeout) : ''; 
  (term.length > 0) ? elem.siblings('.clean').show(0) : elem.siblings('.clean').hide(0);
  (term != '' && term.length >= min) ? elem.closest('.picker').addClass('loading') : '';
  
  zetLoadCategoriesSimpleTimeout = setTimeout(function() {
    if(term != '' && term.length >= min) {
      $.ajax({
        type: 'GET',
        url: baseAjaxUrl + '&ajaxCat=1&dataType=' + type + '&term=' + term,
        success: function(data) {
          //console.log(data);
          
          elem.closest('.picker').removeClass('loading');
          box.html(data).show(0);
          box.find('fieldset').remove();   // DB debug
          
          if(box.find('a, div').length <= 0) {
            box.html('').hide(0);
          }
        },
        error: function(data) {
          elem.closest('.picker').removeClass('loading');
          box.html('').hide(0);
        }
      });
    } else {
      elem.closest('.picker').removeClass('loading');
      box.html('').hide(0); 
    }
  }, 300);
}



var zetLoadPatternAdvancedTimeout = '';
var zetLoadPatternAdvancedValue = '';


// SIMPLE PATTERN LOADER
function zetLoadPatternAdvanced(elem, event) {
  var min = 1;
  var form = elem.closest('form');
  var picker = elem.closest('.picker');
  var type = picker.hasClass('type-advanced') ? 'advanced' : 'simple';
  var box = picker.find('.results');
  var boxLoaded = picker.find('.results .loaded');
  var boxDefault = picker.find('.results .default');
  var term = encodeURIComponent($(elem).val().trim());

  if(zetLoadPatternAdvancedValue == term) {
    if(term.length >= min) {
      box.show(0);
      boxLoaded.show(0);
      boxDefault.hide(0);
      
      if(boxLoaded.find('a, div').length <= 0) {
        box.hide(0);
      }
    } else {
      box.show(0);
      boxLoaded.hide(0);
      boxDefault.show(0);
    }
    
    return false;
  } else {
    zetLoadPatternAdvancedValue = term; 
  }

  (typeof(zetLoadPatternAdvancedTimeout) !== undefined) ? clearTimeout(zetLoadPatternAdvancedTimeout) : '';  
  (term.length > 0) ? elem.siblings('.clean').show(0) : elem.siblings('.clean').hide(0);
  (term != '' && term.length >= min) ? elem.closest('.picker').addClass('loading') : '';
  (term.length < min) ? boxDefault.find('.minlength span.min').text(min - term.length) : '';
  
  zetLoadPatternAdvancedTimeout = setTimeout(function() {
    if(term != '' && term.length >= min) {
      $.ajax({
        type: 'GET',
        url: baseAjaxUrl + '&ajaxPatternSearch=1&term=' + term + '&type=' + type,
        success: function(data) {
          //console.log(data);
          elem.closest('.picker').removeClass('loading');
          box.show(0);
          boxLoaded.html(data).show(0);
          boxLoaded.find('fieldset').remove();   // DB debug
          boxDefault.hide(0);
          
          if(boxLoaded.find('a, div').length <= 0) {
            box.hide(0);
            boxLoaded.html('').hide(0);
          }
        },
        error: function(data) {
          elem.closest('.picker').removeClass('loading');
          box.hide(0);
          boxLoaded.html('').hide(0);
        }
      });
    } else {
      elem.closest('.picker').removeClass('loading');
      box.show(0);
      boxDefault.show(0);
      boxLoaded.html('').hide(0); 
    }
  }, 300);
}




var zetLoadLocationsAdvancedTimeout = '';
var zetLoadLocationsAdvancedValue = '';


// SIMPLE PATTERN LOADER
function zetLoadLocationsAdvanced(elem, event) {
  var min = 1;
  var form = elem.closest('form');
  var picker = elem.closest('.picker');
  var box = picker.find('.results');
  var boxLoaded = picker.find('.results .loaded');
  var boxDefault = picker.find('.results .default');
  var term = encodeURIComponent($(elem).val().trim());

  // Min length might be hidden before, show it by default
  boxDefault.find('.row.minlength').show(0);

  // On new page load, when location was selected before, just show default content
  if(event.type == 'click' && zetLoadLocationsAdvancedValue == '' && term != '' && boxLoaded.is(':empty')) {
    box.show(0);
    boxLoaded.hide(0);
    boxDefault.show(0);
    boxDefault.find('.row.minlength').hide(0);
    
    return false;
    
  } else if(zetLoadLocationsAdvancedValue == term) {
    if(term.length >= min) {
      box.show(0);
      boxLoaded.show(0);
      boxDefault.hide(0);
      
      if(boxLoaded.find('a, div').length <= 0) {
        box.hide(0);
      }
    } else {
      box.show(0);
      boxLoaded.hide(0);
      boxDefault.show(0);
    }
    
    return false;
    
  } else {
    zetLoadLocationsAdvancedValue = term; 
  }

  (typeof(zetLoadLocationsAdvancedTimeout) !== undefined) ? clearTimeout(zetLoadLocationsAdvancedTimeout) : '';  
  (term.length > 0) ? elem.siblings('.clean').show(0) : elem.siblings('.clean').hide(0);
  (term != '' && term.length >= min) ? elem.closest('.picker').addClass('loading') : '';
  (term.length < min) ? boxDefault.find('.minlength span.min').text(min - term.length) : '';
  
  zetLoadLocationsAdvancedTimeout = setTimeout(function() {
    if(term != '' && term.length >= min) {
      $.ajax({
        type: 'GET',
        url: baseAjaxUrl + '&ajaxLocationSearch=1&term=' + term,
        success: function(data) {
          //console.log(data);
          elem.closest('.picker').removeClass('loading');
          box.show(0);
          boxLoaded.html(data).show(0);
          boxLoaded.find('fieldset').remove();   // DB debug
          boxDefault.hide(0);
          
          if(boxLoaded.find('a, div').length <= 0) {
            box.hide(0);
            boxLoaded.html('').hide(0);
          }
        },
        error: function(data) {
          elem.closest('.picker').removeClass('loading');
          box.hide(0);
          boxLoaded.html('').hide(0);
        }
      });
    } else {
      elem.closest('.picker').removeClass('loading');
      box.show(0);
      boxDefault.show(0);
      boxLoaded.html('').hide(0); 
    }
  }, 300);
}


var zetAjaxSearchTimeout = '';

// AJAX SEARCH - core function
function zetAjaxSearch(elem, event) {
  (typeof(zetAjaxSearchTimeout) !== undefined) ? clearTimeout(zetAjaxSearchTimeout) : '';  

  var delay = (event.type == 'keyup' ? 200 : 50);
  delay = ($(event.target).attr('name') == 'sPattern' ? 100 : delay);
  
  var scrollToTop = false;
  var ajaxStop = false;
  var ajaxSearchUrl = '';
  var sidebarReload = true;
  var modalReload = true;
  
  if(elem.closest('form.search-side-form').length) {
    var sidebar = elem.closest('form.search-side-form').last();
  } else {
    var sidebar = $('form.search-side-form').last();
  }


  // Breadcrumb home button
  if(elem.closest('li.first-child').length && elem.attr('href') == baseDir) {
    window.location.href = elem.attr('href');
    return false;
  }
  
  if($(event.target).attr('name') == 'sLocation') {
    ajaxStop = true;
    return false;
  }

  if(elem.attr('name') == 'sCity' || elem.attr('name') == 'sRegion' || elem.attr('name') == 'sCountry') {
    sidebarReload = true;
  } else if (elem.closest('.sidebar-hooks').length || elem.closest('.input-box-check').length || (elem.closest('.search-side-form').length && elem.attr('name') != 'sCategory') || event.type == 'keyup') {
    sidebarReload = false;
  }
  
  // These does not require modal reload
  // We must reload modal to get correct links and params!
  if(elem.closest('.link-check-box').length || ['sPattern','iRadius','iPriceMin','iPriceMax','sCompany','sLocation','sCountry','sRegion','sCity','sCityArea'].indexOf(elem.attr('name')) >= 0) {
    //modalReload = false;
  }

  // Make sure no sidebar reload for Car Attributes PRO inputs
  if(elem.closest('.cap-input-box').length) {
    sidebarReload = false;
  }

  if(elem.closest('.paginate').length || elem.closest('.paginate-alt').length || elem.closest('#latest-search').length) {
    scrollToTop = true;
  }
  
  if (event.type == 'click' && !elem.is('input:radio')) {
    if(typeof elem.attr('href') !== typeof undefined && elem.attr('href') !== false && elem.attr('href') != '') {
      ajaxSearchUrl = elem.attr('href');
    }
  } else if (event.type == 'change' || event.type == 'keyup' || elem.is('input:radio')) {
    if (elem.hasClass('orderSelect')) {
      ajaxSearchUrl = elem.find(':selected').attr('data-link');
    } else {
      ajaxSearchUrl = baseDir + "index.php?" + sidebar.find(":input").filter(function() { return $.trim(this.value).length > 0}).serialize();
    }
  }

  zetAjaxSearchTimeout = setTimeout(function() {
    if(ajaxSearch == 1 && $('input.ajaxRun').val() != 1 && (ajaxSearchUrl != '#' && ajaxSearchUrl != '') && ajaxStop !== true) {
      if(ajaxSearchUrl == $(location).attr('href')) {
        return false;
      }

      sidebar.find('.init-search').addClass('loading').addClass('disabled').attr('disabled', true);
      sidebar.find('input.ajaxRun').val(1);
      $('#search-main').addClass('loading');
      
      if(modalReload) {
        $('.modal-content form.search-side-form').addClass('loading');
      }

      $('#search-main .ajax-load-failed').hide(0);

      $.ajax({
        url: ajaxSearchUrl,
        type: "GET",
        timeout: 10000,
        success: function(response){
          var data = $(response).contents().find('#search-main').html();
          var bread = $(response).contents().find('ul.breadcrumb').html();
          var resetBtn = $(response).contents().find('.filter-menu .row.buttons .reset').html();
          var sideForm = $(response).contents().find('.filter-menu > .outer-wrap > form').html();

          sidebar.find('.init-search').removeClass('loading').removeClass('disabled').attr('disabled', false);
          sidebar.find('input.ajaxRun').val('');

          // Use #side-menu to not reload form content inside modal!!
          $('#search-main').removeClass('loading').html(data);
          $('#search-menu.filter-menu form.search-side-form').html(sideForm);


          if(modalReload) {
            $('.modal-content form.search-side-form').removeClass('loading').html(sideForm);
          } else {
            $('.modal-content form.search-side-form .row.buttons .reset').html(resetBtn);
          }
          

          $('ul.breadcrumb').html(bread);
          
          zetLazyLoadImages('search-items');
          zetLazyLoadImages('search-premium-items');
          zetManageScroll();
          zetManageScrollEnd();
          zetShowUsefulScrollButtons();

          // Update URL
          var ajaxSearchUrlCleaned = baseDir + "index.php?" + $('.filter-menu form.search-side-form').find(":input").filter(function () { return $.trim(this.value).length > 0}).serialize();
          window.history.pushState(null, null, ajaxSearchUrl);
          
          //(scrollToTop ? $(window).scrollTop(0) : '');
          (scrollToTop ? $(window).scrollTop($('#search-quick-bar').offset().top - parseInt($('header').height()) - parseInt($('#header-search').height()) + 2) : '');
        },

        error: function(response){
          // There was some problem
          console.log(response);
          
          sidebar.find('.init-search').removeClass('loading').removeClass('disabled').attr('disabled', false);
          sidebar.find('input.ajaxRun').val('');

          $('#search-main, form.search-side-form').removeClass('loading');
          $('#search-main .ajax-load-failed').show(0).css('display', 'flex');
        }
      });

      if(!elem.is('input:radio')) {
        return false;
      }
    }
  }, delay);
}



// Lazyload images
function zetLazyLoadImages(type = '') {
  if(zetLazy != "1" ) {
    return false;
  }
  
  // standard initialization
  if(type == '' || type == 'basic') {
    $('img.lazy').Lazy({
      appendScroll: window,
      scrollDirection: 'both',
      effect: 'fadeIn',
      effectTime: 100,
      afterLoad: function(element) {
        element.addClass('loaded');
        setTimeout(function() {
          element.css('transition', '0.2s');
        }, 100);
      }
    });
  }
  
  // search items
  if(type == 'search-items') {
    $('#search-items img.lazy').Lazy({
      appendScroll: window,
      scrollDirection: 'both',
      effect: 'fadeIn',
      effectTime: 100,
      afterLoad: function(element) {
        element.addClass('loaded');
        setTimeout(function() {
          element.css('transition', '0.2s');
        }, 100);
      }
    });
  }
  
  if(type == '' || type == 'search-premium-items') {
    $('#search-premium-items img.lazy').Lazy({
      appendScroll: window,
      scrollDirection: 'both',
      effect: 'fadeIn',
      effectTime: 100,
      afterLoad: function(element) {
        element.addClass('loaded');
        setTimeout(function() {
          element.css('transition', '0.2s');
        }, 100);
      }
    });
    
    $('#search-premium-items img.lazy').Lazy({
      appendScroll: '.nice-scroll, .mobile-scroll',
      scrollDirection: 'both',
      effect: 'fadeIn',
      effectTime: 100,
      afterLoad: function(element) {
        element.addClass('loaded');
        setTimeout(function() {
          element.css('transition', '0.2s');
        }, 100);
      }
    });
    
    if(type == 'search-premium-items') {
      $('#search-premium-items .nice-scroll').scrollLeft(1).delay(10).scrollLeft(0);
    }
  }
  
  
  // initialization in nice-scroll slider
  if(type == '') {
    $('.nice-scroll img.lazy, .mobile-scroll img.lazy').Lazy({
      appendScroll: '.nice-scroll, .mobile-scroll',
      scrollDirection: 'both',
      effect: 'fadeIn',
      effectTime: 100,
      afterLoad: function(element) {
        element.addClass('loaded');
        setTimeout(function() {
          element.css('transition', '0.2s');
        }, 100);
      }
    });
  }
  
  // item gallery swiper move
  if(type == 'item-gallery') {
    $('#item-image li a img.lazy').Lazy({
      appendScroll: '.swiper-wrapper, .swiper-container',
      scrollDirection: 'both',
      effect: 'fadeIn',
      effectTime: 100,
      afterLoad: function(element) {
        element.addClass('loaded');
        setTimeout(function() {
          element.css('transition', '0.2s');
        }, 100);
      }
    });
  }
}


// Fix lazyload large pictures when using thumbnails
function zetfixImgSourcesThumb() {
  $('#item-image li img').each(function() {
    if(typeof $(this).attr('data-src') !== 'undefined') {
      $(this).attr('src', $(this).attr('data-src'));
    }
  });
}


// Fix lazyload thumbnails for light gallery
function zetFixImgSources() {
  $('#item-image li img').each(function() {
    if(typeof $(this).attr('data-src') !== 'undefined') {
      var imgDataSrc = $(this).attr('data-src');
    } else {
      var imgDataSrc = $(this).attr('src');
    }
    
    if(typeof imgDataSrc !== 'undefined') {
      var index = $(this).closest('li').index();
      $('.lg-thumb .lg-thumb-item').eq(index).find('img').attr('src', imgDataSrc);
    }
  });
}


// NICE SCROLL - MANAGE FADERS
function zetManageScroll() {
  $('.nice-scroll').on('scroll', function(e) {
    var box = $(this);
    var scrollLeft = (isRtl ? -1 : 1) * box.scrollLeft();
    var padding = parseFloat((box.css('padding-left')).replace('px', '')) + parseFloat((box.css('padding-right')).replace('px', ''));
    var maxScroll = box.prop('scrollWidth') - scrollLeft - box.width() - padding;
    var prev = box.siblings('.nice-scroll-prev');
    var next = box.siblings('.nice-scroll-next');

    if (scrollLeft < 20) {
      //(isRtl ? next.fadeOut(100) : prev.fadeOut(100));
      (isRtl ? next.addClass('disabled') : prev.addClass('disabled'));
    } else {
      //(isRtl ? next.fadeIn(100) : prev.fadeIn(100));
      (isRtl ? next.removeClass('disabled').show(100) : prev.removeClass('disabled').show(100));
    }

    if (maxScroll < 20) {
      //(isRtl ? prev.fadeOut(100) : next.fadeOut(100));
      (isRtl ? prev.addClass('disabled') : next.addClass('disabled'));
    } else {
      //(isRtl ? prev.fadeIn(100) : next.fadeIn(100));
      (isRtl ? prev.removeClass('disabled').show(100) : next.removeClass('disabled').show(100));
    }
  });
}


// AT THE END OF SCOLL EVENT, CENTER SCROLL TO CLOSEST ELEMENT
function zetManageScrollEnd() {
  $('.nice-scroll').on('scrollend', {delay: 100, initialLoadDelay: 100}, function(e) {
    if(($(this).hasClass('products') || $(this).hasClass('manage-scrollend')) && !$(this).hasClass('no-scrollend')) {
      var box = $(this);
      var scrollLeft = (isRtl ? -1 : 1) * box.scrollLeft();

      var padding = parseFloat((box.css('padding-left')).replace('px', '')) + parseFloat((box.css('padding-right')).replace('px', ''));
      var maxScroll = box.prop('scrollWidth') - scrollLeft - box.width() - padding;

      var len = box.find(' > *').width() + parseFloat((box.find(' > *').css('margin-left')).replace('px', '')) +  + parseFloat((box.find(' > *').css('margin-right')).replace('px', ''));

      if(len > 0 && !box.hasClass('centering') && !box.siblings('.scrolling').length) {
        box.addClass('centering');
        var goToCard = Math.round(scrollLeft/len + 0.05 * (isRtl ? -1 : 1));     // Adding 5% priority to next element instead of prev

        box.stop(true,false).animate({scrollLeft: goToCard * len * (isRtl ? -1 : 1)}, 500, function() {
          window.setTimeout(function() {
            box.removeClass('centering');
          }, 50);
        });
      }
    }
  });

  // On tap of scrollable div, stop existing scrolling
  $('.nice-scroll').on('touchstart', function(e) {
    var box = $(this);
    
    if($(this).hasClass('centering')) {
      box.stop(true,false).removeClass('centering');
    }
  });

}


// SHOW/HIDE SCROLL BUTTONS AFTER WINDOW RESIZE
function zetShowUsefulScrollButtons() {
  $('.nice-scroll').each(function() {
    var box = $(this);
    var boxWidth = parseInt($(this).outerWidth());
    var boxInnerWidth = parseInt($(this)[0].scrollWidth);
    var prev = box.siblings('.nice-scroll-prev');
    var next = box.siblings('.nice-scroll-next');
    
    if(boxWidth < boxInnerWidth - 2) {   // 2 is there as buffer for rounding etc
      //(isRtl ? prev.fadeIn(200) : next.fadeIn(200));
      (isRtl ? prev.removeClass('disabled').fadeIn(100) : next.removeClass('disabled').fadeIn(100));
      $(this).parent().addClass('nice-scroll-have-overflow').removeClass('nice-scroll-nothave-overflow');
    } else {
      //(isRtl ? prev.hide(0) : next.hide(0));
      (isRtl ? prev.addClass('disabled') : next.addClass('disabled'));
      $(this).parent().removeClass('nice-scroll-have-overflow').addClass('nice-scroll-nothave-overflow');
    }
  });
}


// HIDE SHOW/HIDE BUTTONS IN CASE IT'S NOT NEEDED
function zetHideUselessScrollButtons() {
  $('.nice-scroll').each(function() {
    var box = $(this);
 
    if(box.prop('scrollWidth') - box.width() <= 0) {
      box.siblings('.nice-scroll-prev, .nice-scroll-next').hide(0);
    }
  });
}
