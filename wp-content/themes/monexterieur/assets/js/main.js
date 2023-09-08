// kick it all off here
(function ($) {
    /**
     * Common DOM Elements which will be used frequently.
     */
    var COMMON = {
        init: function () {
            this.cacheDOM();
            $(window).on('load resize orientationchange', this.reCalcOnResize.bind(this));
        },
        cacheDOM: function () {
            this.$body = $('body');
            this.windowWidth = $(window).width();
        },
        reCalcOnResize: function () {
            // init widowWidth.
            this.windowWidth = $(window).width();
            // rather than writing multiple $(window).on('load resize', function(){} ), integrate it in single event.
            this.fakeHeight();
        },
        fakeHeight: function () {
            /**** Top Gap ****/
            var $hero = $('.hero'),
                heroHeight = $hero.outerHeight(true),
                headerHeight = $('.site-header').outerHeight(true),
                fakeNetHeight = (heroHeight + headerHeight);
            $('.fake-height').css('padding-bottom', fakeNetHeight + 'px');
            $hero.css('top', headerHeight + 'px');
        }
    };


    /**
     * WooCommerce Events
     */
    ME_WOO = {
        init: function () {
            $( document.body ).on( 'updated_wc_div', this.cartPageRefresh.bind(this) );
        },
        cartPageRefresh: function () {
            // $('.custom-cart-form .tbody-products-list').mCustomScrollbar();
        }
    };


    /**
     * Theme Basic JS Code Organization.
     */
    MENEXTERIEUR = {
        common: {
            init: function () {
                // init COMMON variables
                COMMON.init();
                this.hamMenu();
                this.svgSupport();
                this.productQuantityInput();
                this.matchHeight();
            },
            finalize: function () {
                ME_WOO.init();
            },
            hamMenu: function() {
                // responsive ham menu
                $('.ham-icon').on('click', function () {
                    $('body').toggleClass('menu-open');
                });
            },
            svgSupport: function () {
                /**
                 * SVG Support.
                 * Get the SVG from IMG SRC and replace with SVG XML Code.
                 */
                $('img.svg').each(function () {
                    var $img = jQuery(this);
                    var imgID = $img.attr('id');
                    var imgClass = $img.attr('class');
                    var imgURL = $img.attr('src');
                    var imgwidth = $img.attr('width');
                    var imgheight = $img.attr('height');
                    $.get(imgURL, function (data) {
                        // Get the SVG tag, ignore the rest
                        var $svg = $(data).find('svg');
                        // Add replaced image's ID to the new SVG
                        if (typeof imgID !== 'undefined') {
                            $svg = $svg.attr('id', imgID);
                        }
                        // Add replaced image's classes to the new SVG
                        if (typeof imgClass !== 'undefined') {
                            $svg = $svg.attr('class', imgClass + ' replaced-svg');
                            $svg = $svg.attr({
                                width: imgwidth,
                                height: imgheight
                            });
                        }
                        // Remove any invalid XML tags as per http://validator.w3.org
                        $svg = $svg.removeAttr('xmlns:a');
                        // Replace image with new SVG
                        $img.replaceWith($svg);
                    }, 'xml');
                });
            },
            productQuantityInput: function () {
                $("body").on("click", '.wc-quantity .quantity-inc .button', function () {
                    var $button = $(this),
                        $parent = $button.parent(),
                        oldValue = $parent.find('.input').val(),
                        $input = $button.siblings('input[type="number"]'),
                        min = $input.attr('min'),
                        max = $input.attr('max'),
                        step = $input.attr('step');

                    if (!oldValue) {
                        oldValue = 0;
                    }

                    if (typeof undefined === typeof  min || !min) {
                        min = 1;
                    }

                    if (typeof undefined === typeof  max || !max) {
                        max = '';
                    }

                    if (typeof undefined === typeof  step || !step) {
                        step = 1;
                    }

                    var newVal = min;

                    if ($button.text() === "+") {
                        newVal = parseFloat(oldValue) + parseFloat(step);
                        if( max && newVal > parseFloat(max) ) {
                            newVal = oldValue;
                        }
                    } else {
                        // Don't allow decrementing below zero
                        if (oldValue > parseFloat(min)) {
                            newVal = parseFloat(oldValue) - parseFloat(step);
                        } else {
                            newVal = parseFloat(step);
                        }
                    }
                    newVal = newVal.toFixed(2);
                    newVal = newVal.replace('.00', '');
                    $input.val(newVal).trigger('change');
                });
            },
            matchHeight: function () {
                /**
                 * Match Element Height.
                 */
                $('.match-height').matchHeight();
            }
        },
        'home': {
            init: function () {
                this.heroSlider();
                this.productCatSlider();
                this.nosRealisationsSlider();
            },
            heroSlider: function () {
                /**
                 * Front-page banner slider.
                 */
                $('.hero-slider').slick({
                    dots: true,
                    infinite: true,
                    prevArrow: '<div class="prev-arrow"></div>',
                    nextArrow: '<div class="next-arrow"></div>',
                    autoplay: true
                });
            },
            nosRealisationsSlider: function () {
                /**
                 * Nos Realisations Linked Slider.
                 */
                $('.res-slider-left .slider-text-content').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true,
                    infinite: false,
                    asNavFor: '.res-slider-right',
                    prevArrow: '<div class="prev-arrow"></div>',
                    nextArrow: '<div class="next-arrow"></div>',
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                adaptiveHeight: true,
                            }
                        }
                    ]
                });
                $('.res-slider-right').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false,
                    infinite: false,
                    arrows: false,
                    asNavFor: '.res-slider-left .slider-text-content',
                });
            },
            productCatSlider: function () {
                $('.product-slider-wrap').slick({
                    dots: false,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 5,
                    prevArrow: '<div class="prev-arrow"></div>',
                    nextArrow: '<div class="next-arrow"></div>',
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                dots: false,
                                slidesToShow: 1
                            }
                        }
                    ]
                });
            }
        },
        'page-template-tmpl-nos-realisations': {
            init: function () {
                this.gallerySlider();
                $('#realisations-content').on('click', '#realisations-pagination > .me_btn', this.loadMore.bind(this));
            },
            gallerySlider: function () {
                // Slider will be added through AJAX also.
                $('.realisations-gallery-slider').each(function () {
                    var $this = $(this);
                    // if slider is already initialized, do nothing
                    if ($this.hasClass('slick-initialized')) {
                        return true;
                    }
                    $this.slick({
                        dots: false,
                        infinite: false,
                        speed: 300,
                        slidesToShow: 1,
                        prevArrow: '<div class="prev-arrow"></div>',
                        nextArrow: '<div class="next-arrow"></div>',
                    });
                });
            },
            loadMore: function (e) {
                e.preventDefault();
                var $this = $(e.currentTarget);
                var obj = this;
                if ($this.parent().hasClass('loading')) {
                    return;
                }
                $this.parent().addClass('loading').css('opacity', 0.3);
                $('<div>').load($this.attr('href') + ' #nos-realisations-wrapper', function (response, status, xhr) {
                    if ('success' === status) {
                        $this.parent().remove();
                        $('#realisations-content').append($(response).find('#realisations-content').html());
                        obj.gallerySlider();
                    }
                    $this.parent().removeClass('loading').css('opacity', 1);
                });
            }
        },
        'single-product': {
            init: function () {
                $('input[name="mon_attribute_pa_color"]').on('change', this.colorSwatch.bind(this));
                this.slider();
                this.relatedSlider();
            },
            colorSwatch: function (e) {
                e.preventDefault();
                var $this = $(e.currentTarget),
                    value = $this.val(),
                    $select = $this.parent().parent().find('select#pa_color');

                $select.find('option:selected').prop('selected', false);
                $select.find('option[value="' + value + '"]').prop('selected', true).trigger('change');
            },
            slider: function () {
                $('.single-product-gallery').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: false,
                    infinite: true,
                    prevArrow: '<div class="prev-arrow"></div>',
                    nextArrow: '<div class="next-arrow"></div>',
                });
            },
            relatedSlider: function () {
                $('.related-slider').slick({
                    dots: false,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 4,
                    prevArrow: '<div class="prev-arrow"></div>',
                    nextArrow: '<div class="next-arrow"></div>',
                    responsive: [
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });

            }
        },
        'monexterieur-shop': {
            init: function () {
                $('#shop-product-contain .woocommerce-loop-product__title').matchHeight();
                $('#shop-product-contain').on('click', '.woocommerce-pagination  a', this.loadMore.bind(this));
                this.mobileCategory();
            },
            loadMore: function (e) {
                e.preventDefault();
                var $this = $(e.currentTarget);
                if ($this.parent().hasClass('loading')) {
                    return;
                }
                $this.parent().addClass('loading').css('opacity', 0.3);
                $('<div>').load($this.attr('href') + ' #shop-product-contain', function (response, status, xhr) {
                    if ('success' === status) {
                        $this.parent().remove();
                        $('#shop-product-contain').append($(response).find('#shop-product-contain').html());
                    }
                    $this.parent().removeClass('loading').css('opacity', 1);
                });
            },
            mobileCategory: function () {
                $('.mobile-category-select').on('click', function(){
                    $('body').toggleClass('filter-active');
                });
            }
        },
        'tax-product_cat':  {
            init: function () {
                // this page has similar behaviour as Shop page.
                WEN_JS_UTIL.fire('monexterieur-shop');
            },
        },
        'search-results': {
            init: function () {
                // this page has similar behaviour as Shop page.
                WEN_JS_UTIL.fire('monexterieur-shop');
            }
        },
        'woocommerce-cart': {
            init: function () {
                //$('.custom-cart-form .tbody-products-list').mCustomScrollbar();
            }
        }
    };
    //common UTIL this doesn't change
    WEN_JS_UTIL = {
        fire: function (func, funcname, args) {
            var namespace = MENEXTERIEUR; // indicate your obj literal namespace here for standard lets make it abbreviation of current project
            funcname = (funcname === undefined) ? 'init' : funcname;
            if (func !== '' && namespace[func] && typeof namespace[func][funcname] == 'function') {
                namespace[func][funcname](args);
            }
        },
        loadEvents: function () {
            var bodyId = document.body.id;
            // hit up common first.
            WEN_JS_UTIL.fire('common');
            // do all the classes too.
            $.each(document.body.className.split(/\s+/), function (i, classnm) {
                WEN_JS_UTIL.fire(classnm);
                WEN_JS_UTIL.fire(classnm, bodyId);
            });
            WEN_JS_UTIL.fire('common', 'finalize');
        }
    };
    WEN_JS_UTIL.loadEvents();

    $(document).ready(function($){
        $('#primary-menu li a').removeAttr('title');
    });
})(jQuery);