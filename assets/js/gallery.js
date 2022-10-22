/**
 * Als de selectie link aangeklikt wordt verander de stijlen en pas het paneel aan.
 */
$('a.selection-link').click(function(event) 
{
  event.preventDefault();
  var url = $(this).attr("href");
  var segments = url.split( '/' );
  var last_segment = segments.pop(); // het laatste element van de array
  var calc = parseInt($('#ammount_selected').text()); // parseInt cast de string naar int
  var included = parseInt($('#ammount_included').text());
  $.ajax
  ({
    url: url,
    type: "POST", // type 
    success: function ()
    {
      if($(".imagelink_" + last_segment).hasClass('isNot'))
      {
        $(".imagelink_" + last_segment).removeClass('isNot').addClass('is');
        calc = calc + 1;
        $("#ammount_selected").text(calc);
      }
      else if ($(".imagelink_" + last_segment).hasClass('is'))
      {
        $(".imagelink_" + last_segment).removeClass('is').addClass('isNot');
        calc = calc - 1;
        $("#ammount_selected").text(calc);
      }

      warningColor(included, calc);
    },
    error: function(xhr, ajaxOptions, thrownError)
    {
      // Pop-up error bericht
      alert('Er is een probleem bij het selecteren van de foto (jquery)');
    },
    timeout : 15000
  });
});

/**
 * Copier de bezoekers link
 * @param {string} element 
 */
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

/**
 * Controleer of het geselecteerde aantal groter is dan het incluzieve aantal
 * @param {int} included 
 * @param {int} selected 
 */
function warningColor(included, selected)
{
  (selected > included) ? $("#ammount_selected").addClass('text-primary') : $("#ammount_selected").removeClass('text-primary');
}

/**
 * Zet het paneel sticky of niet sticky
 */
$(window).on('load, resize', function mobileViewUpdate() {
  var viewportWidth = $(window).width();
  if (viewportWidth < 768) {
    $(".gallery").removeClass("sticky-top");
  }
  if (viewportWidth > 768) {
    $(".gallery").addClass("sticky-top");
  }
});

/**
 * Bij het laden van de pagina
 */
$(document).ready(function() {
  var selected = parseInt($('#ammount_selected').text());
  var included = parseInt($('#ammount_included').text());

  warningColor(included, selected)
});