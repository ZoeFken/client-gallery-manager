/**
 * Masonry creatie
 * Lazyload van de zichtbare foto's
 */
var $container = $('.grid');
$container.imagesLoaded(function(){
    $container.masonry({
        itemSelector: '.grid-item',
        columnWidth: '.grid-sizer',
        percentPosition: true,
        gutter: 10
    });
    $('.grid-item img').addClass('not-loaded');
    $('.grid-item img.not-loaded').lazy({
        effect: 'fadeIn',
        load: function() {
            // Disable trigger on this image
            $(this).removeClass("not-loaded");
            $container.masonry('reload');
        }
    });
    $('.grid-item img.not-loaded').trigger('scroll');
});


/**
 * LazyLoad lightbox foto's
 */
$(function() {
    $(".image-link").on('click', function() {
        var counter = $(this).data('target');
        $('.lazybox-' + counter).lazy({
            effect: 'fadeIn'
        });
    });
  });