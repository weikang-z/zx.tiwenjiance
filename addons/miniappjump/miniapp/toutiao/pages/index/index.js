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
    var that = this;
    http.post('home', { platform: 'toutiaoapp' }).then(function(data){
      that.setData({
        title: data.setting.title,
        template: data.setting.template,
        applist: data.app,
      });
      tt.setNavigationBarTitle({
        title: that.data.title,
      });
      tt.stopPullDownRefresh();
    });
  },
  onPullDownRefresh: function () {
    this.home();
  },
  toJump: function (e) {
    var that = this;
    var index = e.currentTarget.dataset.index;
    tt.navigateToMiniProgram({
      appId: that.data.applist[index].appid,
      path: that.data.applist[index].path,
      success: (res) => {
        // console.log(res)
      },
      fail: (res) => {
        console.log(res)
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
      success: function (res) {
        tt.showToast({
          title: '成功',
          icon: 'success',
          duration: 1000,
        })
      },
      fail: function (res) {

      }
    }
  }
})
