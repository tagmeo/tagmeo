(function($) {
  'use strict';

  $.wp.customize('blogname', function(value) {
    value.bind(function(to) {
      $('.brand').text(to);
    });
  });
})(jQuery);
