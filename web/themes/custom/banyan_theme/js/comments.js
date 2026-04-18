(function ($, Drupal) {
  Drupal.behaviors.banyanComments = {
    attach: function (context, settings) {
      // Hide comment forms and add a toggle button
      once('banyan-comments', '.comment-form', context).forEach(function (form) {
        var $form = $(form);
        var $wrapper = $form.closest('.comment-form-wrapper, #comment-form');
        $form.hide();
        var $button = $('<button class="btn btn-primary comment-toggle-btn">Add a comment</button>');
        $form.before($button);
        $button.on('click', function (e) {
          e.preventDefault();
          $form.slideToggle();
          $button.text($form.is(':visible') ? 'Cancel' : 'Add a comment');
        });
      });
    }
  };
})(jQuery, Drupal);
