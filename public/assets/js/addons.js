define([], function () {
    require([], function () {
    //绑定data-toggle=addresspicker属性点击事件

    $(document).on('click', "[data-toggle='addresspicker']", function () {
        var that = this;
        var callback = $(that).data('callback');
        var input_id = $(that).data("input-id") ? $(that).data("input-id") : "";
        var lat_id = $(that).data("lat-id") ? $(that).data("lat-id") : "";
        var lng_id = $(that).data("lng-id") ? $(that).data("lng-id") : "";
        var lat = lat_id ? $("#" + lat_id).val() : '';
        var lng = lng_id ? $("#" + lng_id).val() : '';
        var url = "/addons/address/index/select";
        url += (lat && lng) ? '?lat=' + lat + '&lng=' + lng : '';
        Fast.api.open(url, '位置选择', {
            callback: function (res) {
                input_id && $("#" + input_id).val(res.address).trigger("change");
                lat_id && $("#" + lat_id).val(res.lat).trigger("change");
                lng_id && $("#" + lng_id).val(res.lng).trigger("change");
                try {
                    //执行回调函数
                    if (typeof callback === 'function') {
                        callback.call(that, res);
                    }
                } catch (e) {

                }
            }
        });
    });
});

require(['../addons/bootstrapcontextmenu/js/bootstrap-contextmenu'], function (undefined) {
    if (Config.controllername == 'index' && Config.actionname == 'index') {
        $("body").append(
            '<div id="context-menu">' +
            '<ul class="dropdown-menu" role="menu">' +
            '<li><a tabindex="-1" data-operate="refresh"><i class="fa fa-refresh fa-fw"></i>刷新</a></li>' +
            '<li><a tabindex="-1" data-operate="refreshTable"><i class="fa fa-table fa-fw"></i>刷新表格</a></li>' +
            '<li><a tabindex="-1" data-operate="close"><i class="fa fa-close fa-fw"></i>关闭</a></li>' +
            '<li><a tabindex="-1" data-operate="closeOther"><i class="fa fa-window-close-o fa-fw"></i>关闭其他</a></li>' +
            '<li class="divider"></li>' +
            '<li><a tabindex="-1" data-operate="closeAll"><i class="fa fa-power-off fa-fw"></i>关闭全部</a></li>' +
            '</ul>' +
            '</div>');

        $(".nav-addtabs").contextmenu({
            target: "#context-menu",
            scopes: 'li[role=presentation]',
            onItem: function (e, event) {
                var $element = $(event.target);
                var tab_id = e.attr('id');
                var id = tab_id.substr('tab_'.length);
                var con_id = 'con_' + id;
                switch ($element.data('operate')) {
                    case 'refresh':
                        $("#" + con_id + " iframe").attr('src', function (i, val) {
                            return val;
                        });
                        break;
                    case 'refreshTable':
                        try {
                            if ($("#" + con_id + " iframe").contents().find(".btn-refresh").size() > 0) {
                                $("#" + con_id + " iframe")[0].contentWindow.$(".btn-refresh").trigger("click");
                            }
                        } catch (e) {

                        }
                        break;
                    case 'close':
                        if (e.find(".close-tab").length > 0) {
                            e.find(".close-tab").click();
                        }
                        break;
                    case 'closeOther':
                        e.parent().find("li[role='presentation']").each(function () {
                            if ($(this).attr('id') == tab_id) {
                                return;
                            }
                            if ($(this).find(".close-tab").length > 0) {
                                $(this).find(".close-tab").click();
                            }
                        });
                        break;
                    case 'closeAll':
                        e.parent().find("li[role='presentation']").each(function () {
                            if ($(this).find(".close-tab").length > 0) {
                                $(this).find(".close-tab").click();
                            }
                        });
                        break;
                    default:
                        break;
                }
            }
        });
    }
    $(document).on('click', function () { // iframe内点击 隐藏菜单
        try {
            top.window.$(".nav-addtabs").contextmenu("closemenu");
        } catch (e) {
        }
    });

});
require.config({
    paths: {
        'editable': '../libs/bootstrap-table/dist/extensions/editable/bootstrap-table-editable.min',
        'x-editable': '../addons/editable/js/bootstrap-editable.min',
    },
    shim: {
        'editable': {
            deps: ['x-editable', 'bootstrap-table']
        },
        "x-editable": {
            deps: ["css!../addons/editable/css/bootstrap-editable.css"],
        }
    }
});
if ($("table.table").size() > 0) {
    require(['editable', 'table'], function (Editable, Table) {
        $.fn.bootstrapTable.defaults.onEditableSave = function (field, row, oldValue, $el) {
            var data = {};
            data["row[" + field + "]"] = row[field];
            Fast.api.ajax({
                url: this.extend.edit_url + "/ids/" + row[this.pk],
                data: data
            });
        };
    });
}
require.config({
    paths: {
        'nkeditor': '../addons/nkeditor/js/customplugin',
        'nkeditor-core': '../addons/nkeditor/nkeditor',
        'nkeditor-lang': '../addons/nkeditor/lang/zh-CN',
    },
    shim: {
        'nkeditor': {
            deps: [
                'nkeditor-core',
                'nkeditor-lang'
            ]
        },
        'nkeditor-core': {
            deps: [
                'css!../addons/nkeditor/themes/black/editor.min.css',
                'css!../addons/nkeditor/css/common.css'
            ],
            exports: 'window.KindEditor'
        },
        'nkeditor-lang': {
            deps: [
                'nkeditor-core'
            ]
        }
    }
});
require(['form'], function (Form) {
    var _bindevent = Form.events.bindevent;
    Form.events.bindevent = function (form) {
        _bindevent.apply(this, [form]);
        if ($(Config.nkeditor.classname || '.editor', form).size() > 0) {
            require(['nkeditor', 'upload'], function (Nkeditor, Upload) {
                var getFileFromBase64, uploadFiles;
                uploadFiles = async function (files) {
                    var self = this;
                    for (var i = 0; i < files.length; i++) {
                        try {
                            await new Promise((resolve) => {
                                var url, html, file;
                                file = files[i];
                                Upload.api.send(file, function (data) {
                                    url = Fast.api.cdnurl(data.url, true);
                                    if (file.type.indexOf("image") !== -1) {
                                        self.exec("insertimage", url);
                                    } else {
                                        html = '<a class="ke-insertfile" href="' + url + '" data-ke-src="' + url + '" target="_blank">' + (file.name || url) + '</a>';
                                        self.exec("inserthtml", html);
                                    }
                                    resolve();
                                }, function () {
                                    resolve();
                                });
                            });
                        } catch (e) {

                        }
                    }
                };
                getFileFromBase64 = function (data, url) {
                    var arr = data.split(','), mime = arr[0].match(/:(.*?);/)[1],
                        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
                    while (n--) {
                        u8arr[n] = bstr.charCodeAt(n);
                    }
                    var filename, suffix;
                    if (typeof url != 'undefined') {
                        var urlArr = url.split('.');
                        filename = url.substr(url.lastIndexOf('/') + 1);
                        suffix = urlArr.pop();
                    } else {
                        filename = Math.random().toString(36).substring(5, 15);
                    }
                    if (!suffix) {
                        suffix = data.substring("data:image/".length, data.indexOf(";base64"));
                    }

                    var exp = new RegExp("\\." + suffix + "$", "i");
                    filename = exp.test(filename) ? filename : filename + "." + suffix;
                    var file = new File([u8arr], filename, {type: mime});
                    return file;
                };

                //上传Word图片
                Nkeditor.uploadwordimage = function (index, image) {
                    var that = this;
                    (function () {
                        var file = getFileFromBase64(image);
                        var placeholder = new RegExp("##" + index + "##", "g");
                        Upload.api.send(file, function (data) {
                            that.html(that.html().replace(placeholder, Fast.api.cdnurl(data.url)));
                        }, function (data) {
                            that.html(that.html().replace(placeholder, ""));
                        });
                    }(index, image));
                };

                $(Config.nkeditor.classname || '.editor', form).each(function () {
                    var that = this;
                    var options = $(this).data("nkeditor-options");
                    var editor = Nkeditor.create(that, $.extend({}, {
                        width: '100%',
                        filterMode: false,
                        wellFormatMode: false,
                        allowMediaUpload: true, //是否允许媒体上传
                        allowFileManager: true,
                        allowImageUpload: true,
                        fontSizeTable: ['9px', '10px', '12px', '14px', '16px', '18px', '21px', '24px', '32px'],
                        wordImageServer: typeof Config.nkeditor != 'undefined' && Config.nkeditor.wordimageserver ? "127.0.0.1:10101" : "", //word图片替换服务器的IP和端口
                        formulaPreviewUrl: typeof Config.nkeditor != 'undefined' && Config.nkeditor.formulapreviewurl ? Config.nkeditor.formulapreviewurl : "", //数学公式的预览地址
                        cssPath: Config.site.cdnurl + '/assets/addons/nkeditor/plugins/code/prism.css',
                        cssData: "body {font-size: 13px}",
                        fillDescAfterUploadImage: false, //是否在上传后继续添加描述信息
                        themeType: typeof Config.nkeditor != 'undefined' ? Config.nkeditor.theme : 'black', //编辑器皮肤,这个值从后台获取
                        fileManagerJson: Fast.api.fixurl("/addons/nkeditor/index/attachment/module/" + Config.modulename),
                        items: [
                            'source', 'undo', 'redo', 'preview', 'print', 'template', 'code', 'quote', 'cut', 'copy', 'paste',
                            'plainpaste', 'wordpaste', 'justifyleft', 'justifycenter', 'justifyright',
                            'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
                            'superscript', 'clearhtml', 'quickformat', 'selectall',
                            'formatblock', 'fontname', 'fontsize', 'forecolor', 'hilitecolor', 'bold',
                            'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', 'image', 'multiimage', 'graft',
                            'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap', 'pagebreak',
                            'anchor', 'link', 'unlink', 'remoteimage', 'search', 'math', 'about', 'fullscreen'
                        ],
                        afterCreate: function () {
                            var self = this;
                            //Ctrl+回车提交
                            Nkeditor.ctrl(document, 13, function () {
                                self.sync();
                                $(that).closest("form").submit();
                            });
                            Nkeditor.ctrl(self.edit.doc, 13, function () {
                                self.sync();
                                $(that).closest("form").submit();
                            });
                            //粘贴上传
                            $("body", self.edit.doc).bind('paste', function (event) {
                                var originalEvent;
                                originalEvent = event.originalEvent;
                                if (originalEvent.clipboardData && originalEvent.clipboardData.files.length > 0) {
                                    uploadFiles.call(self, originalEvent.clipboardData.files);
                                    return false;
                                }
                            });
                            //拖拽上传
                            $("body", self.edit.doc).bind('drop', function (event) {
                                var originalEvent;
                                originalEvent = event.originalEvent;
                                if (originalEvent.dataTransfer && originalEvent.dataTransfer.files.length > 0) {
                                    uploadFiles.call(self, originalEvent.dataTransfer.files);
                                    return false;
                                }
                            });
                        },
                        afterChange: function () {
                            $(this.srcElement[0]).trigger("change");
                        },
                        //自定义处理
                        beforeUpload: function (callback, file) {
                            var file = file ? file : $("input.ke-upload-file", this.form).prop('files')[0];
                            Upload.api.send(file, function (data) {
                                var data = {code: '000', data: {url: Fast.api.cdnurl(data.url, true)}, title: '', width: '', height: '', border: '', align: ''};
                                callback(data);
                            });
                        },
                        //错误处理 handler
                        errorMsgHandler: function (message, type) {
                            try {
                                console.log(message, type);
                            } catch (Error) {
                                alert(message);
                            }
                        },
                        uploadFiles: uploadFiles
                    }, options || {}));
                    $(this).data("nkeditor", editor);
                });
            });
        }
    }
});

});