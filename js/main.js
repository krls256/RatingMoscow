function modal_open(e) {
        var t = $(".modal");
        t.css({
                display: "block"
        }),
        750 < $(window).width() ? t.children(e).css({
                display: "block"
        }).stop().animate({
                top: 150,
                opacity: 1
        }, 200) : t.children(e).css({
                display: "block"
        }).stop().animate({
                top: 0,
                opacity: 1
        }, 200)
}
function refresh() {
        var e = $(".jq-selectbox ul li:first");
        $(".jq-selectbox__select-text").text(e.text()),
        $(".jq-selectbox ul li").removeClass(),
        e.addClass("selected sel"),
        $(".jq-selectbox select").val()
}
function modal_clear() {
        var e = $(".modal");
        return refresh(),
        e.find('input:not([type="hidden"]), textarea').val(""),
        e.find(".active").removeClass("active"),
        e.find(".g-recaptcha").each(function () {
                grecaptcha.reset($(this).data("wi"))
        }),
        e.find(".thank").remove(),
        e.find(".modal__error").css({
                display: "none"
        }).text(),
        !1
}
function modal_close(e) {
        var t, a = $(".modal");
        t = 750 < $(window).width() ? 50 : 0,
        a.children(e).stop().animate({
                top: t,
                opacity: "0"
        }, 200, function () {
                a.css({
                        display: "none"
                }),
                a.find(".modal__error").css({
                        display: "none"
                }).removeClass("success"),
                $(this).css({
                        display: "none"
                }),
                modal_clear()
        })
}
function modal_error(e, t) {
        var a = $(".modal__error");
        a.removeClass("success"),
        "success" == t && a.addClass("success"),
        a.css({
                display: "block"
        }),
        a.text(e)
}
$(document).ready(function () {
        var t;
        $('input[name="phone"]').mask("+7(000) 000-00-00");
        $(".modal__type").styler();

        $(window).on("load resize", function () {
                950 < $(this).width() && ($(".home__search-item").find("*").removeAttr("style"), $("header .header__menu").removeAttr("style"), $("header .button-menu").removeClass("active")),
                $(document).width() <= 950 && $("header .button-menu").on("click", function () {
                        $(this).hasClass("active") ? ($(this).removeClass("active"), $("header .header__menu").stop().animate({
                                left: "100%"
                        }, 300, function () {
                                $(this).removeAttr("style")
                        })) : ($(this).addClass("active"), $("header .header__menu").stop().animate({
                                left: "0"
                        }, 300))
                })
        });

        $(".review_modal, .comment_modal, .request_modal").on("click", function () {
                var e = $(this).data();
                switch (e.type) {
                        case "review":
                                elem = $("#review");
                                break;
                        case "comment":
                                elem = $("#comment");
                                
                                elem.find('[name="type"]').val(e.val);
                                break;
                        case "request":
                                elem = $("#request");
                                
                                elem.find('[name="type"]').val(e.val);
                                break;
                        case "review__hr":
                                elem = $("#review__hr")
                                break;
                }

                elem.find('[name="id"]').val(e.id);
                elem.find('[name="key"]').val(e.key);
                
                modal_open(".window__" + e.type);
        });

        $(".modal__window .close").on("click", function () {
                modal_close($(this).closest(".modal__window"))
        });

        $(".modal").on("click", function (e) {
                var t = $(".modal__window");
                t.is(e.target) || 0 !== t.has(e.target).length || modal_close($(this).closest(".modal__window"))
        });

        $(".modal__like span").on("click", function () {
                var e = $(this).parent();
                e.find("span").hasClass("active") && e.find("span").removeClass("active"),
                $(this).toggleClass("active")
        });

        $(".send-review").on("click", function () {
                var e = $(".modal__like span.active").data("like"),
                    t = $("#review").serialize() + "&like=" + e,
                    a = $(this),
                    s = !0;

                $("#review").find('[data-check="true"]').each(function () {
                        0 == $(this).val().length && (s = !1)
                }),
                e && s ? $.ajax({
                        type: "POST",
                        url: "/ajax?func=new_review",
                        data: t,
                        cache: !1,
                        success: function (e) {
                                "ok" == e ? (modal_clear(), a.closest("form").append('<div class="thank"><span>Спасибо! Ваш отзыв будет опубликован после модерации.</span></div>')) : modal_error(e)
                        }
                }) : modal_error("Пожалуйста заполните все поля.")
        });

        $(".send-comment").on("click", function () {
                var e = $("#comment"),
                        t = !0,
                        a = $(this);
                e.find('[data-check="true"]').each(function () {
                        "" == $(this).val() && (t = !1)
                }),
                1 == t ? $.post("/ajax?func=create_comment", e.serialize(), function (e) {
                        "ok" == e ? (modal_clear(), a.closest("form").append('<div class="thank"><span>Спасибо! Ваш комментарий будет опубликован после модерации.</span></div>')) : modal_error(e)
                }) : modal_error("Пожалуйста заполните все поля.")
        }),


        //Форама отправки заявок
        $(".send-request").on("click", function () {
                var t   = $(this),
                    e   = t.closest("form"),
                    id  = e.find('[name="id"]').val();

                e.find('[data-check="true"]').each(function () {
                        if (0 == $(this).val().length )
                                return modal_error("Пожалуйста заполните все поля.");
                });

                $.post("/ajax?func=new_requests", e.serialize())
                        .then(function (e) {

                                //Список целей
                                var list = {
                                        2:'studia-remontov',
                                        12:'fundament',
                                        13:'blagodat',
                                        15:'remont-express',
                                        16:'mastera-remontov',
                                        17:'art-remont',
                                        19:'sm-remont',
                                        20:'aval',
                                        21:'dobriy-dom',
                                        14:'vira-artstroy'
                                }
            
                                if(e == true){
                                        ym(56028883, 'reachGoal', list[id]);
                                        modal_clear();
                                        t.closest("form").append('<div class="thank"><span>Ваша заявка отправлена представителю компании. Так-же отправили заявку в лучшие компании, рекомендуем обратить на них внимание.</span></div>');
                                        return true;
                                }else{
                                        modal_error(e);
                                }
                        });
        });

        $("#review__hr").on("submit", function (e) { 
                e.preventDefault();

                var $this       = $(this);
                var like        = $this.find('.modal__like span.active').data('like');

                $this.find('[data-check="true"]').each(function() { 
                        if ( $(this).val().length == 0 )
                                return modal_error("Пожалуйста заполните все поля.");
                });

                $.post("/ajax?func=new_reviews_hr", $this.serialize() + "&like=" + like )
                        .then(function(e) { 
                                if (e) {
                                        modal_clear();
                                        $this.append('<div class="thank"><span>Спасибо! Ваш отзыв опубликован.</span></div>');
                                }
                        });
        });

        $(" .home__search-item span, .home__search-item i").on("click", function () {
                $(".home__search").trigger("focus")
        });

        $(".home__search").on("focus", function () {
                $(this).closest(".home__item").addClass("active"),
                950 < $(window).width() ? $(this).parent().stop().animate({
                        width: "100%"
                }, 300) : ($(this).stop().animate({
                        width: "100%",
                        padding: "6px 7px"
                }, 300), setTimeout(function () {
                        $(".home__search-item").stop().animate({
                                width: "100%"
                        }, 200),
                        $(".home__search-item span").fadeIn("fast", "swing")
                }, 100))
        }).on("input", function () {
                var e = $(this).val();
                clearTimeout(t),
                "" == $(this).val() ? ($(this).siblings("span").css({
                        display: "block"
                }), $('input[name="home__search"]').removeAttr("style"), $(".search").stop().animate({
                        top: 50,
                        opacity: 0
                }, 300, function () {
                        $(this).css({
                                display: "none"
                        })
                })) : ($(this).siblings("span").css({
                        display: "none"
                }), t = setTimeout(function () {
                        $.ajax({
                                type: "POST",
                                url: "/ajax?func=search",
                                data: {
                                        value: e
                                },
                                cache: !1,
                                success: function (e) {
                                        $(".search").is(":visible") || ($('input[name="home__search"]').css({
                                                "border-radius": "3px 3px 0 0"
                                        }), $(".search").css({
                                                display: "block"
                                        }).stop().animate({
                                                top: 30,
                                                opacity: 1
                                        }, 300)),
                                        $(".search").html(e)
                                }
                        })
                }, 300))
        });

        $(document).on("click", function (e) {
                var t = $(".home__search-item");
                t.is(e.target) || 0 !== t.has(e.target).length || "" == t.find("input").val() && (950 < $(window).width() ? t.stop().animate({
                        width: "200px"
                }, 300, function () {
                        $(this).closest(".home__item").removeClass("active"),
                        $(this).removeAttr("style")
                }) : (t.find("input").stop().animate({
                        width: "0",
                        padding: "6px 0"
                }, 300, function () {
                        $(this).closest(".home__item").removeClass("active"),
                        $(this).removeAttr("style"),
                        $(".home__search-item span").removeAttr("style")
                }), $(".home__search-item span").fadeOut(250, "swing")))
        });

        $(window).on('scroll load', function(){
          var bannerBlock     = $('.banner-block'),
              navBlcok        = $('.content__item-right > div:nth-child(2)'),
              nav             = ( navBlcok.offset().top + navBlcok.height() + 5 ),
              scrollT         = $(this).scrollTop();

          if(nav < scrollT){
            bannerBlock.css({
                position:'fixed',
                top:15
            });
          }else{
            bannerBlock.removeAttr('style');
          }

        });
});
