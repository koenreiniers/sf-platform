import $ from 'jquery';

$(document).ready(function($){

    var $form = $('form');

    var $anchors = $form.find('.nav-tabs > li > a');
    var $lis = $form.find('.nav-tabs > li');

    function showFirstTabWithErrors()
    {
        $form.find('.nav-tabs > li.has-error > a').first().tab('show');
    }

    function hasError($tabPane)
    {
        var $tab = $tabPane;
        var id = $tab.attr('id');
        var $tabset = $tab.closest('.nav-tabs-custom');
        var $anchor = $tabset.find('a[href="#'+id+'"]');

        $anchor.parent().addClass('has-error');
        $anchor.find('.tab-error-marker').show();
    }

    $form.find('.form-group.has-error').each(function(){
        var $tab = $(this).closest('.tab-pane');
        hasError($tab);
        showFirstTabWithErrors();
    });

    $form.find('button').on('click', function(e){

        $form.find('.tab-error-marker').hide();

        $form.find('.nav-tabs > li').removeClass('has-error');


        $form.find('input:invalid').each(function(e){
            var $tab = $(this).closest('.tab-pane');
            hasError($tab);


            showFirstTabWithErrors();

        });

    });

});