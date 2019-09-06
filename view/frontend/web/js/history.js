define(['jquery'], function ($) {
    'use strict';

    $.widget('mage.history', {
        _create: function () {
            this._deleteall();
            this._deleteSelected();
            this._selectAll();
        },
        _deleteall: function () {
            var self = this.element;
            $(this.options.deleteAllBtn).click(function () {
                $(self).find('.delete-all-input').val(1);
                $(self).submit();
            });
        },
        _deleteSelected: function () {
            var self = this.element;
            $(this.options.deleteSelectedBtn).click(function () {
                var selected_elements = [];
                $(self).find('.checkbox-selection').each(function (k, v) {
                    if ($(v).attr('checked')) {
                        selected_elements.push($(v).data('id'));
                    }
                });
                $(self).find('.delete-selected-input').val(selected_elements.join(','));
            });
        },
        _selectAll: function () {
            $(this.options.selectAllBtn).click(function () {
                if ($(this).attr('checked')) {
                    $('.checkbox-selection').attr('checked', true);
                } else {
                    $('.checkbox-selection').attr('checked', false);
                }
            });
        }
    });

    return $.mage.history;
});