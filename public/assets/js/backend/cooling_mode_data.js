define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'editable'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'cooling_mode_data/index' + location.search,
                    add_url: 'cooling_mode_data/add',
                    edit_url: 'cooling_mode_data/edit',
                    del_url: 'cooling_mode_data/del',
                    multi_url: 'cooling_mode_data/multi',
                    import_url: 'cooling_mode_data/import',
                    table: 'cooling_mode_data',
                }
            });

            const table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [
                    [
                                                {field: 'id', title: __('Id')},
                        {field: 'text_zh', title: __('Text_zh'), operate: 'LIKE'},
                        {field: 'text_en', title: __('Text_en'), operate: 'LIKE'},
                        {field: 'switch', title: __('Switch'), searchList: {"1":__('Yes'),"0":__('No')}, table: table, formatter: Table.api.formatter.toggle},
                        {field: 'weigh', title: __('Weigh'), operate: false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
