/**
 * Handles theme general events
 * 
 * @package Newsis
 * @since 1.0.0
 */
jQuery(document).ready(function($) {
    "use strict"
    var ajaxUrl = newsisObject.ajaxUrl, _wpnonce = newsisObject._wpnonce, sttOption = newsisObject.stt, query_vars = newsisObject.query_vars, paged = newsisObject.paged, stickeyHeader = newsisObject.sticky_header, stickeyHeaderDown = newsisObject.sticky_header_on_scroll_down;

    if( ! newsisObject.is_customizer ) {
        setTimeout(function() {
            $('body .newsis_loading_box').hide();
        }, 2000);
    } else {
        $('body .newsis_loading_box').hide();
    }

    var nrtl = false
    var ndir = "left"
    if ($('body').hasClass("rtl")) {
        nrtl = true;
        ndir = "right";
    };
    
    // theme trigger modal close
    function newsisclosemodal( elm, callback ) {
        $(document).mouseup(function (e) {
            var container = $(elm);
            if (!container.is(e.target) && container.has(e.target).length === 0) callback();
        });
    }

    // ticker news slider events
    var tc = $( ".ticker-news-wrap" );
    if( tc.length ) {
        tc.find( ".ticker-item-wrap" ).marquee({
            duration: 30000,
            gap: 0,
            delayBeforeStart: 0,
            direction: ndir,
            duplicated: true,
            startVisible: true,
            pauseOnHover: true,
        });
    }

    // top date time
    var timeElement = $( ".top-date-time .time" )
    if( timeElement.length > 0 ) {
        setInterval(function() {
            timeElement.html(new Date().toLocaleTimeString())
        },1000);
    }
    
    // search form and off canvas trigger
    $( "#masthead" ).on( "click", ".off-canvas-trigger", function() {
        // $(this).next().addClass('toggle_show');
        $(this).addClass('slideshow');
        $('body').addClass('off-canvas-active');
    });
    $( "#masthead" ).on( "click", ".off-canvas-trigger.slideshow, .sidebar-toggle .off-canvas-close, .search_close_btn", function() {
        $('.off-canvas-trigger').removeClass('slideshow');
        $('body').removeClass('off-canvas-active');
        $("#masthead .search-wrap").find(".search-results-wrap").remove()
        $("#masthead .search-wrap form").removeClass( 'results-loaded' )
    });

     // search form 
    $( "#masthead" ).on( "click", ".search-trigger", function() {
        $(this).next().slideDown();
        $( '#masthead .search-wrap input[type="search"]' ).focus()
        $(this).addClass('slideshow');
        $('body').addClass('bodynoscroll');
    });

    // live search
    if( newsisObject.livesearch ) {
        var searchContainer = $("#masthead .search-wrap")
        if( searchContainer.length > 0 ) {
            var searchFormContainer = searchContainer.find("form")
            searchContainer.on( 'change, keyup', 'input[type="search"]', function() {
                var searchKey = $(this).val()
                if(searchKey) {
                    $.ajax({
                        method: 'post',
                        url: ajaxUrl,
                        data: {
                            action: 'newsis_search_posts_content',
                            search_key : searchKey.trim(),
                            _wpnonce: _wpnonce
                        },
                        beforeSend: function() {
                            searchFormContainer.addClass( 'retrieving-posts' );
                            searchFormContainer.removeClass( 'results-loaded' )
                        },
                        success : function(res) {
                                searchContainer.find(".search-results-wrap").remove()
                                searchFormContainer.after( res.data.posts )
                                searchFormContainer.removeClass( 'retrieving-posts' ).addClass( 'results-loaded' );
                        },
                        complete: function() {
                            // render search content here
                        }
                    })
                } else {
                    searchContainer.find(".search-results-wrap").remove()
                    searchFormContainer.removeClass( 'results-loaded' )
                }
            })
        }
    }

    $( "#masthead" ).on( "click", ".search-trigger.slideshow", function() {
        $(this).next().slideUp();
        $(this).removeClass('slideshow');
        $('body').removeClass('bodynoscroll');
    });

    $( ".search-popup--style-three #masthead" ).on( "click", ".search-trigger", function() {
        $(this).next().slideDown();
        $(this).addClass('slideshow');
        $('body').addClass('bodynoscroll');
    });

    $( ".search-popup--style-three #masthead" ).on( "click", ".search_close_btn", function() {
        $(this).prev().slideUp();
        $(this).prev().prev().removeClass('slideshow');
        $('body').removeClass('bodynoscroll');
    });


    $( ".search-popup--style-two #masthead" ).on( "click", ".search-trigger", function() {
        $(this).next().fadeIn();
        $(this).addClass('slideshow');
        $('body').addClass('bodynoscroll');
    });

    $( "#masthead" ).on( "click", ".search_close_btn", function() {
        $(this).prev().fadeOut();
        $(this).prev().prev().removeClass('slideshow');
        $('body').removeClass('bodynoscroll');
    });

    newsisclosemodal( $( ".search-wrap" ), function () {
        $( ".search-wrap .search-trigger" ).removeClass( "slideshow" );
        $( ".search-form-wrap" ).slideUp();
        $('body').removeClass('bodynoscroll');
    }); // trigger search close


    newsisclosemodal( $( ".sidebar-toggle-wrap" ), function () {
        $( ".sidebar-toggle-wrap .off-canvas-trigger" ).removeClass( "slideshow" );
        $('body').removeClass('off-canvas-active');
    }); // trigger htsidebar close

    newsisclosemodal( $( ".newsletter-popup-modal" ), function () {
        $( ".newsletter-popup-modal" ).removeClass( "open" );
        $("body").removeClass("newsletter-popup-active")
    }); // trigger header newsletter popup close

    // top header ticker news slider events
    var thtn = $( ".top-ticker-news" );
    if( thtn.length ) {
        var thtnitems = thtn.find( ".ticker-item-wrap" )
        thtnitems.slick({
            dots: false,
            infinite: true,
            rtl: nrtl,
            vertical: true,
            arrows: true,
            autoplay: true,
            nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
            prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`,
        });
    }

    // main banner slider events
    var bc = $( "#main-banner-section" );
    if( bc.length ) {
        var bic = bc.find( ".main-banner-slider" )
        bic.slick({
            dots: false,
            infinite: true,
            rtl: nrtl,
            arrows: true,
            autoplay: true,
            speed: 300,
            nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
            prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`,
        });
    }

    // news carousel events
    var nc = $( ".newsis-section .news-carousel .news-carousel-post-wrap" );
    if( nc.length ) {
        nc.each(function() {
            var _this = $(this)
            var ncDots= _this.data("dots") == '1'
            var ncLoop= _this.data("loop") == '1'
            var ncArrows= _this.data("arrows") == '1'
            var ncAuto  = _this.data("auto") == '1'
            var ncColumns  = _this.data("columns")
            _this.slick({
                dots: ncDots,
                infinite: ncLoop,
                arrows: ncArrows,
                autoplay: ncAuto,
                rtl: nrtl,
                slidesToShow: ncColumns,
                nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
                prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`,
                responsive: [
                  {
                    breakpoint: 1100,
                    settings: {
                      slidesToShow: 3,
                    },
                  },
                  {
                    breakpoint: 768,
                    settings: {
                      slidesToShow: 2,
                    },
                  },
                  {
                    breakpoint: 640,
                    settings: {
                      slidesToShow: 1,
                    },
                  }
                ]
            });
        })
    }

    // Filter posts
     $( ".newsis-section .news-filter" ).each(function() {
        var $scope = $(this), $scopeOptions = $scope.data("args"), newTabs = $scope.find( ".filter-tab-wrapper" ), newTabsContent = $scope.find( ".filter-tab-content-wrapper" );
        newTabs.on( "click", ".tab-title", function() {
          var a = $(this), aT = a.data("tab")
          a.addClass( "isActive" ).siblings().removeClass( "isActive" );
          if( newTabsContent.find( ".tab-content.content-" + aT ).length < 1 ) {
            $scopeOptions.category_id = aT
            $.ajax({
                method: 'get',
                url: ajaxUrl,
                data: {
                    action: 'newsis_filter_posts_load_tab_content',
                    options : JSON.stringify( $scopeOptions ),
                    _wpnonce: _wpnonce
                },
                beforeSend: function() {
                    $scope.addClass( 'retrieving-posts' );
                },
                success : function(res) {
                    if( res.data.loaded ) {
                        newTabsContent.append( res.data.posts )
                        $scope.removeClass( 'retrieving-posts' );
                    }
                },
                complete: function() {
                    newTabsContent.find( ".tab-content.content-" + aT ).show().siblings().hide()
                }
            })
          } else {
            newTabsContent.find( ".tab-content.content-" + aT ).show().siblings().hide()
          }
        })
    })

    // popular posts widgets
    var ppWidgets = $( ".newsis-widget-popular-posts" )
    ppWidgets.each(function() {
        var _this = $(this), parentWidgetContainerId = _this.parents( ".widget.widget_newsis_popular_posts_widget" ).attr( "id" ), parentWidgetContainer = $( "#" + parentWidgetContainerId )
        var ppWidget = parentWidgetContainer.find( ".popular-posts-wrap" );
        if( ppWidget.length > 0 ) {
            var ppWidgetAuto = ppWidget.data( "auto" )
            var ppWidgetArrows = ppWidget.data( "arrows" )
            var ppWidgetLoop = ppWidget.data( "loop" )
            var ppWidgetVertical = ppWidget.data( "vertical" )
            if( ppWidgetVertical == 'vertical' ) {
                ppWidget.slick({
                    vertical: true,
                    slidesToShow: 4,
                    dots: false,
                    infinite: ppWidgetLoop,
                    arrows: ppWidgetArrows,
                    autoplay: ppWidgetAuto,
                    nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
                    prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`
                })
            } else {
                ppWidget.slick({
                    dots: false,
                    infinite: ppWidgetLoop,
                    rtl: nrtl,
                    arrows: ppWidgetArrows,
                    autoplay: ppWidgetAuto,
                    nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
                    prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`
                })
            }  
        }
    })

    // carousel posts widgets
    var cpWidgets = $( ".newsis-widget-carousel-posts" )
    cpWidgets.each(function() {
        var _this = $(this), parentWidgetContainerId = _this.parents( ".widget.widget_newsis_carousel_widget" ).attr( "id" ), parentWidgetContainer
        if( typeof parentWidgetContainerId != 'undefined' ) {
            parentWidgetContainer = $( "#" + parentWidgetContainerId )
            var ppWidget = parentWidgetContainer.find( ".carousel-posts-wrap" );
        } else {
            var ppWidget = _this;
        }
        if( ppWidget.length > 0 ) {
            var ppWidgetAuto = ppWidget.data( "auto" )
            var ppWidgetArrows = ppWidget.data( "arrows" )
            var ppWidgetLoop = ppWidget.data( "loop" )
            var ppWidgetVertical = ppWidget.data( "vertical" )
            if( ppWidgetVertical == 'vertical' ) {
                ppWidget.slick({
                    vertical: true,
                    dots: false,
                    infinite: ppWidgetLoop,
                    arrows: ppWidgetArrows,
                    autoplay: ppWidgetAuto,
                    nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
                    prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`
                })
            } else {
                ppWidget.slick({
                    dots: false,
                    infinite: ppWidgetLoop,
                    rtl: nrtl,
                    arrows: ppWidgetArrows,
                    autoplay: ppWidgetAuto,
                    adaptiveHeight: true,
                    nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
                    prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`
                })
            }  
        }
    })

    // tabbed posts
    var tabpWidgets = $( ".newsis-tabbed-widget-tabs-wrap" )
    tabpWidgets.each(function() {
        var _this = $(this), parentWidgetContainerId = _this.parents( ".widget.widget_newsis_tabbed_posts_widget" ).attr( "id" ), parentWidgetContainer
        if( typeof parentWidgetContainerId != 'undefined' ) {
            parentWidgetContainer = $( "#" + parentWidgetContainerId )
            var tabpWidget = parentWidgetContainer.find( ".newsis-tabbed-widget-tabs-wrap" );
        } else {
            var tabpWidget = _this;
        }
        if( tabpWidget.length > 0 ) {
            tabpWidget.on( "click", ".tabbed-widget-tabs li.tabbed-widget", function() {
                var _this = $(this), tabItem = _this.attr( "tab-item" );
                _this.addClass( "active" ).siblings().removeClass( "active" );
                tabpWidget.find( '.widget-tabs-content div[tab-content="' + tabItem + '"]' ).addClass( "active" ).siblings().removeClass( "active" );
            })
        }
    })

    // news filter tabbed posts
    var nftabpWidgets = $( ".newsis-news-filter-tabbed-widget-tabs-wrap" )
    nftabpWidgets.each(function() {
        var _this = $(this), parentWidgetContainerId = _this.parents( ".widget.widget_newsis_news_filter_tabbed_widget" ).attr( "id" ), parentWidgetContainer
        if( typeof parentWidgetContainerId != 'undefined' ) {
            parentWidgetContainer = $( "#" + parentWidgetContainerId )
            var nftabpWidget = parentWidgetContainer.find( ".newsis-news-filter-tabbed-widget-tabs-wrap" );
        } else {
            var nftabpWidget = _this;
        }
        if( nftabpWidget.length > 0 ) {
            nftabpWidget.on( "click", ".widget-tabs li.widget-tab", function() {
                var _this = $(this), tabItem = _this.attr( "tab-item" );
                _this.addClass( "active" ).siblings().removeClass( "active" );
                nftabpWidget.find( '.tabs-content-wrap div.tabs-content.' + tabItem ).addClass( "show" ).siblings().removeClass( "show" );
            })
        }
    })

    var widgetLoader = $(".newsis-widget-loader")
    if( widgetLoader.length > 0 ) {
        widgetLoader.each(function() {
            var widgetLoaderContainer = $(this), widgetPaged = 1
            widgetLoaderContainer.on( "click", ".load-more", function() {
                var _this = $(this)
                var widget = _this.data("widget")
                var instance = _this.data("instance")
                var widgetParent = _this.parents(".widget_newsis_" + widget)
                $.ajax({
                    method: 'post',
                    url: ajaxUrl,
                    data: {
                        action: 'newsis_' +  widget + '_content',
                        _wpnonce: _wpnonce,
                        instance: JSON.stringify(instance),
                        widgetPaged: widgetPaged
                    },
                    beforeSend: function() {
                        widgetParent.addClass( 'retrieving-posts' );
                        _this.text( "Retrieving posts" )
                    },
                    success : function(res) {
                        if( res.data.loaded ) {
                            if( widgetParent.find(" > div.posts-wrap").length > 0 ) {
                                widgetParent.find(" > div.posts-wrap").append( res.data.posts )
                            } else {
                                widgetParent.find(".nwsmatic-widget-loader").before( res.data.posts )
                            }
                            if( res.data.continue ) {
                                widgetPaged++;
                            } else {
                                _this.remove()
                            }
                        } else {
                            _this.remove()
                        }
                    },
                    complete: function() {
                        _this.text( "Show more" )
                        widgetParent.removeClass( 'retrieving-posts' );
                    }
                })
            })
        })
    }

    // stickey related posts
    if( '.single-related-posts-section-wrap' ) {
      $('.single .entry-footer').waypoint(function(direction) {  
            $('.single-related-posts-section-wrap.related_posts_popup').addClass('related_posts_popup_sidebar');
        }, { offset: + 50 });
    }
    $('.related_post_close').on('click',function(){
      $('.single .single-related-posts-section-wrap.related_posts_popup').removeClass('related_posts_popup_sidebar related_posts_popup');
    });

    // header - theme mode
    var themeModeContainer = $('.blaze-switcher-button')
    if( themeModeContainer.length > 0 ) {
        themeModeContainer.on( 'click', function(){
            var _this = $(this), bodyElement = _this.parents('body')
            console.log( bodyElement )
            _this.toggleClass('active')
            if( bodyElement.hasClass('newsis_dark_mode') ) {
                $.cookie( 'themeMode', 'light', { path: '/' } )
                bodyElement.removeClass('newsis_dark_mode').addClass('newsis_main_body')
            } else {
                $.cookie( 'themeMode', 'dark', { path: '/' } )
                bodyElement.removeClass('newsis_main_body').addClass('newsis_dark_mode')
            }
        })
    }

    // header sticky
    if( stickeyHeader || stickeyHeaderDown ) {
        var lastScroll = 0;
        $(window).on('scroll',function() {  
            var scroll = $(window).scrollTop();
            if(scroll > 50) {
                if( lastScroll - scroll > 0 && stickeyHeader ) {
                    $(".main-header").addClass("fixed_header");
                } else if(lastScroll - scroll < 0 && stickeyHeaderDown) {
                    $(".main-header").addClass("fixed_header");
                } else {
                    $(".main-header").removeClass("fixed_header");
                }
                lastScroll = scroll;
            } else {
                $(".main-header").removeClass("fixed_header");
            }
        });
    }

    // back to top script
    if( sttOption && $( "#newsis-scroll-to-top" ).length ) {
        var scrollContainer = $( "#newsis-scroll-to-top" );
        $(window).scroll(function() {
            if ( $(this).scrollTop() > 800 ) {
                scrollContainer.addClass('show');
            } else {
                scrollContainer.removeClass('show');
            }
        });
        scrollContainer.click(function(event) {
            event.preventDefault();
            // Animate the scrolling motion.
            $("html, body").animate({scrollTop:0},"slow");
        });
    }

    // category archive hide featured post in list
    var featuredPost = $( ".archive.category .featured-post.is-sticky" )
    if( featuredPost.length > 0 ) {
        var postHide = "#post-" + featuredPost.data("id")
        $(postHide).addClass( "sticky-hide" );
    }

    // ads slide block
    var adsSliderContainer = $(".ads-banner.ads-banner-slider")
    if( adsSliderContainer.length > 0 ) {
        adsSliderContainer.slick({
            dots: false,
            infinite: true,
            rtl: nrtl,
            vertical: false,
            arrows: true,
            autoplay: true,
            nextArrow: `<button type="button" class="slick-next"><i class="fas fa-chevron-right"></i></button>`,
            prevArrow: `<button type="button" class="slick-prev"><i class="fas fa-chevron-left"></i></button>`,
        })
    }

    // post format - gallery
    var gallery = $('body.single .wp-block-gallery')
    if( gallery.length > 0 ) {
        gallery.each(function(){
            var _this = $(this)
            var findImageSrc = _this.find('.wp-block-image img')
            var srcArgs = []
            findImageSrc.each(function(){
                srcArgs.push({
                    src: $(this).attr('src'),
                    type: 'image'
                })
            })
            _this.magnificPopup({
                items: srcArgs,
                gallery: {
                    enabled: true
                },
                type: 'image'
            })
        })
    }

    // news filter burger
    var newsFilterContainer = $('.news-filter')
    if( newsFilterContainer.length > 0 ) {
        newsFilterContainer.each(function(){
            $(this).on('click', '.newsis-burger', function(){
                var _this = $(this)
                _this.siblings().toggleClass('isactive')
            })
        })
    }
})