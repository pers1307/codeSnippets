// Вставить в ready
$(document).on('submit', '.js-ajax-form', function (e) {
    e.preventDefault();
    var $el = $(this),
        data = $el.serialize(),
        method = $(this).attr('method') || 'POST',
        target = $(this).attr('target'),
        $button = $el.find('button.button');

    if ($el.hasClass('loaded')) return false;

    $el.addClass('loaded');
    $button.fadeTo(250, 0.4);

    $.ajax({
        url: target,
        type: method,
        data: data,
        dataType: 'json',
        success: function (response) {
            var $inputText = $('.js-input-text', $el),
                $errorText = $('.js-error-text', $el),
                $errorSummary = $('.js-error-summary', $el),
                $successText = $('.js-success-text', $el);

            $inputText.removeClass('error');
            $errorText.empty().hide();
            $successText.empty().hide();
            $errorSummary.empty().hide();

            response.data = response.data || {};
            response.status = response.status || {};
            response.status.code = response.status.code || 0;
            response.data.errors = response.data.errors || {};
            response.data.content = response.data.content || '';
            response.data.popup = response.data.popup || '';
            response.data.redirect = response.data.redirect || '';
            response.data.block = response.data.block || '';
            response.data.close = response.data.close || '';

            if (response.data.close) Site.modal.close();
            if (response.status.code == 200) {
                if (response.data.redirect !== "") {
                    window.location.href = response.data.redirect;
                    return true;
                } else if (response.data.content !== "" || response.data.popup !== "") {
                    // Replace content if needed
                    if (response.data.content !== "") {
                        var $block = (response.data.block !== "") ? $(response.data.block) : $el.closest('.form-wrapper');
                        $block.html(response.data.content);
                    }

                    // Show popup if needed
                    if (response.data.popup !== "") {
                        Site.modal.close();
                        Site.modal.open(null, {content: response.data.popup});
                    }
                }
            } else {
                if (!$.isEmptyObject(response.data.errors)) {
                    var summary = [];
                    for (var field in response.data.errors) {
                        var $inputText = $('[name="' + field + '"]', $el);
                        var $errorText = $inputText.siblings('.js-error-text');

                        $inputText.addClass('error');
                        if ($errorText.length) {
                            $errorText.text(response.data.errors[field]).show();
                        }
                        summary.push(response.data.errors[field]);
                    }
                    $errorSummary.text(summary.join('<br />')).show();
                }
            }
            $el.removeClass('loaded');
            $button.fadeTo(250, 1);
        },
        error: function (response) {
            $el.removeClass('loaded');
            $button.fadeTo(250, 1);
        }
    });

    return false;
});