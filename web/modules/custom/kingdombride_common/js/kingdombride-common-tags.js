/**
 * @file
 * Behaviors of tags for kingdombride_common module.
 */

 (function ($, Drupal) {
  
    /**
     * Behavior to initialize kingdombride Bef tags.
     *
     * @type {{attach: Drupal.behaviors.initKingdombrideCommonBefTags.attach}}
     */
    Drupal.behaviors.initKingdombrideCommonBefTags = {
      attach: function (context) {
        $(context)
          .find('.kb-bef-checkboxes')
          .once('init-kingdombride-common-bef-tags')
          .each(function () {
            
            var $kb_bef_checkboxes = $(this);

            $kb_bef_checkboxes.find('.label').on('click', function(e) {
              
              if ($(this).hasClass('label-default')) {
                  var data_input_id = $(this).data('input-id');
                  $(this).removeClass('label-default')
                          .addClass('label-info');
                  $(this).html('<span class="glyphicon glyphicon-remove"></span>' + $(this).text());
                  var $checkbox = $kb_bef_checkboxes.closest('.form-checkboxes')
                                  .find('.form-checkboxes input[value="' + data_input_id + '"]');
                  
                  $checkbox.prop('checked', true); 
                  $checkbox.trigger('change');         
              }
              else {
                  var data_input_id = $(this).data('input-id');
                  $(this).removeClass('label-info')
                          .addClass('label-default');
                  $(this).find('.glyphicon-remove').remove();
                  var $checkbox = $kb_bef_checkboxes.closest('.form-checkboxes')
                                  .find('.form-checkboxes input[value="' + data_input_id + '"]');
                  $checkbox.prop('checked', false);
                  $checkbox.trigger('change');
              }
            });
          });
      }
    };
  
  })(jQuery, Drupal);
  