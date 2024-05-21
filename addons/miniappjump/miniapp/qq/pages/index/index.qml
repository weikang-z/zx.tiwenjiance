<view class='container'>
  <block qq:if="{{template == 1}}">
    <view class="template1" qq:for="{{applist}}" qq:key="key">
      <navigator hover-class="none" class="navigator" target="miniProgram" open-type="navigate" app-id="{{item.appid}}" path="{{item.path}}">
        <image class='icon' src='{{item.icon}}'></image>
        <view class='app-info'>
          <text class='title'>{{item.title}}</text>
          <view class='desc'>{{item.description}}</view>
        </view>
      </navigator>
    </view>
  </block>

  <block qq:if="{{template == 2}}">
  <view class="template2" qq:for="{{applist}}" qq:key="key">
    <navigator hover-class="none" target="miniProgram" open-type="navigate" app-id="{{item.appid}}" path="{{item.path}}">
      <image class="background" src="{{item.background}}"></image>
      <view class="app-item">
        <view class="app-info">
          <image class="icon" src="{{item.icon}}"></image>
          <view class="title">{{item.title}}</view>
          <view class="desc">{{item.description}}</view>
        </view>
      </view>
    </navigator>
  </view>
  </block>

  <block qq:if="{{template == 3}}">
  <view class="template3" qq:for="{{applist}}" qq:key="key">
    <navigator hover-class="none" target="miniProgram" open-type="navigate" app-id="{{item.appid}}" path="{{item.path}}">
      <view class="app-item">
        <image class="background" mode="aspectFill" src="{{item.background}}"></image>
        <view class="app-info">
          <label class="title">{{item.title}}</label>
          <label class="desc">{{item.description}}</label>
          <label class="open">立即打开</label>
        </view>
      </view>
    </navigator>
  </view>
  </block>

</view>