define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'editable'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'start_page_config/index' + location.search,
                    add_url: 'start_page_config/add',
                    edit_url: 'start_page_config/edit',
                    del_url: 'start_page_config/del',
                    multi_url: 'start_page_config/multi',
                    import_url: 'start_page_config/import',
                    table: 'start_page_config',
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
                        {field: 'zh_image', title: __('Zh_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'en_image', title: __('En_image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
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
