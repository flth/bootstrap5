<?php
  /* --------------------------------------------------------------
   $Id: default.js.php 12435 2019-12-02 09:21:20Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2019 [www.modified-shop.org]
   --------------------------------------------------------------
   Released under the GNU General Public License
   --------------------------------------------------------------*/
?>
<script>
	$(document).ready(function(){
<?php if ($_SESSION['customers_status']['customers_status'] == '0') { ?>
	$('body').addClass('admin_mode');
<?php } ?>
	$(".listing_topscroll").click(function(event) {
		event.preventDefault();
		$("html, body").animate({ scrollTop: $('.listing').offset().top - 120}, "slow", function() {
			$(".listing_bottomscroll").focus();
		});
    return false;
  });
	$('.as-oil .as-oil__heading-intro-description').focus();
	$(".listing_bottomscroll").click(function(event) {
		event.preventDefault();
		$("html, body").animate({ scrollTop: $('.listing').offset().top + $(".listing").outerHeight() - $(window).height() + 200}, "slow", function() {
			$(".listing_topscroll").focus();
		});
		return false;
	});

<?php if (BS5_AWIDSRATINGBREAKDOWN == 'true') { ?>
	// ajax awids rating
	$('a.bs5_avg_container').click(function() {
		var $this = $(this);
		if($this.next('div .avg_container').length > 0) {
			$this.next('div .avg_container').slideToggle('slow');
		} else {
			var pid = $this.data("pid");
			var cssclass = $this.data("class");
			$.get(DIR_WS_BASE+'ajax.php', {ext: 'bs5_awids_rating', pid: pid, class: cssclass, speed: 1}, function(data) {
				if (data != '' && data != undefined) {
					$this.after(data);
					loadGallery();
					$this.next('div .avg_container').slideToggle('slow');
				} else {
					$this.insertAfter('AJAX-FEHLER');
				}
			});
		}
        ac_closing();
        return false;
	});
	$('html').on('click', function(e) {
		if (!$(e.target).closest('.avg_container').length > 0 ) {
			$('.avg_container').slideUp('slow');
		}
	});
<?php } ?>
		// Hintergrundfarbe aktiver Filter
		$(".filter_bar select option:selected").each(function(){
			if($(this).val() != "") {
				$(this).parent().addClass('bg-<?php echo BS5_FILTERCOLOR_AKTIV; ?> border-<?php echo BS5_FILTERBORDERCOLOR_AKTIV; ?>').focus();
				document.getElementById('filterBar').scrollIntoView({behavior: 'smooth'});
			}
		});
		// Attribut "disabled" für Button, wenn Formular übermittelt wird
		$('form#create_account').submit(function(){
			$(this).find(':submit').prop('disabled', true);
		});
		// Passwort anzeigen
		$(".togglePw").on( "click", function() {
			$(this).toggleClass("fa-eye fa-eye-slash");
			var input = $(this).siblings("input");
			if (input.attr("type") == "password") {
				input.attr("type", "text");
				$(this).attr("aria-label", "<?php echo BS5_TOGGLE_PWFIELD_HIDE; ?>");
			} else {
				input.attr("type", "password");
				$(this).attr("aria-label", "<?php echo BS5_TOGGLE_PWFIELD_SHOW; ?>");
			}
		});
	});
	var curtext = "<?php echo str_replace(' ', '&nbsp;', TEXT_COLORBOX_CURRENT); ?>";
<?php if (strpos($PHP_SELF, 'checkout') !== false) { ?>
	$('#button_checkout_confirmation').on('click',function() {
		$(this).hide();
	});
<?php }
// Beginn Autocomplete
if (SEARCH_AC_STATUS == 'true') { ?>
  var session_id = '<?php echo xtc_session_id(); ?>';
  $('body').on('keyup paste cut input focus', '#inputString', delay(function() {
    if ($(this).length == 0) {
      $('#suggestions').hide();
    } else {
      var post_params = $('#quick_find').serialize();
      $.ajax({
        dataType: "json",
        type: 'post',
        url: '<?php echo DIR_WS_BASE; ?>ajax.php?ext=get_autocomplete&MODsid='+session_id,
        data: post_params,
        cache: false,
        async: true,
        success: function(data) {
          if (data !== null && typeof data === 'object') {
            if (data.result !== null && data.result != undefined && data.result != '') {
              $('#autoSuggestionsList').html(decode_ajax(data.result));
              $('#suggestions').slideDown();
              $('#inputStringSubmit').prop("disabled",true);
            } else {
              $('#suggestions').slideUp();
              $('#inputStringSubmit').prop("disabled",false);
            }
          }
        }
      });
    }
  }, 500));
  function delay(fn, ms) {
    let timer = 0;
    return function(args) {
      clearTimeout(timer);
      timer = setTimeout(fn.bind(this, args), ms || 0);
    }
  }
  function decode_ajax(encodedString) {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = encodedString;
    return textArea.value;
  }
  $('body').on('click', function (e) {
    if ($(e.target).closest("#suggestions").length === 0) {
      ac_closing();
    }
  });
<?php
}
if (SEARCH_AC_STATUS == 'true' || (basename($PHP_SELF) != FILENAME_SHOPPING_CART && strpos($PHP_SELF, 'checkout') === false)) { ?>
	function ac_closing() {
		$('#inputStringSubmit').prop("disabled",false);
		setTimeout("$('#suggestions').slideUp();", 100);
	}
<?php
}
// Ende Autocomplete
// Beginn Alert
?>
	function alert(message, title) {
		title = title || "<?php echo TEXT_LINK_TITLE_INFORMATION; ?>";
		$.alertable.alert('<span id="alertable-title"></span><span id="alertable-content"></span>', {
			html: true
		});
		$('#alertable-content').html(message);
		$('#alertable-title').html(title);
		$('.alertable-buttons').find('button').focus();
	}
<?php
// Ende Alert
// Beginn Aufklappen Warenkorb und Merkzettel
if (basename($PHP_SELF) != FILENAME_SHOPPING_CART && strpos($PHP_SELF, 'checkout') === false) {
?>
	$(function() {
<?php if (DISPLAY_CART == 'false' && isset($_SESSION['new_products_id_in_cart'])) {
		unset($_SESSION['new_products_id_in_cart']); ?>
		var canvas_cart = document.getElementById('canvas_cart');
		var bscanvas_cart = new bootstrap.Offcanvas(canvas_cart);
		bscanvas_cart.show();
<?php } ?>
<?php if (DISPLAY_CART == 'false' && isset($_SESSION['new_products_id_in_wishlist'])) {
		unset($_SESSION['new_products_id_in_wishlist']);
?>
		var canvas_wish = document.getElementById('canvas_wish');
		var bscanvas_wish = new bootstrap.Offcanvas(canvas_wish);
		bscanvas_wish.show();
<?php } ?>
	});
<?php
} else {
unset($_SESSION['new_products_id_in_cart']);
unset($_SESSION['new_products_id_in_wishlist']);
}
// Ende Aufklappen Warenkorb und Merkzettel
// Beginn Erweiterte Validation im Registrierungsformular
if (strpos($PHP_SELF, FILENAME_CREATE_ACCOUNT) !== false || strpos($PHP_SELF, FILENAME_CREATE_GUEST_ACCOUNT) !== false) {
?>
	(function(){
		var isinvalid = document.querySelector('.is-invalid');
		if (typeof(isinvalid) != 'undefined' && isinvalid != null) isinvalid.focus();
	})();
<?php
}
// Ende Erweiterte Validation im Registrierungsformular
// Beginn KK-Megamenü
if(!empty($KK_MEGAS) && BS5_MENUCASE != '2') {
?>
	(function($){
		//define the defaults
		$.fn.KKMega = function(options){
			//set default options
			var defaults = {
				rows: 3,
				max: ''
			};
			//call in the default otions
			var options = $.extend(defaults, options);
			var $KKMegaObj = this;
			return $KKMegaObj.each(function(options){
				megaSetup();
				function megaSetup(){
					$($KKMegaObj).each(function(){
						if (defaults.rows != 3) {
							$('.kk-mega.row',this).removeClass("row-cols-lg-3").addClass("row-cols-lg-"+defaults.rows);
						}
						if (defaults.max != '') {
							$('.kk-mega ul.col').each(function() {
								var limax = $(this).find('li:eq('+(defaults.max -1)+')');
								var linext = $(this).find('li:eq('+defaults.max+')').length;
								if (linext > 0) {
									if ($(this).find('li.active').length < 1 && $(this).find('li.morecats').length < 1){
										$(limax).nextAll('li').addClass('d-none');
										$(limax).after('<li class="morecats my-2"><a href=""><span class="more"><?php echo BS5_MORECATS_SHOW; ?></span></a></li>');
									} else if($(this).find('li.active').length > 0 && $(this).find('li.morecats').length < 1){
										$(limax).after('<li class="morecats my-2"><a href=""><span class="more"><?php echo BS5_MORECATS_HIDE; ?></span></a></li>');
									}
								}
							});
						}
					});
				}
			});
		};
	})(jQuery);
	$(document).ready(function(){
<?php foreach ($KK_MEGAS as $megas) {
	$mega = explode("-", $megas);
?>
		$('#<?php echo $mega[0]; ?>').KKMega({
			rows: '<?php echo $mega[1]; ?>',
			max: '<?php if (isset($mega[2]))echo $mega[2]; ?>'
		});
<?php } ?>
		$('ul#main').on('click', '.morecats', function(e) {
			$(this).find('span').text($(this).text() == '<?php echo BS5_MORECATS_SHOW; ?>' ? '<?php echo BS5_MORECATS_HIDE; ?>' : '<?php echo BS5_MORECATS_SHOW; ?>');
			$(this).nextAll('li').toggleClass("d-none");
			e.preventDefault();
		});
	});
<?php
}
?>
$('ul#main li.level1.hassub').hover(function (ev) {
		ev.stopPropagation();
		var sel = $(this).find('>a');
		if (typeof sel.data('href') != "undefined") {
			sel.attr('href', sel.data('href'));
		}
		sel.removeAttr('data-bs-toggle');
		sel.attr('aria-expanded','true');
  }, function (ev) {
		ev.stopPropagation();
		var sel = $(this).find('>a');
		sel.attr('href', '#/');
		sel.attr('data-bs-toggle', 'dropdown');
		sel.attr('aria-expanded','false');
  });
<?php
// Ende KK-Megamenü
?>
<?php if ((strpos(basename($PHP_SELF), 'checkout') === false && strpos(basename($PHP_SELF), 'bs5_') === false)
    || strpos(basename($PHP_SELF), 'account_checkout_express') !== false) { ?>
    document.addEventListener(
      "DOMContentLoaded", () => {
        const menu = new MmenuLight(
          document.querySelector( "#mmenu" ),
          'all'
        );

        const navigator = menu.navigation({
          selectedClass: 'Selected',
          slidingSubmenus: true,
          theme: 'light',
          title: '<?php echo TEXT_MENU_TITLE; ?>'
        });
        const drawer = menu.offcanvas({
          position: 'left'
        });

        document.querySelector('#mmopener')
        .addEventListener( "click", ( evnt ) => {
          evnt.preventDefault();
          drawer.open();
        });

        document.querySelector('.mmcloser')
        .addEventListener( "click", ( evnt ) => {
          evnt.preventDefault();
          drawer.close();
        });

      }
    );
<?php } ?>
<?php  if (basename($PHP_SELF) == FILENAME_PRODUCT_INFO) { ?>
	window.addEventListener('DOMContentLoaded', function() {
	// Plus-/Minus-Button
	var btn_minus = document.getElementById("btn_minus");
	if (btn_minus) {
		var btn_plus = document.getElementById("btn_plus");
		var qty_input = document.getElementById("qty_input");
		btn_minus.addEventListener("click", function(){
			if (qty_input.value > 1) qty_input.value--;
		});
		btn_plus.addEventListener("click", function() {
			qty_input.value++;
		});
	}
<?php  if (BS5_USE_VIEWERJS == 'true') { ?>
		var pd_viewer = document.getElementById('pd_viewer');
		var viewer = new Viewer(pd_viewer, {
			url: 'data-org',
			title: [1, (image, imageData) => `${image.alt}`],
			maxZoomRatio: 1,
			zIndex: 100001,
			transition: true,
			toolbar: {
				zoomIn: 1,
				reset: 1,
				zoomOut: 1,
				prev: 1,
				next: 1,
			},
			shown() {
				document.querySelector(".viewer-zoom-in").classList.add("fa","fa-magnifying-glass-plus","btn","btn-secondary","mx-2");
				document.querySelector(".viewer-reset").classList.add("fa","fa-magnifying-glass","btn","btn-secondary");
				document.querySelector(".viewer-zoom-out").classList.add("fa","fa-magnifying-glass-minus","btn","btn-secondary","mx-2");
				document.querySelector(".viewer-prev").classList.add("fa","fa-chevron-left","btn","btn-secondary");
				document.querySelector(".viewer-next").classList.add("fa","fa-chevron-right","btn","btn-secondary");
				document.querySelector(".viewer-close").classList.add("fa","fa-xmark","btn","btn-secondary");
			},
    });
<?php } ?>
	});
<?php } ?>
</script>