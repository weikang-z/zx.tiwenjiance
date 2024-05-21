import http from '../../utils/http';

const app = getApp();

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
    wx.showNavigationBarLoading();
    var that = this;
    http.post('home', { platform: 'wxapp' }).then(function(data){
      that.setData({
        title: data.setting.title,
        template: data.setting.template,
        applist: data.app,
      });
      wx.setNavigationBarTitle({
        title: that.data.title,
      });
      wx.hideNavigationBarLoading();
      wx.stopPullDownRefresh();
    });
  },
  onPullDownRefresh: function () {
    this.home();
  },
  onShareAppMessage: function (res) {
    var that = this;
    if (res.from === 'button') {
      console.log(res.target)
    }
    return {
      title: that.data.title,
      path: 'pages/index/index',
      success: function (res) {
        wx.showToast({
          title: '成功',
          icon: 'success',
          duration: 1000,
          mask: true
        })
      },
      fail: function (res) {

      }
    }
  }
})