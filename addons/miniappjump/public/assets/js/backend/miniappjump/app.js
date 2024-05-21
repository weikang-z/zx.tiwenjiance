define(['jquery', 'bootstrap', 'backend', 'table', 'form', '../../../addons/miniappjump/js/clipboard.min'], function ($, undefined, Backend, Table, Form, Clipboard) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'miniappjump/app/index' + location.search,
                    add_url: 'miniappjump/app/add',
                    edit_url: 'miniappjump/app/edit',
                    del_url: 'miniappjump/app/del',
                    multi_url: 'miniappjump/app/multi',
                    table: 'miniappjump',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                pk: 'id',
                sortName: 'createtime',
                pagination: true,
                commonSearch: false,
                search: true,
                templateView: false,
                clickToSelect: false,
                showColumns: false,
                showToggle: false,
                showExport: false,
                showSearch: false,
                searchFormVisible: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'platform', title: __('Platform'), operate: false, formatter: Table.api.formatter.normal},
                        {field: 'title', title: __('Title')},
                        {field: 'description', title: __('Description')},
                        {field: 'icon', title: __('Icon'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'background', title: __('Background'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'appid', title: __('Appid')},
                        {field: 'path', title: __('Path')},
                        {
                            field: 'createtime',
                            title: __('Createtime'),
                            operate:'RANGE',
                            addclass:'datetimerange',
                            formatter: Table.api.formatter.datetime,
                        },
                        {
                            field: 'updatetime',
                            title: __('Updatetime'),
                            operate:'RANGE',
                            addclass:'datetimerange',
                            formatter: Table.api.formatter.datetime,
                        },                        
                        {field: 'status', title: __('Status'), searchList: {"1":__('Normal'),"0":__('Hidden')}, formatter: Table.api.formatter.status},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ],
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            // 绑定TAB事件
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var platformStr = $(this).attr("href").replace('#', '');
                var options = table.bootstrapTable('getOptions');
                options.pageNumber = 1;
                options.queryParams = function (params) {
                    params.platform = platformStr;
                    return params;
                };
                table.bootstrapTable('refresh', {});
                return false;
            });

        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        app: function () {
            var clipboard = new Clipboard('#Jcopy');
            clipboard.on('success', function(e) {
                Layer.alert(__('复制成功'));
                e.clearSelection();
            });

            $("#miniappjump").on("click", "ul#subaction li input", function () {                
                $.ajax({
                    url: "miniappjump/app/app",
                    type: 'post',
                    data: {platform: $(this).attr("rel")},
                    success: function (data) {
                        // 不显示
                        $(".dropdown-toggle").dropdown('toggle');
                        $("#appidlist").val(data);
                        return false;
                    }
                });
                return false;
            });
        },
        api: {
            bindevent: function () {
                $.validator.config({
                    rules: {
                        
                    }
                });

                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {

            }
        }
    };
    return Controller;
});