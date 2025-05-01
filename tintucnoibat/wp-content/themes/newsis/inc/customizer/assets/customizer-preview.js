/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

 ( function( $ ) {
	const themeContstants = {
		prefix: 'newsis_'
	}
	const themeCalls = {
		newsisGenerateStyleTag: function( code, id ) {
			if( code ) {
				if( $( "head #" + id ).length > 0 ) {
					$( "head #" + id ).html( code )
				} else {
					$( "head" ).append( '<style id="' + id + '">' + code + '</style>' )
				}
			}
		},
		newsisGenerateLinkTag: function( action, id ) {
			$.ajax({
				method: "GET",
				url: newsisPreviewObject.ajaxUrl,
				data: ({
					action: action,
					_wpnonce: newsisPreviewObject._wpnonce
				}),
				success: function(response) {
					if( response ) {
						if( $( "head #" + id ).length > 0 ) {
							$( "head #" + id ).attr( "href", response )
						} else {
							$( "head" ).append( '<link rel="stylesheet" id="' + id + '" href="' + response + '"></link>' )
						}
					}
				}
			})
		},
		newsisGenerateTypoCss: function(selector,value) {
			var cssCode = ''
			if( value.font_family ) {
				cssCode += '.newsis_font_typography { ' + selector + '-family: ' + value.font_family.value + '; } '
			}
			if( value.font_weight ) {
				cssCode += '.newsis_font_typography { ' + selector + '-weight: ' + value.font_weight.value + '; } '
			}
			if( value.text_transform ) {
				cssCode += '.newsis_font_typography { ' + selector + '-texttransform: ' + value.text_transform + '; } '
			}
			if( value.text_decoration ) {
				cssCode += '.newsis_font_typography { ' + selector + '-textdecoration: ' + value.text_decoration + '; } '
			}
			if( value.font_size ) {
				if( value.font_size.desktop ) {
					cssCode += '.newsis_font_typography { ' + selector + '-size: ' + value.font_size.desktop + 'px; } '
				}
				if( value.font_size.tablet ) {
					cssCode += '.newsis_font_typography { ' + selector + '-size-tab: ' + value.font_size.tablet + 'px; } '
				}
				if( value.font_size.smartphone ) {
					cssCode += '.newsis_font_typography { ' + selector + '-size-mobile: ' + value.font_size.smartphone + 'px; } '
				}
			}
			if( value.line_height ) {
				if( value.line_height.desktop ) {
					cssCode += '.newsis_font_typography { ' + selector + '-lineheight: ' + value.line_height.desktop + 'px; } '
				}
				if( value.line_height.tablet ) {
					cssCode += '.newsis_font_typography { ' + selector + '-lineheight-tab: ' + value.line_height.tablet + 'px; } '
				}
				if( value.line_height.smartphone ) {
					cssCode += '.newsis_font_typography { ' + selector + '-lineheight-mobile: ' + value.line_height.smartphone + 'px; } '
				}
			}
			if( value.letter_spacing ) {
				if( value.letter_spacing.desktop ) {
					cssCode += '.newsis_font_typography { ' + selector + '-letterspacing: ' + value.letter_spacing.desktop + 'px; } '
				}
				if( value.letter_spacing.tablet ) {
					cssCode += '.newsis_font_typography { ' + selector + '-letterspacing-tab: ' + value.letter_spacing.tablet + 'px; } '
				}
				if( value.letter_spacing.smartphone ) {
					cssCode += '.newsis_font_typography { ' + selector + '-letterspacing-mobile: ' + value.letter_spacing.smartphone + 'px; } '
				}
			}
			return cssCode
		},
	}

	// post title hover class
	wp.customize( 'post_title_hover_effects', function( value ) {
		value.bind( function(to) {
				$( "body" ).removeClass( "newsis-title-none newsis-title-two newsis-title-six " )
				$( "body" ).addClass( "newsis-title-" + to )
		});
	});

	// website layout class
	wp.customize( 'website_layout', function( value ) {
		value.bind( function(to) {
				$( "body" ).removeClass( "site-boxed--layout site-full-width--layout" )
				$( "body" ).addClass( "site-" + to )
		});
	});

	// block title layouts class
	wp.customize( 'website_block_title_layout', function( value ) {
		value.bind( function(to) {
				$( "body" ).removeClass( "block-title--layout-one block-title--layout-four" )
				$( "body" ).addClass( "block-title--" + to )
		});
	});

	// image hover class
	wp.customize( 'site_image_hover_effects', function( value ) {
		value.bind( function(to) {
				$( "body" ).removeClass( "newsis-image-hover--effect-none newsis-image-hover--effect-four newsis-image-hover--effect-eight" )
				$( "body" ).addClass( "newsis-image-hover--effect-" + to )
		});
	});

	// post blocks hover class
	wp.customize( 'post_block_hover_effects', function( value ) {
		value.bind( function(to) {
				$( "body" ).removeClass( "newsis-post-blocks-hover--effect-none newsis-post-blocks-hover--effect-one" )
				$( "body" ).addClass( "newsis-post-blocks-hover--effect-" + to )
		});
	});

	// main banner five trailing layout styles 
	wp.customize( 'main_banner_six_trailing_posts_layout', function( value ) {
		value.bind( function( to ) {
			$("#main-banner-section.banner-layout--six .main-banner-trailing-posts").removeClass( "layout--column layout--row" )
			$("#main-banner-section.banner-layout--six .main-banner-trailing-posts").addClass( "layout--" + to )
		});
	});

	// theme color bind changes
	wp.customize( 'theme_color', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-color-style', '--newsis-global-preset-theme-color')
		});
	});

	// preset 1 bind changes
	wp.customize( 'preset_color_1', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-color-1-style', '--newsis-global-preset-color-1')
		});
	});

	// preset 2 bind changes
	wp.customize( 'preset_color_2', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-color-2-style', '--newsis-global-preset-color-2')
		});
	});

	// preset 3 bind changes
	wp.customize( 'preset_color_3', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-color-3-style', '--newsis-global-preset-color-3')
		});
	});

	// preset 4 bind changes
	wp.customize( 'preset_color_4', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-color-4-style', '--newsis-global-preset-color-4')
		});
	});

	// preset 5 bind changes
	wp.customize( 'preset_color_5', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-color-5-style', '--newsis-global-preset-color-5')
		});
	});

	// preset 6 bind changes
	wp.customize( 'preset_color_6', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-color-6-style', '--newsis-global-preset-color-6')
		});
	});

	// preset 7 bind changes
	wp.customize( 'preset_color_7', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-color-7-style', '--newsis-global-preset-color-7')
		});
	});

	// preset 8 bind changes
	wp.customize( 'preset_color_8', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-color-8-style', '--newsis-global-preset-color-8')
		});
	});

	// preset 9 bind changes
	wp.customize( 'preset_color_9', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-color-9-style', '--newsis-global-preset-color-9')
		});
	});

	// preset 10 bind changes
	wp.customize( 'preset_color_10', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-color-10-style', '--newsis-global-preset-color-10')
		});
	});

	// preset 11 bind changes
	wp.customize( 'preset_color_11', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-color-11-style', '--newsis-global-preset-color-11')
		});
	});

	// preset 12 bind changes
	wp.customize( 'preset_color_12', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-color-12-style', '--newsis-global-preset-color-12')
		});
	});

	// preset gradient 1 bind changes
	wp.customize( 'preset_gradient_1', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-gradient-color-1-style', '--newsis-global-preset-gradient-color-1')
		});
	});

	// preset gradient 2 bind changes
	wp.customize( 'preset_gradient_2', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-gradient-color-2-style', '--newsis-global-preset-gradient-color-2')
		});
	});

	// preset gradient 3 bind changes
	wp.customize( 'preset_gradient_3', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-gradient-color-3-style', '--newsis-global-preset-gradient-color-3')
		});
	});

	// preset gradient 4 bind changes
	wp.customize( 'preset_gradient_4', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-gradient-color-4-style', '--newsis-global-preset-gradient-color-4')
		});
	});

	// preset gradient 5 bind changes
	wp.customize( 'preset_gradient_5', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-gradient-color-5-style', '--newsis-global-preset-gradient-color-5')
		});
	});

	// preset gradient 6 bind changes
	wp.customize( 'preset_gradient_6', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-gradient-color-6-style', '--newsis-global-preset-gradient-color-6')
		});
	});

	// preset gradient 7 bind changes
	wp.customize( 'preset_gradient_7', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-gradient-color-7-style', '--newsis-global-preset-gradient-color-7')
		});
	});

	// preset gradient 8 bind changes
	wp.customize( 'preset_gradient_8', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-gradient-color-8-style', '--newsis-global-preset-gradient-color-8')
		});
	});

	// preset gradient 9 bind changes
	wp.customize( 'preset_gradient_9', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-gradient-color-9-style', '--newsis-global-preset-gradient-color-9')
		});
	});

	// preset gradient 10 bind changes
	wp.customize( 'preset_gradient_10', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-gradient-color-10-style', '--newsis-global-preset-gradient-color-10')
		});
	});

	// preset gradient 11 bind changes
	wp.customize( 'preset_gradient_11', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-gradient-color-11-style', '--newsis-global-preset-gradient-color-11')
		});
	});

	// preset gradient 12 bind changes
	wp.customize( 'preset_gradient_12', function( value ) {
		value.bind( function( to ) {
			helperFunctions.generateStyle(to, 'theme-preset-gradient-color-12-style', '--newsis-global-preset-gradient-color-12')
		});
	});

	// header menu hover effect 
	wp.customize( 'header_menu_hover_effect', function( value ) {
		value.bind( function(to) {
			$( "#site-navigation" ).removeClass( "hover-effect--one hover-effect--none" )
			$( "#site-navigation" ).addClass( "hover-effect--" + to )
		});
	});

	// scroll to top align
	wp.customize( 'stt_alignment', function( value ) {
		value.bind( function(to) {
			$( "#newsis-scroll-to-top" ).removeClass( "align--left align--center align--right" )
			$( "#newsis-scroll-to-top" ).addClass( "align--" + to )
		});
	});

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	});
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	});
	// blog description
	wp.customize( 'blogdescription_option', function( value ) {
		value.bind(function(to) {
			if( to ) {
				$( '.site-description' ).css( {
					clip: 'auto',
					position: 'relative',
				} );
			} else {
				$( '.site-description' ).css( {
					clip: 'rect(1px, 1px, 1px, 1px)',
					position: 'absolute',
				} );
			}
		})
	});

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title' ).css( {
					clip: 'rect(1px, 1px, 1px, 1px)',
					position: 'absolute',
				} );
			} else {
				$( '.site-title' ).css( {
					clip: 'auto',
					position: 'relative',
				} );
				$( '.site-title a' ).css( {
					color: to,
				} );
			}
		} );
	});

	// site title hover color
	wp.customize( 'site_title_hover_textcolor', function( value ) {
		value.bind( function( to ) {
			// $( '.site-title a:hover' ).css( {
			// 	color: to
			// })
		})
	});

	// site description color
	wp.customize( 'site_description_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).css( {
				color: to,
			});
		} );
	});

	// site title typo
	wp.customize('site_title_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--site-title'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-site-title-typo' )
		})
	})

	// site tagline typo
	wp.customize('site_tagline_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--site-tagline'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-site-tagline-typo' )
		})
	})

	// side background color
	wp.customize( 'site_background_color', function( value ) {
		value.bind( function( to ) {
			var value = JSON.parse(to)
			var cssCode = ''
			var selector = '--site-bk-color'
			cssCode += ".newsis_main_body { " + selector + ": " + newsis_get_color_format( value[value.type] ) +  "}";
			themeCalls.newsisGenerateStyleTag( cssCode, 'site-background-style' )
		})
	})

	// category colors text colors
	var parsedCats = newsisPreviewObject.totalCats
	if( parsedCats ) {
		parsedCats = Object.keys( parsedCats ).map(( key ) => { return parsedCats[key] })
		parsedCats.forEach(function(item) {
			wp.customize( 'category_' + item.term_id + '_color', function( value ) {
				value.bind( function(to) {
					var cssCode = ''
					if( to.color ) {
						cssCode += "body article:not(.newsis-category-no-bk) .post-categories .cat-item.cat-" + item.term_id + " { background : " + newsis_get_color_format( to.color ) + " } "
						cssCode += "body .newsis-category-no-bk .post-categories .cat-item.cat-" + item.term_id + " a { color : " + newsis_get_color_format( to.color ) + " } "
					}
					if( to.hover ) {
						cssCode += "body article:not(.newsis-category-no-bk) .post-categories .cat-item.cat-" + item.term_id + ":hover { background : " + newsis_get_color_format( to.hover ) + " } "
						cssCode += "body .newsis-category-no-bk .post-categories .cat-item.cat-" + item.term_id + " a:hover { color : " + newsis_get_color_format( to.hover ) + " } "
					}
					themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-category-' + item.term_id + '-style' )
				})
			})
		})
	}

	// global typography block title
	wp.customize('site_section_block_title_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--block-title'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'site-section-block-title-typo' )
		})
	})

	// global typography post title
	wp.customize('site_archive_post_title_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--post-title'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'site-archive-post-title-typo' )
		})
	})

	// global typography post meta
	wp.customize('site_archive_post_meta_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--meta'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'site-archive-post-meta-typo' )
		})
	})

	// global typography post content
	wp.customize('site_archive_post_content_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--content'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'site-archive-post-content-typo' )
		})
	})

	// card settings enable / disable
	wp.customize('card_settings_option', function( value ) {
		value.bind(function( to ) {
			if( to ) {
				$('body.newsis_main_body').addClass('newsis-iscard')
			} else {
				$('body.newsis_main_body').removeClass('newsis-iscard')
			}
		})
	})

	// card settings box shadow
	wp.customize('card_box_shadow_control', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to && to.option != 'none' ) {
				if( to.type == 'outset' ) {
					cssCode += ".newsis-iscard .newsis-card, .newsis-iscard .widget_block { box-shadow: " + to.hoffset + "px " + to.voffset + "px " + to.blur + "px " + to.spread + "px " + newsis_get_color_format( to.color ) + " } "
				} else {
					cssCode += ".newsis-iscard .newsis-card, .newsis-iscard .widget_block { box-shadow: " + to.type + " " + to.hoffset + "px " + to.voffset + "px " + to.blur + "px " + to.spread + "px " + newsis_get_color_format( to.color ) + " } "
				}
				themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-card-box-shadow' )
			} else {
				cssCode += ".newsis-iscard .newsis-card, .newsis-iscard .widget_block { box-shadow: 0px 0px 0px 0px } "
				themeCalls.newsisGenerateStyleTag( '', 'newsis-card-box-shadow' )
			}
		})
	})

	// card settings box shadow
	wp.customize('card_hover_box_shadow', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to && to.option != 'none' ) {
				if( to.type == 'outset' ) {
					cssCode += ".newsis-iscard .newsis-card:hover, .newsis-iscard .widget_block:hover { box-shadow: " + to.hoffset + "px " + to.voffset + "px " + to.blur + "px " + to.spread + "px " + newsis_get_color_format( to.color ) + " } "
				} else {
					cssCode += ".newsis-iscard .newsis-card:hover, .newsis-iscard .widget_block:hover { box-shadow: " + to.type + " " + to.hoffset + "px " + to.voffset + "px " + to.blur + "px " + to.spread + "px " + newsis_get_color_format( to.color ) + " } "
				}
				themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-card-box-shadow' )
			} else {
				cssCode += ".newsis-iscard .newsis-card:hover, .newsis-iscard .widget_block:hover { box-shadow: 0px 0px 0px 0px } "
				themeCalls.newsisGenerateStyleTag( '', 'newsis-card-box-shadow' )
			}
		})
	})

	// global buttons typography
	wp.customize( 'global_button_typo', function( value ) {
		value.bind( function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--post-link-btn'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'global-buttons-typo' )
		})
	})

	// global buttons font size
	wp.customize('global_button_font_size', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'a.post-link-button i { font-size: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { a.post-link-button i { font-size: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {				
				cssCode += '@media(max-width: 610px) { a.post-link-button i { font-size: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-global-button-font-size' )
		})
	})

	// top header show social icons hover animation
	wp.customize('top_header_social_icons_hover_animation', function(value){
		value.bind(function( to ){
			if( to ) {
				$('body.newsis_main_body .social-icons-wrap').addClass('newsis-show-hover-animation')
			} else {
				$('body.newsis_main_body .social-icons-wrap').removeClass('newsis-show-hover-animation')
			}
		})
	})

	// top header background
	wp.customize( 'top_header_background_color_group', function( value ) {
		value.bind( function(to) {
			var value = JSON.parse( to )
			if( value ) {
				var cssCode = ''
				cssCode += '.newsis_main_body .site-header.layout--default .top-header {' + newsis_get_background_style( value ) + '}'
				themeCalls.newsisGenerateStyleTag( cssCode, 'top-header-background-color' )
			} else {
				themeCalls.newsisGenerateStyleTag( cssCode, 'top-header-background-color' )
			}
		});
	});

	// theme header general settings width layout
	wp.customize( 'header_width_layout', function( value ) {
		value.bind(function( to ){
			if( to == 'full-width' ) {
				$('body.newsis_main_body').removeClass('header-width--contain').addClass('header-width--full-width')
			} else {
				$('body.newsis_main_body').removeClass('header-width--full-width').addClass('header-width--contain')
			}
		});
	});

	// theme header general settings vertical padding
	wp.customize( 'header_vertical_padding', function( value ) {
		value.bind(function( to ){
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --header-padding: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += 'body.newsis_main_body { --header-padding-tablet: ' + to.tablet + 'px }';
			}
			if( to.smartphone ) {
				cssCode += 'body.newsis_main_body { --header-padding-smartphone: ' + to.smartphone + 'px }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-header-vertical-padding' )
		})
	})

	// theme header general settings background
	wp.customize( 'header_background_color_group', function( value ) {
		value.bind( function(to) {
			var value = JSON.parse( to )
			if( value ) {
				var cssCode = ''
				cssCode += 'body.newsis_main_body .site-header.layout--default .site-branding-section, body.newsis_main_body .site-header.layout--default .menu-section {' + newsis_get_background_style( value ) + '}'
				themeCalls.newsisGenerateStyleTag( cssCode, 'header-background-color' )
			} else {
				themeCalls.newsisGenerateStyleTag( cssCode, 'header-background-color' )
			}
		});
	});

	// newsletter show border
	wp.customize( 'header_newsletter_show_border', function( value ) {
		value.bind( function( to ) {
			if( to ) {
				$('body.newsis_main_body .main-header .newsletter-element').addClass('newsis-show-border')
			} else {
				$('body.newsis_main_body .main-header .newsletter-element').removeClass('newsis-show-border')
			}
		})
	})

	// newsletter show hover animation 
	wp.customize( 'header_newsletter_show_hover_animation', function( value ) {
		value.bind( function( to ) {
			if( to ) {
				$('body.newsis_main_body .main-header .newsletter-element').addClass('newsis-show-hover-animation')
			} else {
				$('body.newsis_main_body .main-header .newsletter-element').removeClass('newsis-show-hover-animation')
			}
		})
	})

	// menu options text color
	wp.customize( 'header_menu_color', function( value ) {
		value.bind( function( to ) {
			var color = newsis_get_color_format( to.color )
			var hover = newsis_get_color_format( to.hover )
			themeCalls.newsisGenerateStyleTag( 'body.newsis_main_body { --menu-color : ' + color + '; --menu-color-hover : ' + hover + ' }', 'header-menu-color' )
		})
	})

	// menu options active text color
	wp.customize( 'header_active_menu_color', function( value ) {
		value.bind( function( to ) {
			var color = newsis_get_color_format( to )
			themeCalls.newsisGenerateStyleTag( 'body.newsis_main_body { --menu-color-active : ' + color + ' }', 'header-active-menu-color' )
		})
	})

	// menu options background
	wp.customize( 'header_menu_background_color_group', function( value ) {
		value.bind( function(to) {
			var value = JSON.parse( to )
			if( value ) {
				var cssCode = ''
				cssCode += '.newsis_main_body .site-header.layout--default .menu-section .row, .newsis_main_body .site-header.layout--three .header-smh-button-wrap {' + newsis_get_background_style( value ) + '}'
				themeCalls.newsisGenerateStyleTag( cssCode, 'header-menu-background-color' )
			} else {
				themeCalls.newsisGenerateStyleTag( cssCode, 'header-menu-background-color' )
			}
		});
	});

	// menu options mobile menu color
	wp.customize('header_mobile_menu_button_color', function(value){
		value.bind(function(to){
			var cssCode = '.newsis_main_body #newsis_menu_burger span{ background-color: '+ newsis_get_color_format( to ) +'}'
			cssCode += '.newsis_main_body .menu_txt{ color: '+ newsis_get_color_format( to ) +'}'
			themeCalls.newsisGenerateStyleTag( cssCode, 'header-mobile-menu-button-color' )
		})
	})

	// menu options mobile menu text color
	wp.customize('header_mobile_menu_text_color', function(value){
		value.bind(function(to){
			var cssCode = '@media(max-width: 610px) { .newsis_main_body nav.main-navigation ul.menu li a, nav.main-navigation ul.nav-menu li a{ color: '+ newsis_get_color_format( to ) +'} }'
			themeCalls.newsisGenerateStyleTag( cssCode, 'header-mobile-menu-text-color' )
		})
	})

	// menu options mobile menu background color
	wp.customize('header_mobile_menu_background_color', function(value){
		value.bind(function(to){
			var value = JSON.parse( to )

			var cssCode = '@media(max-width: 610px) { .newsis_main_body .newsis_main_body nav.main-navigation ul.menu, .newsis_main_body nav.main-navigation ul.nav-menu, .newsis_main_body .main-navigation ul.menu ul, .newsis_main_body .main-navigation ul.nav-menu ul { background: '+ newsis_get_color_format( value[value.type] ) +'} }'
			themeCalls.newsisGenerateStyleTag( cssCode, 'header-mobile-menu-background-color' )
		})
	})

	// main menu typo
	wp.customize( 'header_menu_typo', function( value ) {
		value.bind( function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--menu'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'header-menu-typo' )
		})
	})

	// main menu typo
	wp.customize( 'header_sub_menu_typo', function( value ) {
		value.bind( function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--submenu'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'header-sub-menu-typo' )
		})
	})

	// off canvas toggle bar color
	wp.customize( 'header_off_canvas_toggle_color', function( value ) {
		value.bind( function( to ) {
			var color = newsis_get_color_format( to.color )
			var hover = newsis_get_color_format( to.hover )
			themeCalls.newsisGenerateStyleTag( 'body.newsis_main_body { --sidebar-toggle-color : ' + color + '; --sidebar-toggle-color-hover : ' + hover + ' }', 'header-off-canvas-toggle-color' )
		})
	})

	// custom button icon size
	wp.customize('custom_button_icon_size', function( value ) {
		value.bind(function( to ){
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --custom-btn-icon-size: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --custom-btn-icon-size-tab: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --custom-btn-icon-size-mobile: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-custom-button-icon-size' )
		})
	})

	// custom button text typography
	wp.customize('custom_button_text_typo', function( value ) {
		value.bind(function( to ){
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--custom-btn'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'custom-button-text-typo' )
		})
	})
	
	// live search button label
	wp.customize('theme_header_live_search_button_label', function( value ) {
		value.bind(function( to ){
			var parentElement = $('body.newsis_main_body .main-header .search-wrap')
			parentElement.find('.view-all-search-button').text( to )
		})
	})

	// front sections full width vertical spacing top
	wp.customize('full_width_vertical_spacing_top', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --full-width-padding-top: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --full-width-padding-top-tab: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --full-width-padding-top-mobile: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-full-width-vertical-spacing-top' )
		})
	})

	// front sections full width section padding
	wp.customize('full_width_vertical_spacing_bottom', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --full-width-padding-bottom: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --full-width-padding-bottom-tab: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --full-width-padding-bottom-mobile: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-full-width-vertical-spacing-bottom' )
		})
	})

	// leftc rights vertical spacing top
	wp.customize('leftc_rights_vertical_spacing_top', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --leftc_rights-padding-top: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --leftc_rights-padding-top-tab: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --leftc_rights-padding-top-mobile: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-leftc-rights-vertical-spacing-top' )
		})
	})

	// leftc rights vertical spacing bottom
	wp.customize('leftc_rights_vertical_spacing_bottom', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --leftc_rights-padding-bottom: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --leftc_rights-padding-bottom-tab: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --leftc_rights-padding-bottom-mobile: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-leftc-rights-vertical-spacing-bottom' )
		})
	})

	// lefts rightc vertical spacing top
	wp.customize('lefts_rightc_vertical_spacing_top', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --lefts_rightc-padding-top: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --lefts_rightc-padding-top-tab: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --lefts_rightc-padding-top-mobile: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-lefts-rightc-vertical-spacing-top' )
		})
	})

	// lefts rightc vertical spacing bottom
	wp.customize('lefts_rightc_vertical_spacing_bottom', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --lefts_rightc-padding-bottom: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --lefts_rightc-padding-bottom-tab: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --lefts_rightc-padding-bottom-mobile: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-lefts-rightc-vertical-spacing-bottom' )
		})
	})

	// front sections bottom full width vertical spacing top
	wp.customize('bottom_full_width_blocks_vertical_spacing_top', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --bottom-full-width-padding-top: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --bottom-full-width-padding-top-tab: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --bottom-full-width-padding-top-mobile: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-bottom-full-width-blocks-vertical-spacing-top' )
		})
	})

	// front sections bottom full width vertical spacing bottom
	wp.customize('bottom_full_width_blocks_vertical_spacing_bottom', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --bottom-full-width-padding-bottom: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --bottom-full-width-padding-bottom-tab: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --bottom-full-width-padding-bottom-mobile: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-bottom-full-width-blocks-vertical-spacing-bottom' )
		})
	})

	// blogs / archive image ratio
	wp.customize('archive_image_ratio', function( value ) {
		value.bind(function( to ){
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --newsis-archive-image-ratio: ' + to.desktop + ' }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --newsis-archive-image-ratio-tab: ' + to.tablet + ' } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --newsis-archive-image-ratio-mobile: ' + to.smartphone + ' } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-archive-image-ratio' )
		})
	})

	// opinions vertical spacing top
	wp.customize('archive_vertical_spacing_top', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --archive-padding-top: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --archive-padding-top-tab: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --archive-padding-top-mobile: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-archive-vertical-spacing-top' )
		})
	})

	// archive vertical spacing bottom
	wp.customize('archive_vertical_spacing_bottom', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --archive-padding-bottom: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --archive-padding-bottom-tab: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --archive-padding-bottom-mobile: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-archive-vertical-spacing-bottom' )
		})
	})

	// single post show original image
	wp.customize('single_post_show_original_image_option', function( value ) {
		value.bind(function( to ) {
			if( to ) {
				$('body.newsis_main_body.single main#primary .post-inner-wrapper .post-thumbnail').addClass('show-original-image')
			} else {
				$('body.newsis_main_body.single main#primary .post-inner-wrapper .post-thumbnail').removeClass('show-original-image')
			}
		})
	})

	// single post related news title
	wp.customize('single_post_related_posts_title', function( value ) {
		value.bind(function( to ) {
			var parentElement = $('body.newsis_main_body #theme-content .post-inner-wrapper .single-related-posts-section')
			if( parentElement.find( '.newsis-block-title' ).length > 0 ) {
				parentElement.find( '.newsis-block-title span' ).text( to )
			} else {
				parentElement.find( '.related_post_close' ).after('<h2 class="newsis-block-title"><span>'+ to +'</span></h2>')
			}
		})
	})

	// single post image ratio
	wp.customize('single_post_image_ratio', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --newsis-single-image-ratio: ' + to.desktop + ' }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --newsis-single-image-ratio-tab: ' + to.tablet + ' } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --newsis-single-image-ratio-mobile: ' + to.smartphone + ' } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-single-post-image-ratio' )
		})
	})

	// single post post title typo
	wp.customize('single_post_title_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--single-title'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-single-post-title-typo' )
		})
	})

	// single post post meta typo
	wp.customize('single_post_meta_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--single-meta'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-single-post-meta-typo' )
		})
	})

	// single post post content typo
	wp.customize('single_post_content_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--single-content'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-single-post-content-typo' )
		})
	})

	// single post h1 typo
	wp.customize('single_post_content_h1_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--single-content-h1'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-single-post-content-h1-typo' )
		})
	})

	// single post h2 typo
	wp.customize('single_post_content_h2_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--single-content-h2'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-single-post-content-h2-typo' )
		})
	})

	// single post h3 typo
	wp.customize('single_post_content_h3_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--single-content-h3'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-single-post-content-h3-typo' )
		})
	})

	// single post h4 typo
	wp.customize('single_post_content_h4_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--single-content-h4'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-single-post-content-h4-typo' )
		})
	})

	// single post h5 typo
	wp.customize('single_post_content_h5_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--single-content-h5'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-single-post-content-h5-typo' )
		})
	})

	// single post h6 typo
	wp.customize('single_post_content_h6_typo', function( value ) {
		value.bind(function( to ) {
			ajaxFunctions.typoFontsEnqueue()
			var cssCode = ''
			var selector = '--single-content-h6'
			cssCode = themeCalls.newsisGenerateTypoCss(selector,to)
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-single-post-content-h6-typo' )
		})
	})

	// single page show original image
	wp.customize('single_page_show_original_image_option', function( value ) {
		value.bind(function( to ) {
			if( to ) {
				$('body.newsis_main_body.page main#primary .post-inner-wrapper .post-thumbnail').addClass('show-original-image')
			} else {
				$('body.newsis_main_body.page main#primary .post-inner-wrapper .post-thumbnail').removeClass('show-original-image')
			}
		})
	})

	// page settings image ratio
	wp.customize('single_page_image_ratio', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --newsis-page-image-ratio: ' + to.desktop + ' }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --newsis-page-image-ratio-tab: ' + to.tablet + ' } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --newsis-page-image-ratio-mobile: ' + to.smartphone + ' } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-page-settings-image-ratio' )
		})
	})
	
	// theme footer vertical spacing top
	wp.customize('footer_vertical_spacing_top', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --footer-padding-top: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --footer-padding-top-tab: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --footer-padding-top-mobile: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-theme-footer-vertical-spacing-top' )
		})
	})

	// theme footer vertical spacing bottom
	wp.customize('footer_vertical_spacing_bottom', function( value ) {
		value.bind(function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body.newsis_main_body { --footer-padding-bottom: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body.newsis_main_body { --footer-padding-bottom-tab: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body.newsis_main_body { --footer-padding-bottom-mobile: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-theme-footer-vertical-spacing-bottom' )
		})
	})

	// bottom footer background
	wp.customize('bottom_footer_background_color_group', function( value ) {
		value.bind(function( to ) {
			var value = JSON.parse( to )
			if( value ) {
				var cssCode = ''
				cssCode += 'body.newsis_main_body .site-footer .bottom-footer {' + newsis_get_background_style( value ) + '}'
				themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-bottom-footer-background-color' )
			} else {
				themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-bottom-footer-background-color' )
			}
		})
	})

	// bottom footer menu option
	wp.customize( 'bottom_footer_menu_option', function( value ) {
		value.bind( function( to ) {
			if( to ) {
				$( '.bottom-footer .bottom-menu' ).show()
			} else {
				$( '.bottom-footer .bottom-menu' ).hide()
			}
		});
	});

	// newsis_preloader_section

	// prloader handler
	wp.customize( 'preloader_type', function( value ) {
		value.bind( function( to ) {
			var loaderItem = $( 'body .newsis_loading_box .loader-item' )
			$( "body .newsis_loading_box" ).show()
			setTimeout( function() {
				$( "body .newsis_loading_box" ).hide()
			}, 2000)
			loaderItem.removeClass();
			loaderItem.addClass( "loader-item loader-" + to )
		});
	});

	// bottom footer copyright align option
	wp.customize( 'bottom_footer_site_info_alignment', function( value ) {
		value.bind( function( to ) {
			$( '.bottom-footer .site-info' ).removeClass( 'align--left align--center align--right' );
			$( '.bottom-footer .site-info' ).addClass( 'align--' + to );
		});
	});

	// single post related posts title
	wp.customize( 'single_post_related_posts_title', function( value ) {
		value.bind( function( to ) {
			$( '.single-related-posts-section .newsis-block-title span' ).text( to );
		} );
	});

	// footer width option
	wp.customize( 'footer_section_width', function( value ) {
		value.bind( function( to ) {
			if( to == 'boxed-width' ) {
				$( 'footer .main-footer' ).removeClass( 'full-width' ).addClass( 'boxed-width' );
				$( 'footer .main-footer .footer-inner' ).removeClass( 'newsis-container-fluid' ).addClass( 'newsis-container' );
			} else {
				$( 'footer .main-footer' ).removeClass( 'boxed-width' ).addClass( 'full-width' );
				$( 'footer .main-footer .footer-inner' ).removeClass( 'newsis-container' ).addClass( 'newsis-container-fluid' );
			}
		});
	});

	// archive page layout
	wp.customize( 'archive_page_layout', function( value ) {
		value.bind( function( to ) {
			$('body').removeClass('post-layout--one post-layout--two')
			$('body').addClass( 'post-layout--' + to )
		});
	});

	// site title hover color
	wp.customize( 'site_title_hover_textcolor', function( value ) {
		value.bind( function( to ) {
			var color = newsis_get_color_format( to )
			themeCalls.newsisGenerateStyleTag( 'header .site-title a:hover { color : ' + color + ' }', 'newsis-site-title-hover-color' )
		})
	})

	// site title hover color
	wp.customize( 'newsis_site_logo_width', function( value ) {
		value.bind( function( to ) {
			var cssCode = ''
			if( to.desktop ) {
				cssCode += 'body .site-branding img.custom-logo { width: ' + to.desktop + 'px }';
			}
			if( to.tablet ) {
				cssCode += '@media(max-width: 940px) { body .site-branding img.custom-logo { width: ' + to.tablet + 'px } }';
			}
			if( to.smartphone ) {
				cssCode += '@media(max-width: 610px) { body .site-branding img.custom-logo { width: ' + to.smartphone + 'px } }';
			}
			themeCalls.newsisGenerateStyleTag( cssCode, 'newsis-site-logo-width' )
		})
	})

	// check if string is variable and formats 
	function newsis_get_color_format(color) {
		if( color.indexOf( '--newsis-global-preset' ) >= 0 ) {
			return( 'var( ' + color + ' )' );
		} else {
			return color;
		}
	}

	// returns css property and value of background
	function newsis_get_background_style( control ) {
		if( control ) {
		 var cssCode = '', mediaUrl = '', repeat = '', position = '', attachment = '', size = ''
		 switch( control.type ) {
		 case 'image' : 
				  if( 'media_id' in control.image ) mediaUrl = 'background-image: url(' + control.image.media_url + ');'
				 if( 'repeat' in control ) repeat = " background-repeat: "+ control.repeat + ';'
				 if( 'position' in control ) position = " background-position: "+ control.position + ';'
				 if( 'attachment' in control ) attachment = " background-attachment: "+ control.attachment + ';'
				 if( 'size' in control ) size = " background-size: "+ control.size + ';'
				 return cssCode.concat( mediaUrl, repeat, position, attachment, size )
			 break;
		 default: 
		 if( 'type' in control ) return "background: " + newsis_get_color_format( control[control.type] )
	   }
	 }
 }

	// constants
	const ajaxFunctions = {
		typoFontsEnqueue: function() {
			var action = themeContstants.prefix + "typography_fonts_url",id ="newsis-customizer-typo-fonts-css"
			themeCalls.newsisGenerateLinkTag( action, id )
		}
	}

	// constants
	const helperFunctions = {
		generateStyle: function(color, id, variable) {
			if(color) {
				if( id == 'theme-color-style' ) {
					var styleText = 'body.newsis_main_body, body.newsis_dark_mode { ' + variable + ': ' + helperFunctions.getFormatedColor(color) + '}';
				} else {
					var styleText = 'body.newsis_main_body { ' + variable + ': ' + helperFunctions.getFormatedColor(color) + '}';
				}
				if( $( "head #" + id ).length > 0 ) {
					$( "head #" + id).text( styleText )
				} else {
					$( "head" ).append( '<style id="' + id + '">' + styleText + '</style>' )
				}
			}
		},
		getFormatedColor: function(color) {
			if( color == null ) return
			if( color.includes('preset') ) {
				return 'var(' + color + ')'
			} else {
				return color
			}
		}
	}
}( jQuery ) );