$(function(){

    var $window = $(window);
    var $menuNav = $(".col-sm-12").find($(".meanmenu-reveal"));

    var scrollTime = 0.5;
    var scrollDistance = 70;

    $window.scroll(function (){
        if ($menuNav.hasClass("meanclose")){
            $('.mean-nav ul:first').slideUp();
            menuOn = false;

            $menuNav.toggleClass("meanclose").html("<span /><span /><span />");
        }
    });


    $window.on("mousewheel DOMMouseScroll", function(event){

        event.preventDefault();

        var delta = event.originalEvent.wheelDelta/120 || -event.originalEvent.detail/3;
        var scrollTop = $window.scrollTop();
        var finalScroll = scrollTop - parseInt(delta*scrollDistance);

        TweenMax.to($window, scrollTime, {
            scrollTo : { y: finalScroll, autoKill:true },
            ease: Power1.easeOut,
            autoKill: true,
            overwrite: 5
        });

    });

});