/*
 * File       : js/script.js
 */



jQuery(function($) {

/* **************************************** *
 * GLOBAL
 * **************************************** */
//var youtube = null;
var slideshow_autoplay_progress = null;

 // kill main visual progress bar
if( slideshow_autoplay_progress != null ) {
    slideshow_autoplay_progress.kill();
    slideshow_autoplay_progress = null;
}

/* **************************************** *
 * Functions INIT
 * **************************************** */
page_body_class();
current_menu();
lazyload_init();
minimize_header();
header_util_popup();
clipboard();
tabs();
small_nav();
menu_layout_setting();
selectric();
custom_scroll_init();

//youtube_play();
main_visual();
main_video();
main_games();
main_nfts();
//main_events();

launchpad();
nfts_filter();
announcement_slide();
game_view_slide();
accordion();
nfts_collection();
wallet_swap();



/* **************************************** *
 * ON LOAD
 * **************************************** */
$(window).on('load',function() {


});



    
/* **************************************** *
 * ON RESIZE
 * **************************************** */
// INITIALIZE RESIZE
function init_resize(){

    nfts_filter();

    // setTimeout to fix IOS animation on rotate issue
    setTimeout(function(){
        
        // add heref

    }, 400);

}

// Init resize on reisize
$(window).on('resize',init_resize);



/* **************************************** *
 * Functions
 * **************************************** */

 function page_body_class() {

	var $html = $('html');
	var $body = $('body');
	var container_class_str = $('#container').attr('class');

    if(typeof container_class_str != 'undefined'){
		var container_class = container_class_str.split(/\s+/);

		$.each(container_class,function(index,val){
			 $body.addClass('page-'+val+'');
			 $html.addClass('html-'+val+'');
		});
    }
}


function current_menu() {

    var url = window.location.href.split('&')[0];

	if (!window.location.origin) {
	  window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
	}

    if(url.indexOf('games-view.php') !== -1){
		url = window.location.origin+'/sub/games.php';
	}else if(url.indexOf('launchpad-view.php') !== -1){
		url = window.location.origin+'/sub/launchpad.php';
	}

    $('#menu > li > a').each(function () {
        var link_page = this.href.split('&')[0];
        if (url == link_page) {
            $(this).parents('li').addClass('current-menu').find('> ul').show();
        }
    });

}


function lazyload_init(){

    // lazyload
    $("img[data-unveil]").unveil(300, function() {
    	$(this).on('load',function() {
    		$(this).addClass('lazyload--loaded');
    	});
    });

}


function minimize_header() {

    var $window       = $(window),
	    $header       = $('#header'),
        $body         = $('body'),
        didScroll     = null,
        currentScroll = 0,
        lastScroll    = 0,
        moveScroll    = 10;

	$window.on('scroll', function() {
        didScroll = true;

		if ($window.scrollTop() > $header.height()) {
            $body.addClass('minimize');
			$header.addClass('minimize');
		} else {
            $body.removeClass('minimize');
			$header.removeClass('minimize');
		}
	});

    setInterval(function() {
        if( didScroll && !$body.hasClass('open-menu') ) {
            has_scrolled();
            didScroll = false;
        }
    }, 50);

    function has_scrolled(){

        currentScroll = $(this).scrollTop();

        // Make sure they scroll more than move scroll
        if(Math.abs(lastScroll - currentScroll) <= moveScroll) return;

        if(currentScroll > lastScroll){ // ScrollDown
            if(currentScroll > $(window).height()){
                if( $('#menu > li.menu-children--open').length > 0 ) { $('#menu > li').removeClass('menu-children--open'); }
                gsap.to($header, { duration: .4, autoAlpha: 0, y: -$header.outerHeight(), ease: 'power3.out' });
                $body.addClass('header-hide');
            }
        }
        else { // ScrollUp
            gsap.to( $header, {duration: .4, autoAlpha:1, y: 0, ease: 'power3.out' });
            $body.removeClass('header-hide');
        }

        lastScroll = currentScroll;

    }

}



function header_util_popup(){
    //header util popup
    $('.global-menu-util__popup').magnificPopup({
        type: 'inline',
        fixedContentPos: true,
        fixedBgPos: true,
        closeBtnInside: true,
        callbacks: {
            open: function() {
                $('body').addClass('mfp-popup-open');
                //$('html').css('overflow-y','hidden');
            },
            afterClose: function() {
                $('body').removeClass('mfp-popup-open');
                //$('html').removeAttr('style');
            }
        },
        midClick: true
    })

    //mypage popup
    $('.btn-account').on('click', function(){
        $('.mypage-account').show().siblings().hide();
    });
    $('.mypage-account .btn-back, .mypage-referral .btn-back, .mypage-change-pw .btn-cancel, .mypage-tickets .btn-cancel').on('click', function(){
        $('.mypage-intro').show().siblings().hide();
    });
    $('.mypage-account .btn-change-pw').on('click', function(){
        $('.mypage-change-pw').show().siblings().hide();
    });
    $('.mypage-change-pw .btn-cancel').on('click', function(){
        $('.mypage-account').show().siblings().hide();
    });
    $('.mypage-intro .btn-referral').on('click', function(){
        $('.mypage-referral').show().siblings().hide();
    });
    $('.mypage-intro .btn-tickets').on('click', function(){
        $('.mypage-tickets').show().siblings().hide();
    });


    //wallet popup
    $('.wallet-addr-form__amount').each(function () {
        var $this = $(this)
        $this.keyup(function(e) {
            //placeholder
            if($this.val().length > 0 ){
                $this.parents('.wallet-addr-form__input').addClass('active');
            } else {
                $this.parents('.wallet-addr-form__input').removeClass('active');
            }
            //콤마
            $(this).val($(this).val().replace(/[^0-9]/gi, '').replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,'));
        })
    });
/*
    $('.send-to-multi').on('click', function(){
        $('.send-to-multi__select-popup').css('display','flex');
    });
	*/
    $('.send-to-multi__select-popup .btn-cancel').on('click', function(){
        $('.send-to-multi__select-popup').hide();
    });

    $('.send-to-multi__select-popup .btn-wallet-addr-form').on('click', function(){
        $('.wallet-addr-form__send-to-multi').css('display','flex');
    });
    $('.wallet-addr-form__send-to-multi .btn-cancel').on('click', function(){
        $('.wallet-addr-form__send-to-multi').hide();
    });
/*
    $('.wallet-addr-form__send-to-multi .btn-send').on('click', function(){
        swal({
            text : 'Confirmed Transaction!',
        }).then(function(){
            $('.wallet-addr-form__send-to-multi').hide();
        });
    });
*/

    $('.send-to-nebula').on('click', function(){
        $('.wallet-addr-form__send-to-nebula').css('display','flex');
    });
    $('.wallet-addr-form__send-to-nebula .btn-cancel').on('click', function(){
        $('.wallet-addr-form__send-to-nebula').hide();
    });

    $('.wallet-addr-form__send-to-nebula .btn-send').on('click', function(){
     // $('.sign-popup').show();
        $('.wallet-addr-form__send-to-nebula').hide();
    });

}


function clipboard(){

	if( !$('.clipboard-js').length ) { return; }

    $('.clipboard-js').each(function(){

	    if(typeof Clipboard != "undefined"){
        
            var $el = $(this);
            var clipboard = new Clipboard($el[0]);
        
            var $clipboard_tooltip = $el.parents('.clipboard').find('.clipboard-copynote');

            $clipboard_tooltip.appendTo('#container');

            $el.on('click', function(e){
                e.preventDefault();
                e.stopPropagation();
            });

            clipboard.on('success', function(e) {
                e.clearSelection();
                gsap.fromTo($clipboard_tooltip, .2, {autoAlpha: 0}, {autoAlpha: 1});

                setTimeout(function(){
                    gsap.fromTo($clipboard_tooltip, .2, {autoAlpha: 1}, {autoAlpha: 0});
                }, 1500);
            });
        
	    }
    })

}



function tabs(){

    const tabs = document.querySelectorAll('.tab-item');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('tab-menu--active'));
        tab.classList.add('tab-menu--active');
        const target = tab.getAttribute('data-target');
        contents.forEach(content => {
          if (content.id === target) {
            content.classList.add('tab-content--active');
          } else {
            content.classList.remove('tab-content--active');
          }
        });
      });
    });

}




function small_nav(){

    var $body          = $('body'),
	    $menuBtn       = $('.small-menu-controller'),
	    $menuContainer = $('.small-menu-container'),
        $menuScroll    = $('.global-menu-nav'),
	    $menu          = $('#menu');

    
    // Menu button
    $menuBtn.on('click', function(e){
        e.preventDefault();
        if( !$body.hasClass('small-menu-open') ){
            open_menu();
        } else {
            close_menu();
        }
    });

     if( matchMedia('screen and (max-width: 1023px)').matches ){ 
        // toggle 2depth menu
        $menu.on('click', '> li.has-sub-menu > a', function(e){

            e.preventDefault();
            e.stopPropagation()

            var $this = $(this);
            var $li = $this.closest('li');
            var $child = $li.find('> ul');

            if( !$child.is(':visible') ) {

                $('.all-menu__list > li').removeClass('menu-children--open');
                $li.addClass('menu-children--open');

                $('.all-menu__list ul.sub-menu').stop().slideUp();
                $child.stop().slideDown();

            } else {
                $li.removeClass('menu-children--open');
                $child.stop().slideUp();
            }

        });
     }

    // Open action
    function open_menu(){

        $body.addClass('small-menu-open');
        $menuBtn.addClass('active');

		// Scroll
        var scrollStorage = $(window).scrollTop();
        $body.addClass('small-menu-open-fixed').attr('data-scrolltop' , scrollStorage);
        $menuContainer.removeAttr('style');

        if( $('html').hasClass('mobile') && $(window).width() <= 540 ) {
            setTimeout(function(){
                $body.css('position', 'fixed');
            }, 300);
        }

        // Active menu check
		var $active = null;

		$menu.find('> li').each(function(){
			if( $(this).hasClass('current-menu-item') ){
				$active = $(this);
                $active.addClass('menu-children--open');

				/*if( $active.find('> ul').length > 0 ) {
					gsap.set($active.find('> ul'), { display: 'block', autoAlpha: 1 });
				}*/

				return false;
			}
		});

        gsap.fromTo($menuContainer, .1, {autoAlpha: 0}, {
            autoAlpha: 1,
            onStart: function() {
                $menuContainer.css('display', 'block');
            }
        });

        /*// Show
		gsap.fromTo($menuContainer, {
		    autoAlpha: 0
		}, {
		    autoAlpha: 1,
		    duration: .3,
		    ease: 'power3.out',
		    onStart: function () {
		        $menuContainer.css('display', 'block');

                if( $active != null && $active.index() >= 2 ) {
                    gsap.set($menu, { scrollTo: $active.offset().top - $active.height() });
                } else {
		            gsap.set($menu, { scrollTo: 0 });
                }
		    }
		});*/

    }

    // Close action
    function close_menu(){

        $menuBtn.removeClass('active');

		gsap.to($menuContainer, {
			autoAlpha:0,
            duration: .2,
			ease: 'power3.out',
			onStart: function(){
                if( $('html').hasClass('mobile') && $(window).width() <= 540 ) { $body.removeAttr('style'); }
				window.scrollTo(0 , $body.attr('data-scrolltop'));
				$body.removeClass('small-menu-open');
                $menuContainer.css('display','none');
			},
            onComplete: function() {
                //$menuContainer.css('display','none');

                $menuContainer.find('li.has-sub-menu').removeClass('menu-children--open');
				//$menuContainer.find('ul.sub-menu').css('display','none');
            }
        });
    }

    $(window).on('resize', function(){
        if( matchMedia('screen and (min-width: 1024px)').matches ){ 
            close_menu()
        }
    });


}



function menu_layout_setting(){

    //$('.small-menu-container__inner').append($('.global-menu-util__login-bf').clone());
    //$('.small-menu-container__inner').append($('.global-menu-util__login-af').clone());
    //$('.small-menu-container__inner').append($('#menu').clone().removeAttr('id').addClass('small-screen-menu'));
    $('.small-menu-container').append($('.global-menu-util__sns').clone());

}



function selectric() {

    $('.selectric').each(function() {
        $selectric = $(this);

        $selectric.selectric({
            disableOnMobile: false,
            nativeOnMobile: true,
        });
    });

}



function custom_scroll_init() {
   $('.scrollbar-inner').scrollbar();
}



function main_visual() {

    var $slider = $('.triple-slider__contents');
    var $state = $('.main-visual').find('.swiper-play-state');

    if( !$slider.length ){ return; }

    /* triple slide clone
    if(!$('.triple-slider__contents').hasClass('main-visual__left')){
        $('.main-visual__center').clone().prependTo('.triple-slider').addClass('main-visual__left').removeClass('main-visual__center');
        $('.main-visual__left .main-visual__slide:last-child').insertBefore( $('.main-visual__left .main-visual__slide:eq(0)') );
    }
    if(!$('.triple-slider__contents').hasClass('main-visual__right')){
        $('.main-visual__center').clone().appendTo('.triple-slider').addClass('main-visual__right').removeClass('main-visual__center');
        $('.main-visual__right .main-visual__slide:first-child').insertAfter( $('.main-visual__right .main-visual__slide:last-child') );
    } */

    var swiperCenter = new Swiper('.main-visual__center', {
      loop: true,
      centeredSlides: true,
      allowTouchMove: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      speed: 800,
      // loopedSlides: 3,
        pagination: {
            el: $('.main-visual .swiper-pagination'),
                clickable: true,
            renderBullet: function (index, className) {
              return '<button type="button" class="' + className + '"><span>' + (index + 1) + '</span></button>';
            },
        },
        navigation: {
            nextEl: $('.main-visual .swiper-button-next'),
            prevEl: $('.main-visual .swiper-button-prev')
        },
    });

    /*var swiperSide = new Swiper('.main-visual__left, .main-visual__right', {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 0,
      speed: 800,
      allowTouchMove: false,
      // loopedSlides: 3,
    });
    swiperCenter.controller.control = swiperSide;*/

    var swiperTextSide = new Swiper('.main-visual__txt', {
      loop: true,
      slidesPerView: 1,
      spaceBetween: 0,
      speed: 800,
      effect: 'fade',
      fadeEffect: {
        crossFade: true
      },
    });
    swiperCenter.controller.control = swiperTextSide;


    /*/ Play, Pause
    $state.on('click', function(){
        if($state.hasClass('play')){
            swiperCenter.autoplay.stop();
            $state.removeClass('play').addClass('pause');
            $state.find('.swiper-state--play').focus();
        } else {
            swiperCenter.autoplay.start();
            $state.removeClass('pause').addClass('play');
            $state.find('.swiper-state--pause').focus();
        }
    });*/

}



function main_video(){

    var $slider = $('.main-video__box');
    var $video = $slider.find('.embed-video');
    var player;

    if( !$('.main-video').length ){ return; }

    if( !$video.length ) {
		var autoplay = true;
		var loop = true;
	}else {
		var autoplay = false;
		var loop = true;
	}

    var galleryThumbs = new Swiper('.main-video__pagination', {
        spaceBetween: 0,
        slidesPerView: 'auto',
        direction: 'vertical',
        freeMode: true,
        watchSlidesVisibility: true,
        watchSlidesProgress: true,
        resistance : true,
        resistanceRatio : 0,
        breakpoints: {
          860: {
            direction: 'horizontal',
          }
        },
    });    
    var swiper = new Swiper('.main-video__box', {
        loop: loop,
		autoplay: autoplay,
        effect: 'fade',
        thumbs: {
            swiper: galleryThumbs
        }
    });

    swiper.on('slideChange', function(){
        var data = {event: 'command', func: 'seekTo', args: [0, true]};
        var message = JSON.stringify(data);
        $('.embed-video--youtube.swiper-slide-active').find('iframe')[0].contentWindow.postMessage(message, '*');
    })

    swiper.on('transitionStart', function(){

        $('.embed-video--youtube').find('iframe').each(function () {
            var $this = $(this)
            var youtubePlayer = $this.get(0);
            if (youtubePlayer) {
              youtubePlayer.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
            }
        });

	});

    swiper.on('slideChangeTransitionEnd', function(){
        var youtubePlayer_active = $('.embed-video--youtube.swiper-slide-active').find('iframe').get(0);
        youtubePlayer_active.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
    });

    //youtube
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";

    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    ////추가
        
    var initPlayer = function(element) {
        var player = element.querySelector('.video-iframe');
        //var ytplayer = new YT.Player(player, {
        player = new YT.Player(player, {
            playerVars: {
                'modestbranding': 1,
                'controls': 1,
                'rel': 0,
                'autoplay': 1, 
                'mute' : 1,
            },
            events: {
                'onReady': onPlayerReady, //onReady 상태일 때 작동하는 function이름
                'onStateChange': onPlayerStateChange //onStateChange 상태일 때 작동하는 function이름
            },
            videoId: element.dataset.id
        });

    };

    window.onYouTubePlayerAPIReady = function() {
        var container = document.querySelectorAll('.video-container');
        for (var i = 0; i < container.length; i++) {
            initPlayer(container[i])
        }
    };

    function onPlayerReady(event) {
        console.log('ready')
        //event.target.pauseVideo();
        $('.embed-video--youtube').each(function(){
            if( !$(this).hasClass('swiper-slide-active') ){
                var youtubePlayer = $(this).find('iframe').get(0);
                youtubePlayer.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*')
            }
        });
    }


    var done = false;
    function onPlayerStateChange(event) {

        if(event.data === 1) { // Play
            console.log('play')
        } else if(event.data === 2) { // Pause
            //event.target.pauseVideo();
            console.log('Pause')
        } else if ( event.data == 0 ) { // end
            swiper.slideNext();
            console.log('end')
            event.target.seekTo(0);

        }

        if (event.data == YT.PlayerState.PLAYING && !done) {
            //event.target.pauseVideo();
            done = true;
        }
    }

}








/****
// single slider autoplay slider helper
function slideshow_autoplay_transition(flag){

    var slideshow_autoplay = $('.slideshow-autoplay')[0].swiper;
	var $curr = $(slideshow_autoplay.slides[slideshow_autoplay.activeIndex]);

	if( !!$curr.find('iframe').length && !$('html').hasClass('ie10') ) {
		if( slideshow_autoplay_progress != null ) {
			slideshow_autoplay_progress.kill();
			gsap.set($('.slideshow-autoplay').find('.swiper-progress'), {width: '0%'});
		}
	} else {
		slideshow_autoplay_state(5000);
	}
    //slideshow_autoplay_video_check(flag);
}


function slideshow_autoplay_video_check(first){

    var youtube;
	var slideshow_autoplay = $('.slideshow-autoplay')[0].swiper;

    // previous slide pause
	if( !first ) {
		var $prev_iframe = $(slideshow_autoplay.slides[slideshow_autoplay.previousIndex]).find('iframe');
		if( !!$prev_iframe.length ){
            var youtubePlayer = $prev_iframe.get(0);
            youtubePlayer.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
            //youtube.getCurrentTime(0)
		}
	}

	// current slide play
	var $curr_iframe = $(slideshow_autoplay.slides[slideshow_autoplay.activeIndex]).find('iframe');

	if( !!$curr_iframe.length ){	

        var youtubePlayer = $curr_iframe.get(0);
        //if (youtubePlayer) {
            //youtubePlayer.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');

			//var youtubePlayer = new YT.Player($curr_iframe[0]);

			youtubePlayer.getDuration().then(function(duration){

				youtubePlayer.setCurrentTime(0);
				youtubePlayer.play();

				youtubePlayer.on('timeupdate', function(data) {
					if(data.seconds > 0) {
						youtubePlayer.off('timeupdate');

						//if( $curr_poster.is(':visible') ){
						//	TweenMax.to($curr_poster, .3, {autoAlpha: 0, delay: .3, onComplete: function(){$curr_poster.hide();}});
						//}
					}
				});

				slideshow_autoplay_state(duration*1000);

			});

        //}

        //var youtubePlayer = $('.embed-video--youtube.swiper-slide-active').find('iframe').get(0);
        //youtubePlayer.contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');

         //$('.slideshow-autoplay .swiper-slide').each(function(){

        //});

	}

}*/



/*/////
function youtube_play(callback){

    // load youtube if necessary
	if($('.embed-video--youtube').length <= 0) return;

	var tag = document.createElement('script');
	tag.src = "https://www.youtube.com/iframe_api";

	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

	if(!window['YT']){
		// if youtube api ready do your stuff
		window.onYouTubeIframeAPIReady = function() {
			youtube_iframe_check();
		}
	}else {
		youtube_iframe_check();
	}

}

function youtube_iframe_check(){

	// play if click on the poster
	$('.embed-video--youtube').each(function () {

		var $this = $(this);
        var $iframe = $this.find('iframe');
        var iframe_id = $iframe.attr('id');

		new YT.Player(iframe_id,{
			events: {
				'onReady': function(event){
                    event.target.playVideo();
                    console.log(2)
				}
			}
		});

	});

}*/

////
/*
function slider_pause(){
    slideshow_autoplay_progress.pause()
    $('.slideshow-autoplay .swiper-play-state').removeClass('play').addClass('pause');
}

function slider_play(){
    //e.target.getCurrentTime(0)
    slideshow_autoplay_progress.pause()
    $('.slideshow-autoplay .swiper-play-state').removeClass('play').addClass('pause');
}

function slider_ended(){
    slideshow_autoplay_state(0);
    $('.slideshow-autoplay .swiper-play-state').removeClass('pause').addClass('play');
}

function slideshow_autoplay_state(speed){

    // progress
	var $state = $('.slideshow-autoplay').find('.swiper-play-state');
	var $progress = $('.slideshow-autoplay').find('.swiper-progress');

	if( slideshow_autoplay_progress != null ) { slideshow_autoplay_progress.kill(); }

	slideshow_autoplay_progress = gsap.fromTo($progress, parseInt(speed/1000), {
		width: '0%'
	}, {
		width: '100%',
		ease: Power0.easeNone,
		onStart: function(){
			$state.removeClass('progress-max');
		},
		onComplete: function(){
			$state.addClass('progress-max');
			if($state.hasClass('play')){
				$('.slideshow-autoplay')[0].swiper.slideNext();
                console.log('다음으로넘어가')
			}
		}
	});

}
*/

function main_games(){

    var $slider = $('.main-games');

    if( !$slider.length ){ return; }

    var rwd_slide = "undefined";
    
    rwd_slider_resize = function(){
        if( matchMedia('screen and (min-width: 861px)').matches && rwd_slide == "undefined") {
            if(rwd_slide != "undefined"){
                rwd_slide.destroy();
                rwd_slide = "undefined";
            }

            $slider.find('.main-games__list').removeAttr('style').removeClass('swiper-wrapper');
            $slider.find('.main-games__item').removeAttr('style').removeClass('swiper-slide');

        } else if ( matchMedia('screen and (max-width: 860px)').matches) {

            $slider.find('.main-games__list').removeAttr('style').addClass('swiper-wrapper');
            $slider.find('.main-games__item').removeAttr('style').addClass('swiper-slide');
    
            var swiper = new Swiper('.main-games__inner', {
                spaceBetween: 0,
                slidesPerView: 'auto',
                freeMode: true,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
                resistance : true,
                resistanceRatio : 0,
            }); 
                
        }
    }

    rwd_slider_resize();
    $(window).on('resize', rwd_slider_resize);

}



function main_nfts(){
    var $slider = $('.main-nfts__list');

    if( !$slider.length ){ return; }

    //slide
    var swiper = new Swiper($slider, {
        slidesPerView: "auto",
        freeMode: true,
        //grabCursor: true,
        resistance : true,
        resistanceRatio : 0,
        /*scrollbar: {
          el: '.swiper-scrollbar',
          draggable: true,
        },*/
    });
}


function main_events(){
    var $slider = $('.main-events__list');

    if( !$slider.length ){ return; }

    //slide
    var swiper = new Swiper($slider, {
        slidesPerView: "auto",
        freeMode: true,
        resistance : true,
        resistanceRatio : 0,
    });
}



function launchpad(){

    //progress bar
    $('.mint-progressbar__box:not(.mint-soldout)').each(function(){
        var $this = $(this)
        var progressbar_val = $this.find('.progressbar-item').attr('data-value');
        var soldout_val = $this.find('.progressbar-soldout').attr('data-value');
        var img_half = $this.find('figure').width() / 2

        $this.find('.progressbar-item').css('width', progressbar_val + '%');
        $this.find('figure').css('left', 'calc(' + progressbar_val + '%' + ' - ' + img_half + 'rem' + ')');
        $this.find('.progressbar-soldout').css('width', soldout_val + '%');
    });

    //mint popup
    $('.btn-mint').magnificPopup({
        type: 'inline',
        fixedContentPos: true,
        fixedBgPos: true,
        closeBtnInside: true,
        callbacks: {
            open: function() {
                $('body').addClass('mfp-popup-open');
                //$('html').css('overflow-y','hidden');
            },
            afterClose: function() {
                $('body').removeClass('mfp-popup-open');
                //$('html').removeAttr('style');
            }
        },
        midClick: true
    })


    //mint quantity
    $('.quantity').each(function(){

        var $this = $(this)
        
        $this.find('.count-up').click(function (e) {
            e.preventDefault();
            var inc_value = $this.find('.qty-input').val();
            var value = parseInt(inc_value, 10);
            value = isNaN(value) ? 0 : value;
            //if (value < 1000) {
                value++;
                $this.find('.qty-input').val(value);
           // }
        });

        $this.find('.count-down').click(function (e) {
            e.preventDefault();
            var dec_value = $this.find('.qty-input').val();
            var value = parseInt(dec_value, 10);
            value = isNaN(value) ? 0 : value;
            if (value > 1) {
                value--;
                $this.find('.qty-input').val(value);
            }
        });

    })

}



function nfts_filter(){

    if( $(window).width() < 1024 ) {       
        $('.nfts-filter-popup-open').on('click',function(){
            $('.nfts-filter-popup').show();
            $('.nfts-filter-popup__bg').show();
        })

        $('.nfts-filter-popup__close, .nfts-filter-popup__confirm').on('click',function(){
            $('.nfts-filter-popup').hide();
            $('.nfts-filter-popup__bg').hide();
        })
    } else {
        $('.nfts-filter-popup').show();
    }



    /* 수정전 - magnific popup 
    if( $(window).width() < 1024 ) {

         //filter popup
        $('.nfts-filter-popup-open').magnificPopup({
            type: 'inline',
            //mainClass: 'game_view_intro_popup',
            fixedContentPos: true,
            fixedBgPos: true,
            closeBtnInside: true,
            callbacks: {
                open: function() {
                    $('body').addClass('mfp-popup-open');
                },
                afterClose: function() {
                    $('body').removeClass('mfp-popup-open');

                    //mobile filter popup
                    if(!$('.nfts-filter-popup__body').find('.nfts-filter').length){
                        var contents = $('.nfts-filter')[0].outerHTML
                        $('.nfts-filter-popup__body').prepend(contents);
                    }

                    //$('.nfts-filter-popup__body').find('.nfts-filter').remove();
                }
            },
            midClick: true
        })

        $('.nfts-filter-popup__confirm').on('click',function(){
            $.magnificPopup.close();
        });
        
    } else {
        $.magnificPopup.close();
    }*/
}



function announcement_slide(){

    var $slider = $('.announcement-slide');

    if( !$slider.length ){ return; }
    
    $slider.each(function(){
        //slide
        var swiper = new Swiper($slider, {
            slidesPerView: "auto",
            freeMode: true,
            grabCursor: true,
            resistance : true,
            resistanceRatio : 0,
            navigation: {
                nextEl: $('.game-view__announcement .swiper-button-next'),
                prevEl: $('.game-view__announcement .swiper-button-prev')
            },
        });
    });

}


function game_view_slide(){

    if( !$('.game-view__picture').length ){ return; }

    /*var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";

        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);*/

    var galleryThumbs = new Swiper('.game-view__control', {
        spaceBetween: 0,
        slidesPerView: 'auto',
        freeMode: true,
        watchSlidesVisibility: true,
        watchSlidesProgress: true,
        resistance : true,
        resistanceRatio : 0,
        navigation: {
            nextEl: $('.game-view__control-wrap .swiper-button-next'),
            prevEl: $('.game-view__control-wrap .swiper-button-prev')
        },
    });    
    var swiper = new Swiper('.game-view__slider', {
        effect: 'fade',
        thumbs: {
            swiper: galleryThumbs
        }
    });

    swiper.on('slideChange', function(){
        $('.embed-video--youtube').find('iframe').each(function () {
            var $this = $(this)
            var youtubePlayer = $this.get(0);
            if (youtubePlayer) {
              youtubePlayer.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
            }
        });
	});

    //pagination length check
    if($('.game-view__control .swiper-slide').length < 6){
        $('.game-view__control-wrap .swiper-navigation').remove();
    }
}



function accordion(){

    $('.accordion-item').each(function(){
        if(!$(this).hasClass('accordion-item--active')) $(this).find('.accordion-content').hide();
    });

    // Toggle the accordion
    $('.accordion-list').on('click', '.accordion-title', function(){
  
        var $item = $(this).parent('.accordion-item');

        if($item.hasClass('accordion-item--active')){ // close
            $item.removeClass('accordion-item--active');
            $item.find('.accordion-content').slideUp();
        } else { // open
            $item.addClass('accordion-item--active');
            $item.siblings('.accordion-item').removeClass('accordion-item--active')
            $item.siblings('.accordion-item').find('.accordion-content').slideUp();
            $item.find('.accordion-content').slideDown();
        }
 
        return false;

    });

}



function nfts_collection(){

    $('.btn-enlarge').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});


    $('.nft-utility-popup__close').on('click',function(){
        $.magnificPopup.close();
    });

	/*
    $('.nft-utility-popup-open').magnificPopup({
        type: 'inline',
        showCloseBtn: false,
        closeOnContentClick : false,
        fixedContentPos: true,
        fixedBgPos: true,
        callbacks: {
            open: function() {
                $('body').addClass('mfp-popup-open');
                //$('html').css('overflow-y','hidden');
            },
            afterClose: function() {
                $('body').removeClass('mfp-popup-open');
                //$('html').removeAttr('style');
            }
        },
        midClick: true
    })

    nfts_utility_popup_slider();
    
    function nfts_utility_popup_slider(){

        if( !$('.nft-utility-popup__guide').length ){ return; }

        //table slider
        $('.nft-utility-popup__guide').each(function () {

            var $slider = $(this);

            var nft_utility_slider = new Swiper($slider, {
                init: false,
                spaceBetween: 30,
                speed : 800,
                loop : true,
                loopedSlides: 1,
                observer: true,
                observeParents: true,
                parallax:true,
                simulateTouch:true,
                navigation: {
                    nextEl: $slider.find('.swiper-button-next'),
                    prevEl: $slider.find('.swiper-button-prev')
                },
            });

            nft_utility_slider.on('init', function () {
                //nft_rating
                if( $('.nft-utility-popup').data('rating') == 'basic' ){
                    nft_utility_slider.slideTo(1,10)
                } else if ( $('.nft-utility-popup').data('rating') == 'rare' ){
                    nft_utility_slider.slideTo(2,10)
                } else if ( $('.nft-utility-popup').data('rating') == 'epic' ){
                    nft_utility_slider.slideTo(3,10)
                } else if ( $('.nft-utility-popup').data('rating') == 'unique' ){
                    nft_utility_slider.slideTo(4,10)
                } else if ( $('.nft-utility-popup').data('rating') == 'legend' ){
                    nft_utility_slider.slideTo(5,10)
                }
            });

            nft_utility_slider.init();
            nft_utility_slider.update()

        })

    }
    */    

}



function wallet_swap(){

    //swap select
    $('.swap-select__container select').each(function() {
        $selectric = $(this)
        $selectric_container = $selectric.parents('.swap-select__container');

        $selectric_container.addClass($selectric_container.find('option:selected').val())

        $selectric.on('change', function(){
            $selectric.find('option').each(function() {
                $selectric_container.addClass($(this).val());
                $selectric_container.removeClass($(this).not('option:selected').val());
            });
        });
    });

    //swap input
    $('.swap-input').each(function () {
        var $this = $(this)
        $this.keyup(function(e) {
            //placeholder
            if($this.val().length > 0 ){
                $this.parents('.swap-input__box').addClass('active');
            } else {
                $this.parents('.swap-input__box').removeClass('active');
            }
        })
    });
}


}); // End jQuery
