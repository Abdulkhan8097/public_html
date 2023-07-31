$( document ).ready(function() {

    $(window).scroll(function() {
        if ($(window).scrollTop() > 100) {
          $("#Desktop_Header").addClass("sticky");
        }
        else {
          $("#Desktop_Header").removeClass("sticky");
        }
    });

	owl = $("#landing_slider");
	if($("#landing_slider").length){
		$('#landing_slider').owlCarousel({
			loop:true,
			stagePadding:0,
			margin:0,
			stagePadding:0,
			nav:false,
			dots:true,
			dotsContainer: '#landing_dots',
			items:1,
			autoplay:true,
			animateIn: 'fadeIn',
			animateOut: 'fadeOut',
			autoplayTimeout:5000
		});
	}

	owl = $("#industries_slider");
	if($("#industries_slider").length){
		$('#industries_slider').owlCarousel({
			loop:true,
			stagePadding:0,
			margin:0,
			stagePadding:0,
			nav:false,
			dots:false,
			items:1,
			autoplay:true,
			animateIn: 'fadeIn',
			animateOut: 'fadeOut',
			autoplayTimeout:3000
		});
	}
	owl = $("#supplier_slider");
	if($("#supplier_slider").length){
		$('#supplier_slider').owlCarousel({
			loop:true,
			stagePadding:0,
			margin:0,
			stagePadding:0,
			nav:false,
			dots:false,
			autoplay:true,
			autoplayTimeout: 3000,
			// dots:true,
			// dotsContainer: '#supplier_slider_dots',
			responsive : {
				    0 : {
						items:2,
						slideBy:4,
						stagePadding:50,
						margin:10,
						dotsContainer:false
				    },
				    600 : {
						items:2,
						slideBy:2
				    },
				    992 : {
						items:2,
						slideBy:2,
						margin:20
				    }
				}
		});
	}
	owl = $("#customers_slider");
	if($("#customers_slider").length){
		$('#customers_slider').owlCarousel({
			loop:true,
			stagePadding:0,
			margin:0,
			stagePadding:0,
			nav:false,
			dots:true,
			items:4,
			slideBy:4,
			dotsContainer: '#customers_slider_dots'
		});
	}
	owl = $("#testimonials_slider");
	if($("#testimonials_slider").length){
		$('#testimonials_slider').owlCarousel({
			loop:true,
			stagePadding:0,
			margin:0,
			stagePadding:0,
			nav:false,
			dots:true,
			slideBy:2,
			items:2,
			responsive : {
				    0 : {
						items:1,slideBy:1
				    },
				    600 : {
						items:2,slideBy:2
				    },
				    992 : {
						items:2,slideBy:2
				    }
				}
		});
	}


	owl = $("#mission_our_vision_slider");
	if($("#mission_our_vision_slider").length){
		$('#mission_our_vision_slider').owlCarousel({
			loop:true,
			stagePadding:0,
			margin:0,
			stagePadding:0,
			nav:false,
			dots: false,
			items:1
		});
	}

	if($("select").length){
		$("select").selectBox({keepInViewport:false,mobile:true});
	}






  var wow = new WOW(
    {
      boxClass:     'wow',      // animated element css class (default is wow)
      animateClass: 'animated', // animation css class (default is animated)
      offset:       0,          // distance to the element when triggering the animation (default is 0)
      mobile:       true,       // trigger animations on mobile devices (default is true)
      live:         true,       // act on asynchronously loaded content (default is true)
      callback:     function(box) {
        // the callback is fired every time an animation is started
        // the argument that is passed in is the DOM node being animated
      },
      scrollContainer: null // optional scroll container selector, otherwise use window
    }
  );
  wow.init();



	// counter();
	counterWayPoint();



});



	var counter = function() {
		$('.js-counter').countTo({
			formatter: function (value, options) {
				return value.toFixed(options.decimals);
			}
		});
	};

	var counterWayPoint = function() {
		if ($('#counter').length > 0 ) {
			$('#counter').waypoint(function(direction) {
				// alert("Callll");
					setTimeout( counter , 400);
					$(this.element).addClass('animated');
				if( direction === 'down' && !$(this.element).hasClass('animated') ) {
				}
			} , { offset: '90%' } );
		}
	};

$(document).ready(function(){
	$(".scroll_to_link").click(function(){
		rel=$(this).attr("rel");
		$('html, body').animate({scrollTop:$("#"+rel).offset().top-80});
	});

	$(".load_more").click(function(){
		$(".hidden_logos").removeClass('animated');
		id = $(this).attr("rel");
		if ($(this).hasClass("loaded")) {
			$("#"+id).slideUp();
			$(this).text("LOAD MORE");
			$(this).removeClass("loaded");
			return false;
		}
		$("#"+id).slideToggle();
		$(this).text("LOAD LESS");
		$(this).addClass("loaded");
	});

	$(".nav_mo").click(function(){
		$("#Mobile_Menu").toggleClass("open_menu");
		$(this).toggleClass("menu_close");
	});

	$(".has_dropdown").click(function(){
		if ($(this).hasClass("shown")) {
			$(this).parent().find("ul").slideUp();
			$(this).removeClass("shown");
			return false;
		}
		$(this).parent().find("ul").slideDown();
		$(this).addClass("shown");
	});

    $("#contact_form").validate({
        submitHandler: submitFrom
    });	

  $("#search_product").on("keyup", function() {
    var value = $(this).val().toLowerCase();
	$(".animated").removeClass("animated");
	$("body").find(".hide_on_search").fadeOut(0);

	$(".filter_section").each(function(){
		rel = $(this).attr('rel');
		sub = $(this).data('sub');
      	$("#"+sub).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      	$("#"+rel).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      	$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
	});

	$('.products_alphatical .col-md-4').filter(function(){
		$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		$(this).find(".underline").toggle($(this).text().toLowerCase().indexOf(value) > -1);
	});

	$('.alphabet').filter(function(){
		$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		$(this).find(".underline").toggle($(this).text().toLowerCase().indexOf(value) > -1);
	});

  $(".alphabet p").unhighlight();
  $('.alphabet p').highlight(value);
  
    $(".alphabet p").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });

  });

});




function submitFrom(form) {
    var obj = $('form#'+form.id);
    var formData = $('form#'+form.id).serialize()+'&command='+form.id;
    $("#submit").val('SENDING');
    $.post("form_submit.php",  formData , function(data) {
        if(data.status=='success') {
            // alert("Success");
            $("#submit").val('SENT');
            $("#submit").animate({'opacity': '0.5'});
            $("#submit").attr('disabled', 'disabled');
            $("form#contact_form")[0].reset();
            $("#contact_msg_status").fadeIn(0);
            // AFTER FORM SUBMIT //
            $("#submit").delay(3000).val('SEND');
            $("#submit").delay(3000).animate({'opacity': '1'});
            $("#submit").delay(3000).removeAttr('disabled');
            $("#contact_msg_status").delay(3000).fadeOut();
        } else {
            // alert("Failed");
            obj.hide();
            obj.parent().append(data.message);
        }
    }, "json");
    return false;
}