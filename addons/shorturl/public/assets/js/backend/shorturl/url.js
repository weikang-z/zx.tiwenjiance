define(["jquery", "bootstrap", "backend", "table", "form"], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: "shorturl/url/index" + location.search,
                    add_url: "shorturl/url/add",
                    edit_url: "shorturl/url/edit",
                    del_url: "shorturl/url/del",
                    multi_url: "shorturl/url/multi",
                    table: "shorturl"
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: "id",
                sortName: "createtime",
                columns: [
                    [
                        {checkbox: true},
                        {field: 'title', title: __('Title')},
                        {
                            field: 'hash_url',
                            title: __('Hash'),
                            formatter: Table.api.formatter.url,
                        },
                        {
                            field: 'url',
                            title: __('Url'),
                            formatter: Table.api.formatter.url,
                        },
                        {
                            field: 'allow_qq',
                            title: __('Browse Permission'),
                            formatter: function (value, row, index) {
                                var html = '';
                                if (row.allow_qq == 0) {
                                    html += '禁止QQ';
                                } else {
                                    html += '';
                                }
                                if (row.allow_wechat == 0) {
                                    html += '<br>禁止微信';
                                } else {
                                    html += '';
                                }
                                if (row.allow_pc_browser == 0) {
                                    html += '<br>禁止PC';
                                } else {
                                    html += '';
                                }
                                if (row.allow_mobile_browser == 0) {
                                    html += '<br>禁止手机';
                                } else {
                                    html += '';
                                }
                                if (html == '') {
                                    html = '无限制'
                                }
                                return html;
                            },
                        },
                        {field: 'views', title: __('Views')},
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

                        {
                            field: 'expiretime',
                            title: __('Expiretime'),
                            formatter: function (value, row, index) {
                                if (row.expire == 1) {
                                    return row.expiretime_text;
                                } else {
                                    return '未开启';
                                }
                            },
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                        },

                        {field: 'memo', title: __('Memo')},
                        {field: 'status', title: __('Status'), searchList: {"1":__('Normal'),"0":__('Hidden')}, formatter: Table.api.formatter.status},
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

                $.validator.config({
                    rules: {
                        title: function (element) {
                            if (element.value.toString().length > 50) {
                                return '名称不能超过50个字符';
                            }
                        },
                        url: function (element) {
                            if (!element.value.toString().match(/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/)) {
                                return __('要跳转的网址格式不正确（开头带http://或https://');
                            }
                            return $.ajax({
                                url: "shorturl/url/check_url_available",
                                type: "POST",
                                data: {url: element.value},
                                dataType: 'json'
                            });
                        },
                        memo: function (element) {
                            if (element.value.toString()) {
                                if (element.value.toString().length > 80) {
                                    return '备注不能超过80个字符';
                                }
                            }
                        }
                    }
                });

                Form.api.bindevent($("form[role=form]"));

                $(document).on('click', "input[name='row[expire]']", function () {
                    var expire = $(this).val();
                    if (expire == 1) {
                        $("#expire").show();
                    } else if (expire == 0) {
                        $("#expire").hide();
                    }
                });
                $("input[name='row[expire]']:checked").trigger("click");
            }
        }
    };
    return Controller;
});