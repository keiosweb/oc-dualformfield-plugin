(function ($, window, undefined) {

    var DualFormWidget = function (element, options) {
        var self = this
        this.options = options
        this.$el = $(element)

        this.$left = this.$el.find('#' + options.leftId)
        this.$right = this.$el.find('#' + options.rightId)

        var formOptionName = options.formOption

        this.$el.find('input[name="' + formOptionName + '"]').change(function () {
            self.showFormOption($(this).val())
        })

        this.initialSide = options.selected

        this.showFormOption(options.selected)
    }

    DualFormWidget.prototype.showFormOption = function (side) {
        switch (side) {
            case 'left':
                this.initialSide !== side ? this.purgeSideInput(side) : null
                this.$left.show()
                this.$left.addClass('dualformwidget-form-selected')
                this.$right.hide()
                this.$right.removeClass('dualformwidget-form-selected')
                break;
            case 'right':
                this.initialSide !== side ? this.purgeSideInput(side) : null
                this.$right.show()
                this.$right.addClass('dualformwidget-form-selected')
                this.$left.hide()
                this.$left.removeClass('dualformwidget-form-selected')
                break;
        }
    }

    DualFormWidget.prototype.purgeSideInput = function (side) {
        this['$' + side].find('input').val('')
    }

    DualFormWidget.DEFAULTS = {}

    // DUALFORMWIDGET PLUGIN DEFINITION
    // ============================

    var old = $.fn.dualFormWidget

    $.fn.dualFormWidget = function (option) {
        var args = Array.prototype.slice.call(arguments, 1)
        return this.each(function () {
            var $this = $(this)
            var data = $this.data('oc.dualformwidget')
            var options = $.extend({}, DualFormWidget.DEFAULTS, $this.data(), typeof option == 'object' && option)
            if (!data) $this.data('oc.dualformwidget', (data = new DualFormWidget(this, options)))
            else if (typeof option == 'string') data[option].apply(data, args)
        })
    }

    $.fn.dualFormWidget.Constructor = DualFormWidget

    // DUALFORMWIDGET NO CONFLICT
    // =================

    $.fn.dualFormWidget.noConflict = function () {
        $.fn.datePicker = old
        return this
    }

    // DUALFORMWIDGET DATA-API
    // ===============

    $(document).on('render', function () {
        $('[data-control="dualformwidget"]').dualFormWidget()
    });

})(jQuery, window);