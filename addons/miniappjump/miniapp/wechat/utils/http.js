import {baseUrl} from '../config'

// console.log(baseUrl)

function getPromise(url, data, method) {
  return new Promise((resolve, reject) => {
    wx.request({
      url: baseUrl + url,
      header: {
        'content-type': 'application/json'
      },
      method: method,
      data: data,
      success: function (res) {
        if (res.data.code === 1) {
          resolve(res.data.data)
        } else {
          reject(res.data.msg)
        }
      },
      fail: function (err) {
        reject(err)
      }
    })
  }).catch(function (e) {
    
  })
}

const http = {
  get: function (url, data) {
    return getPromise(url, data, 'GET')
  },
  post: function (url, data) {
    return getPromise(url, data, 'POST')
  }
}

export default http