<template>
  <div class="currency">
    <label v-if="label">{{ label }}</label>
    <input
      ref="input"
      v-bind:value="value"
      v-on:input="updateValue($event.target.value)"
      v-on:focus="selectAll"
      v-on:blur="formatValue"
      v-bind:class="{gray: isGray}"
      maxlength="7"
    >
  </div>
</template>

<script>
  import currencyValidator from '@/filters/currency_validator'

  export default {
    name: 'currency',
    props: {
      value: {
        type: Number,
        default: 0
      },
      label: {
        type: String,
        default: '￥'
      },
      isGray: {
        type: Boolean,
        default: true
      },
      isReadonly: {
        type: Boolean,
        default: false
      }
    },
    mounted: function () {
      this.formatValue()
    },
    methods: {
      updateValue: function (value) {
        var result = currencyValidator.parse(value, this.value)
        this.$emit('update:updateGray', (result.value === 0))
        if (result.warning) {
          this.$refs.input.value = result.value
        }
        result.value = result.value >= 10000 ? 9999.99 : result.value
        this.$emit('input', result.value)
      },
      formatValue: function () {
        this.$refs.input.value = currencyValidator.format(this.value)
      },
      selectAll: function (event) {
        // Workaround for Safari bug
        // http://stackoverflow.com/questions/1269722/selecting-text-on-focus-using-jquery-not-working-in-safari-and-chrome
        setTimeout(function () {
          event.target.select()
        }, 0)
      }
    }
  }
</script>

<style scoped>
  .currency {
    display: table;
    position: relative;
    padding: 10px 15px 10px 0;
    box-sizing: border-box;
    background-color: #fff;
    color: #333;
    overflow: hidden;
  }

  .currency label {
    width: 60px;
    height: 60px;
    line-height: 60px;
    font-size: 48px;
    position: absolute;
    top: 10px;
    left: 0;
  }

  .currency input {
    width: 100%;
    height: 60px;
    line-height: 60px;
    margin-left: 60px;
    padding: 0;
    font-size: 48px;
    display: block;
    border: 0;
    resize: none;
    outline: 0;
  }

  .currency .gray {
    color: gray;
  }

</style>
