import http from '../../utils/http';

const app = getApp()

Page({
  data: {
    title: '',
    template: 0,
    applist: []
  },
  onLoad: function () {
    this.home();
  },
  home: function () {
    my.showNavigationBarLoading();
    var that = this;
    http.post('home', { platform: 'aliapp' }).then(function(data){
      that.setData({
        title: data.setting.title,
        template: data.setting.template,
        applist: data.app,
      });
      my.setNavigationBar({
        title: that.data.title,
      });
      my.hideNavigationBarLoading();
      my.stopPullDownRefresh();
    });
  },
  onPullDownRefresh: function () {
    this.home();
  },
  toJump: function (e) {
    var that = this;
    var index = e.currentTarget.dataset.index;
    my.navigateToMiniProgram({
      appId: that.data.applist[index].appid,
      path: that.data.applist[index].path,
      success: (res) => {
    
      },
      fail: (res) => {
    
      }
    });
  },
  onShareAppMessage: function (res) {
    var that = this;
    if (res.from === 'button') {
      console.log(res.target)
    }
    return {
      title: that.data.title,
      path: 'pages/index/index',
      success: function () {
        my.showToast({
          content: '成功',
          type: 'success',
          duration: 1000,
        })
      },
      fail: function () {

      }
    }
  }
})