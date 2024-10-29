jQuery(document).ready(function($){
    $('.tdm-color-field').wpColorPicker();

    if($('select#select_post_type').val() == 'product'){
        $('.produtc_option').show();
    }

    // Import Feed now
    /*
    $('button#import_now').on('click', function() {
      $('span.message').css('color','');
      event.preventDefault();
          $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'adnk_import_import_now',
            },
            success: function (response) {
              console.log('importato');
              $('span.message').text('');
              $('span.message').append('Articoli importati con successo');
              $('span.message').css('color','green');
            },
            error: function (response) {
                console.log(response);
                $('span.message').text('');
                $('span.message').append('Import Fallito');
            }
        });

      }); */

});