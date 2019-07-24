<template>
  <div class="paycode">
    <van-row style="text-align: center; margin-top: 3rem;">
      <van-icon name="receive-gift" style="text-align: center; font-size: 4rem; color: red;"></van-icon>
      <div style="margin-top: 1rem;">
        <span v-if="qrcode.type == '0'">{{ seller }}</span>
        <span style="font-size: 2rem;" v-else>￥{{ qrcode.amount }}</span>
      </div>
    </van-row>

    <van-row style="margin-top: 2rem;" v-if="qrcode.type == '1'">
      <van-cell-group>
        <van-cell title="收款理由：">{{ qrcode.qrpay_name }}</van-cell>
      </van-cell-group>
    </van-row>

    <van-row style="margin-top: 2rem;" v-else>
      <van-cell-group>
        <van-cell title=" 付款金额" icon="edit"></van-cell>
        <currency-input
          v-model="qrcode.amount"
          :isGray="isGray"
          :updateGray.sync="isGray"
        ></currency-input>
      </van-cell-group>
    </van-row>

    <van-row style="margin-top: 2rem;">
      <van-col offset="2" span="20">
        <van-button type="primary" block v-on:click="doPay">立即支付</van-button>
      </van-col>
    </van-row>
  </div>
</template>

<script>
import {Toast} from 'vant'
import currencyInput from '@/components/Currency'
import qs from 'qs'

export default {
  name: 'paycode',
  data: function () {
    return {
      qrpay_id: 0,
      isGray: true,
      isReadonly: false,
      seller: '',
      qrcode: [],
      locked: false,
    }
  },
  components: {
    'currency-input': currencyInput
  },
  created: function () {
    this.qrpay_id = window.qrpay_id || 0;
    this.getQrcodeDetail(this.qrpay_id)
  },
  methods: {
    doPay: function () {
      let that = this
      if(that.locked == false){
        that.locked = true
        let amount = parseFloat(that.qrcode.amount)
        if (isNaN(amount)) {
          Toast({
            message: '请输入支付金额',
            forbidClick: true
          })
        }

        axios.post(window.home_url + '/index.php?m=qrpay&a=pay', qs.stringify({
          id: that.qrpay_id,
          amount: that.qrcode.amount,
          _ajax: 1
        }))
          .then(function (response) {
            that.locked = false
            let res = response.data
            if (res.error > 0) {
              alert(res.message)
            }

            // just do it
            if (res.paycode == 'alipay'){
              window.location.href = res.payment
            } else if (res.paycode == 'wxpay') {
              that.callpay(JSON.parse(res.payment))
            }

            // console.log(response);
          })
          .catch(function (error) {
            console.log(error);
          });
      }
    },
    getQrcodeDetail: function (id) {
      let that = this
      axios.get(window.home_url + '/index.php?m=qrpay&id=' + parseInt(id), {
        params: {_ajax: 1}
      })
        .then(function (response) {
          that.seller = response.data.seller;
          that.qrcode = response.data.qrcode;
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    jsApiCall: function (ret) {
      let that = this
      WeixinJSBridge.invoke("getBrandWCPayRequest", ret, function (res) {
        if (res.err_msg == "get_brand_wcpay_request:ok") {
          window.location.href = window.home_url + '/index.php?m=qrpay&a=callback&status=1&id=' + that.qrpay_id
        } else {
          window.location.href = window.home_url + '/index.php?m=qrpay&a=callback&status=0&id=' + that.qrpay_id
        }
      })
    },
    callpay: function (ret) {
      if (typeof WeixinJSBridge == "undefined") {
        if (document.addEventListener) {
          document.addEventListener("WeixinJSBridgeReady", this.jsApiCall(ret), false);
        } else if (document.attachEvent) {
          document.attachEvent("WeixinJSBridgeReady", this.jsApiCall(ret));
          document.attachEvent("onWeixinJSBridgeReady", this.jsApiCall(ret));
        }
      } else {
        this.jsApiCall(ret);
      }
    }
  }
}
</script>

<style scoped>
  .price {
    font-size: 3rem;
    height: 5rem;
    line-height: 4rem;
  }
</style>
