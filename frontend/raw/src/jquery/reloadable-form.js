import jQuery from 'jquery';

jQuery(document).ready(function($){

    var $forms = $('form[data-reloadable-form]');

    function makeReloadable($form)
    {

        var url = $form.data('reloadable-form-url');

        function initEvents()
        {
            $form.find('[data-click="reload"]').on('click', function(e){
                e.preventDefault();
                reloadForm();
            });
            $form.find('[data-change="reload"]').on('change', function(){
                reloadForm();
            });
        }

        function reloadForm()
        {
            $form
                .css('position', 'relative')
                .append('<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.1); text-align: center; padding-top: 20px;"><i class="fa fa-spinner fa-spin"></i></div>');

            var data = $form.serializeJSON();

            $.ajax({
                method: 'POST',
                url: url,
                data: data,
            }).done(function(html){

                var $newForm = $(html);
                $form.replaceWith($newForm);
                makeReloadable($newForm);
            })
            ;

        }
        initEvents();
    }

    $forms.each(function(){
        makeReloadable($(this));
    });



});