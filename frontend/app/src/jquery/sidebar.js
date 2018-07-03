import jQuery from 'jquery';

jQuery(document).ready(function($){

    var $sidebar = $('section.sidebar');

    $(document).click(function(event) {
        if(!$(event.target).closest($sidebar).length) {
            hideAll();
        }
    });




    var $menu = $sidebar.find('.menu');
    var $anchors = $menu.find('> li > a');

    function hideAll()
    {
        $sidebar.find('.popout-menu').data('open', false);
        //$anchors.parent().removeClass('active');
        renderAll();
    }

    function renderAll()
    {
        $sidebar.find('.popout-menu').each(function(){

            var $anchor = $(this).parent();

            if(isOpen($(this))) {
                $(this).show();
                $anchor.addClass('open');
            } else {
                $(this).hide();
                $anchor.removeClass('open');
            }
        });
    }

    function isOpen($popoutMenu)
    {
        return $popoutMenu.data('open') === true;
    }

    function isClosed($popoutMenu)
    {
        return !isOpen($popoutMenu);
    }

    function hide($popoutMenu)
    {
        $popoutMenu.data('open', false);
        renderAll();
    }

    function show($popoutMenu)
    {
        hideAll();
        $popoutMenu.data('open', true);


        renderAll();
    }
    function toggle($popoutMenu)
    {
        var open = $popoutMenu.data('open');
        if(open) {
            hide($popoutMenu);
        } else {
            show($popoutMenu);
        }
    }

    $menu.find('li').each(function(){

        var $anchor = $(this).find('> a');
        var $popoutMenu = $(this).find('> .popout-menu');

        $popoutMenu.data('open', false);

        if(!$popoutMenu.length) {
            return;
        }

        $popoutMenu.find('[data-close]').on('click', function(e){
            e.preventDefault();
            hide($popoutMenu);
        });

        $anchor.on('click', function(e){
            e.preventDefault();
            toggle($popoutMenu);
        });

    });

});