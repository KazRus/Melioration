(function($) {
	
	"use strict";
	
	//------like end-----
	 var bunch_theme = {   
	   count: 0,
	   likeit: function(options, selector)
	   {
		options.action = '_bunch_ajax_callback';
		
		if( $(selector).data('_bunch_like_it') === true ){
		 bunch_theme.msg( 'You have already done this job', 'error' );
		 return;
		}
		
		$(selector).data('_bunch_like_it', true );
	
		bunch_theme.loading(true);
		
		$.ajax({
		 url: ajaxurl,
		 type: 'POST',
		 data:options,
		 dataType:"json",
		 success: function(res){
	
		  try{
		   var newjason = res;
	
		   if( newjason.code === 'fail'){
			$(selector).data('_bunch_like_it', false );
			bunch_theme.loading(false);
			bunch_theme.msg( newjason.msg, 'error' );
		   }else if( newjason.code === 'success' ){
			//$('a[data-id="'+options.data_id+'"]').html( '<i class="fa fa-heart-o"></i> '+newjason.value );
			bunch_theme.loading(false);
			$(selector).data('_bunch_like_it', true );
			bunch_theme.msg( newjason.msg, 'success' );
		   }
		   
		  }
		  catch(e){
		   bunch_theme.loading(false);
		   $(selector).data('_bunch_like_it', false );
		   bunch_theme.msg( 'There was an error with request '+e.message, 'error' );
		   
		  }
		 }
		});
	   },
	   loading: function( show ){
		if( $('.ajax-loading' ).length === 0 ) {
		 $('body').append('<div class="ajax-loading" style="display:none;"></div>');
		}
		
		if( show === true ){
		 $('.ajax-loading').show('slow');
		}
		if( show === false ){
		 $('.ajax-loading').hide('slow');
		}
	   },
	   
	   msg: function( msg, type ){
		if( $('#pop' ).length === 0 ) {
		 $('body').append('<div style="display: none;" id="pop"><div class="pop"><div class="alert"><p></p></div></div></div>');
		}
		if( type === 'error' ) {
		 type = 'danger';
		}
		var alert_type = 'alert-' + type;
		
		$('#pop > .pop p').html( msg );
		$('#pop > .pop > .alert').addClass(alert_type);
		
		$('#pop').slideDown('slow').delay(5000).fadeOut('slow', function(){
		 $('#pop .pop .alert').removeClass(alert_type);
		});
		
		
	   },
	   
	  };
  
 //------like end-----
	
	//Hide Loading Box (Preloader)
	function handlePreloader() {
		if($('.preloader').length){
			$('.preloader').delay(200).fadeOut(500);
		}
	}
	
	
	//Update Header Style and Scroll to Top
	function headerStyle() {
		if($('.main-header').length){
			var windowpos = $(window).scrollTop();
			var siteHeader = $('.main-header');
			var sticky_header = $('.fixed-header .sticky-header');
			var scrollLink = $('.scroll-to-top');
			if (windowpos > 200) {
				siteHeader.addClass('fixed-header');
				sticky_header.addClass("animated slideInDown");
				scrollLink.fadeIn(300);
			} else {
				siteHeader.removeClass('fixed-header');
				sticky_header.removeClass("animated slideInDown");
				scrollLink.fadeOut(300);
			}
		}
	}
	headerStyle();


	//Submenu Dropdown Toggle
	if($('.main-header li.dropdown ul').length){
		$('.main-header li.dropdown').append('<div class="dropdown-btn"><span class="fa fa-angle-down"></span></div>');
		
		//Dropdown Button
		$('.main-header li.dropdown .dropdown-btn').on('click', function() {
			$(this).prev('ul').slideToggle(500);
		});
		
		//Disable dropdown parent link
		$('.main-header .navigation li.dropdown > a,.hidden-bar .side-menu li.dropdown > a').on('click', function(e) {
			e.preventDefault();
		});
	}
	
	
	//Fact Counter + Text Count
	if($('.count-box').length){
		$('.count-box').appear(function(){
	
			var $t = $(this),
				n = $t.find(".count-text").attr("data-stop"),
				r = parseInt($t.find(".count-text").attr("data-speed"), 10);
				
			if (!$t.hasClass("counted")) {
				$t.addClass("counted");
				$({
					countNum: $t.find(".count-text").text()
				}).animate({
					countNum: n
				}, {
					duration: r,
					easing: "linear",
					step: function() {
						$t.find(".count-text").text(Math.floor(this.countNum));
					},
					complete: function() {
						$t.find(".count-text").text(this.countNum);
					}
				});
			}
			
		},{accY: 0});
	}
	
	
	//Progress Bar
	if($('.progress-line').length){
		$('.progress-line').appear(function(){
			var el = $(this);
			var percent = el.data('width');
			$(el).css('width',percent+'%');
		},{accY: 0});
	}
	

	//Testimonial Carousel
	if ($('.project-carousel').length) {
		$('.project-carousel').owlCarousel({
			loop:true,
			margin:0,
			nav:false,
			smartSpeed: 700,
			autoplay: true,
			navText: [ '<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>' ],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:2
				},
				800:{
					items:2
				},
				1024:{
					items:2
				},
				1200:{
					items:4
				}
			}
		});    		
	}


	//Volunteer Carousel
	if ($('.volunteer-carousel').length) {
		$('.volunteer-carousel').owlCarousel({
			loop:true,
			margin:0,
			nav:true,
			smartSpeed: 700,
			autoplay: true,
			navText: [ '<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>' ],
			responsive:{
				0:{
					items:1
				},
				800:{
					items:2
				},
				1200:{
					items:2
				}
			}
		});    		
	}
	

	//Testimonial Carousel
	if ($('.testimonial-carousel').length) {
		$('.testimonial-carousel').owlCarousel({
			loop:true,
			margin:0,
			nav:true,
			smartSpeed: 700,
			autoplay: true,
			navText: [ '<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>' ],
			responsive:{
				0:{
					items:1
				},
				800:{
					items:1
				},
				1200:{
					items:1
				}
			}
		});    		
	}

	//Sponsors Carousel
	if ($('.sponsors-carousel').length) {
		$('.sponsors-carousel').owlCarousel({
			loop:true,
			margin:0,
			nav:false,
			smartSpeed: 700,
			autoplay: 4000,
			navText: [ '<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>' ],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:2
				},
				800:{
					items:3
				},
				1200:{
					items:4
				},
				1600:{
					items:5
				}
			}
		});    		
	}

	//Instagram Carousel
	if ($('.insta-carousel').length) {
		$('.insta-carousel').owlCarousel({
			loop:true,
			margin:0,
			nav:true,
			smartSpeed: 700,
			autoplay: 4000,
			navText: [ '<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>' ],
			responsive:{
				0:{
					items:1
				},
				1200:{
					items:1
				},
			}
		});    		
	}

	//Instagram Carousel
	if ($('.single-item-carousel').length) {
		$('.single-item-carousel').owlCarousel({
			loop:true,
			margin:0,
			nav:true,
			smartSpeed: 700,
			autoplay: 4000,
			navText: [ '<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>' ],
			responsive:{
				0:{
					items:1
				},
				1200:{
					items:1
				},
			}
		});    		
	}

	//Two Item Carousel
	if ($('.two-item-carousel').length) {
		$('.two-item-carousel').owlCarousel({
			loop:true,
			margin:30,
			nav:true,
			smartSpeed: 700,
			autoplay: 4000,
			navText: [ '<span class="fa fa-angle-left"></span>', '<span class="fa fa-angle-right"></span>' ],
			responsive:{
				
				0:{
					items:1
				},
				660:{
					items:2
				},
				1200:{
					items:2
				},
			}
		});    		
	}



	//Masonary
	function simpleMasonry() {
		if($('.masonry-items-container').length){
	
			var winDow = $(window);
			// Needed variables
			var $container=$('.masonry-items-container');
	
			$container.isotope({
				itemSelector: '.masonry-item',
				 masonry: {
					columnWidth : '.masonry-item.small-column'
				 },
				animationOptions:{
					duration:500,
					easing:'linear'
				}
			});
	
			winDow.on('resize', function(){

				$container.isotope({ 
					itemSelector: '.masonry-item',
					animationOptions: {
						duration: 500,
						easing	: 'linear',
						queue	: false
					}
				});
			});
		}
	}
	simpleMasonry();


    //Accordion Box
	if($('.accordion-box').length){
		$(".accordion-box").on('click', '.acc-btn', function() {
			
			var outerBox = $(this).parents('.accordion-box');
			var target = $(this).parents('.accordion');
			
			if($(this).hasClass('active')!==true){
				$(outerBox).find('.accordion .acc-btn').removeClass('active');
			}
			
			if ($(this).next('.acc-content').is(':visible')){
				return false;
			}else{
				$(this).addClass('active');
				$(outerBox).children('.accordion').removeClass('active-block');
				$(outerBox).find('.accordion').children('.acc-content').slideUp(300);
				target.addClass('active-block');
				$(this).next('.acc-content').slideDown(300);	
			}
		});	
	}


    //Mixitup Gallery Filters
	 if($('.filter-list').length){
	  $('.filter-list').mixItUp({});
	 }


	//LightBox / Fancybox
	if($('.lightbox-image').length) {
		$('.lightbox-image').fancybox({
			openEffect  : 'fade',
			closeEffect : 'fade',
			helpers : {
				media : {}
			}
		});
	}
	
	//Contact Form Validation
	if($('#contact-form').length){
		$('#contact-form').validate({
			rules: {
				username: {
					required: true
				},
				email: {
					required: true,
					email: true
				},
				subject: {
					required: true
				},
				message: {
					required: true
				}
			}
		});
	}
	
	// Scroll to a Specific Div
	if($('.scroll-to-target').length){
		$(".scroll-to-target").on('click', function() {
			var target = $(this).attr('data-target');
		   // animate
		   $('html, body').animate({
			   scrollTop: $(target).offset().top
			 }, 1500);
	
		});
	}
	
	// Elements Animation
	if($('.wow').length){
		var wow = new WOW(
		  {
			boxClass:     'wow',      // animated element css class (default is wow)
			animateClass: 'animated', // animation css class (default is animated)
			offset:       0,          // distance to the element when triggering the animation (default is 0)
			mobile:       false,       // trigger animations on mobile devices (default is true)
			live:         true       // act on asynchronously loaded content (default is true)
		  }
		);
		wow.init();
	}


/* ==========================================================================
   When document is Scrollig, do
   ========================================================================== */
	
	$(window).on('scroll', function() {
		headerStyle();
	});
	
/* ==========================================================================
   When document is loading, do
   ========================================================================== */
	
	$(window).on('load', function() {
		handlePreloader();
		simpleMasonry();
	});	

	
	$('.jolly_like_it').click(function(e) {
		   
		 e.preventDefault();
		 
		 var opt = {subaction:'likeit', data_id:$(this).attr('data-id')};
		 bunch_theme.likeit( opt, this );
		 
		 setTimeout(function(){
		   window.location.reload(1);
		}, 1500);
		
		 return false;
	});/**like end*/

})(window.jQuery);