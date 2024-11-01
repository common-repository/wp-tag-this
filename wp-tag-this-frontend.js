jQuery(document).ready(function () {
    function bindTagThisList(form, ul) {
        ul.find('a[data-tag]').each(function () {
            var link=jQuery(this);
            var data=link.attr('data-tag');

            link.attr('href', '# Tag This Vote')
            .unbind().click(function () {
                jQuery.post(form.attr('action'), {
                        tagthisajax: "1",
                        tag: data,
                        vote: link.attr('data-vote')},
                    function (data) {
                        tagThisRefreshForm(form, data)
                    });
            });
        });
    }

    function bindTagThisForm(form){
        var ul = form.next('ul');
        bindTagThisList(form, ul);

        //autoCompleteData = el.find(".available-tags").val();
        //el.find("input[type=text]:first").autocomplete({source: autoCompleteData.split(',')});

        form.off('submit.tagthis').on('submit.tagthis',function (e) {
            e.preventDefault();
            var value = jQuery.trim(form.find("input[type=text]:first").val());
            if (value != "") {
                jQuery.post(form.attr('action'), { tagthisajax: "1", tag: value, vote: "add"},
                    function (data) {
                        tagThisRefreshForm(form, data)
                    });
            }
            else {
                alert("Please fill in the suggested tag before submitting.");
            }
            return false;
        });
    }

    function tagThisRefreshForm(form, data){
        //console.log(data);
        var container=jQuery('<div>'+data+'</div>');
        var parent=form.parent();
        parent.html(container.find('.tag-this').html());
        bindTagThisForm(parent.find('form'));
    }

    jQuery(".tagthis-input").each(function () {
        bindTagThisForm(jQuery(this));
    });


});