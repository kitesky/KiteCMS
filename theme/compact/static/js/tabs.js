(function() {
  'use strict';

  //
  // Tabs
  //
  $('.tabs').each(function() {
    var $this = $(this),
      $h = $this.find('> header > h4'),
      $items = $this.find('> .tabs-main > .tabs-item'),
      active = $this.attr('data-active') - 1,
      width = $this.attr('data-width');

    if ( active != undefined) {
      $h.eq(active).addClass('active');
      $items.eq(active).show();
    } else {
      $h.eq(0).addClass('active');
      $items.eq(0).show();
    }

    if ( width != undefined ) {
      $this.width(parseInt(width));
    }

    $h.click(function() {
      var id = $(this).index();
      $h.removeClass('active').eq(id).addClass('active');
      $items.hide().eq(id).fadeIn();
    });
  });

})();