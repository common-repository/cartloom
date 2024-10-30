(function($) {
	
	/* Search bar */

var resizeElements;

$(document).ready(function() {
	

  // Set up common variables
  // --------------------------------------------------

  var bar = ".cl_search_bar";
  var input = bar + " input[type='text']";
  var button = bar + " button[type='submit']";
  var dropdown = bar + " .cl_search_dropdown";
  var dropdownLabel = dropdown + " > span";
  var dropdownList = dropdown + " ul";
  var dropdownListItems = dropdownList + " li";


  // Set up common functions
  // --------------------------------------------------

  resizeElements = function() {
    var barWidth = $(bar).outerWidth();

    var labelWidth = $(dropdownLabel).outerWidth();
    $(dropdown).width(labelWidth);

    var dropdownWidth = $(dropdown).outerWidth();
    var buttonWidth	= $(button).outerWidth();
    var buttonWidth	= 1;
    var inputWidth = barWidth - dropdownWidth - buttonWidth;
    var inputWidthPercent = inputWidth / barWidth * 100 + "%";

    $(input).css({ 'margin-left': dropdownWidth, 'width': inputWidthPercent });
  }

  function dropdownOn() {
    $(dropdownList).fadeIn(25);
    $(dropdown).addClass("active");
  }

  function dropdownOff() {
    $(dropdownList).fadeOut(25);
    $(dropdown).removeClass("active");
  }


  // Initialize initial resize of initial elements 
  // --------------------------------------------------
  resizeElements();


  // Toggle new dropdown menu on click
  // --------------------------------------------------

  $(dropdown).click(function(event) {
    if ($(dropdown).hasClass("active")) {
      dropdownOff();
    } else {
      dropdownOn();
    }

    event.stopPropagation();
    return false;
  });

  $("html").click(dropdownOff);


  // Activate new dropdown option and show tray if applicable
  // --------------------------------------------------

  $(dropdownListItems).click(function() {
    $(this).siblings("li.selected").removeClass("selected");
    $(this).addClass("selected");

    // Focus the input
    $(this).parents("form.cl_search_bar:first").find("input[type=text]").focus();

    var labelText = $(this).text();
    $(dropdownLabel).text(labelText);

    resizeElements();

  });

  $('.cl_embed_btn').on('click',function() {
  	$('.cl_embeds_out').toggleClass('appear');
  });
  
  // Sort and Search actions

	var listItem = $('.cl_embed_results li');

	// Search
	 $('#cl_search_input').on('click input', function () {
		if (this.value.length < 1) {
			listItem.show();
			$('.cl_search_dropdown ul li').removeClass('selected');
			$('.cl_search_dropdown ul li.all').addClass('selected');
			$(dropdownLabel).text('All');
			resizeElements();
		}
	});
	
  $('#cl_search_input').on('keyup input', function () {

		if (this.value.length > 0) {
			listItem.hide().filter(function () {
				return $(this).text().toLowerCase().indexOf($('#cl_search_input').val().toLowerCase()) != -1;
			}).show();
		}
		else {
			listItem.show();
			$('.cl_search_dropdown ul li').removeClass('selected');
			$('.cl_search_dropdown ul li.all').addClass('selected');
			$(dropdownLabel).text('All');
			resizeElements();
		}
	});
	
	// Sort
	$('.cl_search_dropdown ul li').on('click', function(){
		
		listItem.hide();
		
		$('#cl_search_input').val('');
		
		if($(this).hasClass('products'))
			$('.cl-product').show();
			
		if($(this).hasClass('groups'))
			$('.cl-group').show();
			
		if($(this).hasClass('all'))
			$('.cl-group, .cl-product').show();	
		
		
	});
	

  
  
  
  // Set up shortcode insertion functionality.
  
  // modify the code below to work with cartloom solution. -MY
  
  $( 'a.cl-embed-item' ).on( 'click', function( e ) {  
	  var id = $(this).data('id');
	  var type = $(this).data('type');
	  var cartname = $(this).data('cartname');
	  $('.cl_embeds_out').removeClass('appear');
	  wp.media.editor.insert( add_shortcode( cartname, type, id ) );
  });
  
  	$('a.cl-embed-item').on('dragstart', function (e) {
	    var dataTransfer = e.originalEvent.dataTransfer;
	    var id = $(this).data('id');
		var type = $(this).data('type');
		var cartname = $(this).data('cartname');
		var v = add_shortcode( cartname, type, id );
	    dataTransfer.setData('Text', v);
	});
  
	function add_shortcode( cartname, type, id ) {
		return '[cl_' + type + ' cartname="' + cartname + '" id="' + id + '"]';
    }
  
  

});

}(jQuery));