"use strict";

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _instanceof(left, right) { if (right != null && typeof Symbol !== "undefined" && right[Symbol.hasInstance]) { return right[Symbol.hasInstance](left); } else { return left instanceof right; } }

function _classCallCheck(instance, Constructor) { if (!_instanceof(instance, Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/**
 *工具类
 */
var Utils =
/*#__PURE__*/
function () {
  function Utils() {
    _classCallCheck(this, Utils);
  }
  /**
   *只要纯数字字符
   * @param {str} string
   */


  _createClass(Utils, [{
    key: "verifyNumber",
    value: function verifyNumber(str) {
      return str - 0 + '' === str + '';
    }
  }, {
    key: "logout",
    value: function logout() {
      location.href = '/admin.php?s=/Admin/Public/logout.html';
    }
  }, {
    key: "refresh",
    value: function refresh() {
      location.href = location.href;
    }
  }, {
    key: "nullfy2str",
    value: function nullfy2str(data) {
      return data ? data : '';
    }
  }]);

  return Utils;
}();
/**
 * API类 后端交互
 */


var API =
/*#__PURE__*/
function () {
  function API() {
    _classCallCheck(this, API);
  }

  _createClass(API, [{
    key: "api_getDetail",
    value: function api_getDetail(url) {
      return new Promise(function (resolve, reject) {
        $.ajax({
          type: 'GET',
          url: url,
          success: function success(res) {
            res = JSON.parse(res);

            if (res.code) {
              resolve(res);
            } else {
              $.alert('数据错误，请联系技术人员');
            }
          },
          error: function error(err) {
            reject({
              message: err.statusText
            });
          },
          complete: function complete() {}
        });
      });
    }
  }, {
    key: "api_toTgOrder",
    value: function api_toTgOrder(data) {
      return new Promise(function (resolve, reject) {
        $.ajax({
          type: 'post',
          url: '/admin.php?s=/Admin/TgOrder/toTgOrder',
          data: data,
          success: function success(res) {
            res = JSON.parse(res);

            if (res.code) {
              resolve(res);
            } else {
              $.alert('数据错误，请联系技术人员');
            }
          },
          error: function error(err) {
            reject({
              message: err.statusText
            });
          },
          complete: function complete() {}
        });
      });
    }
  }]);

  return API;
}();
/**
 * 封装模态类
 */


var Modal =
/*#__PURE__*/
function (_API) {
  _inherits(Modal, _API);

  //默认未处理
  //时间列表
  //定义事件
  function Modal() {
    var _this;

    _classCallCheck(this, Modal);

    _this = _possibleConstructorReturn(this, _getPrototypeOf(Modal).call(this));

    _defineProperty(_assertThisInitialized(_this), "eventEl", void 0);

    _defineProperty(_assertThisInitialized(_this), "toTgOrderData", {});

    _defineProperty(_assertThisInitialized(_this), "status", 0);

    _defineProperty(_assertThisInitialized(_this), "events", []);

    _defineProperty(_assertThisInitialized(_this), "apiToTgOrder", '');

    _this.windowDeployUtils();

    _this.compatiblePrompt();

    _this.preventOperationEvents();

    _this.initEvents();

    return _this;
  } //定义操作


  _createClass(Modal, [{
    key: "fn200",
    value: function fn200(res) {
      $.pop(res.message, 1500);
    }
  }, {
    key: "fn201",
    value: function fn201(res) {
      $.alert(res.message);
    }
  }, {
    key: "windowDeployUtils",
    value: function windowDeployUtils() {
      if (window) {
        window['_utils'] = new Utils();
      }
    }
    /**
     * 兼容提示
     * ie全不支持Promise
     */

  }, {
    key: "compatiblePrompt",
    value: function compatiblePrompt() {
      if (!window['Promise']) {
        $('.m-accidentalTip').show();
        $.alert('请使用急速模式或谷歌浏览器');
      } else {
        $('.m-accidentalTip').hide();
      }
    } //阻止操作按钮默认事件

  }, {
    key: "preventOperationEvents",
    value: function preventOperationEvents() {
      $('.label-pill').each(function (index, el) {
        if (el.text === '操作') {
          $(el).attr('data-href', el.href);
          $(el).removeAttr('href');
        }
      });
    }
    /**
     * 初始 状态选项
     */

  }, {
    key: "initStatusSelect",
    value: function initStatusSelect(data) {
      data = data || $('#pay_status [selected]').val();
      this.eventEl = $('#pay_status')[0];
      this.eventEl.value = data;
      this.events['pay-status-change'].call(this);
    }
  }, {
    key: "initEvents",
    value: function initEvents() {
      this.events['open'] = this.handleOpen;
      this.events['close'] = this.handleClose;
      this.events['submit'] = this.handleSbumit;
      this.events['pay-status-change'] = this.handleChange;
    }
  }, {
    key: "openModal",
    value: function openModal() {
      $('#order .m-modal').show();
    }
  }, {
    key: "closeModal",
    value: function closeModal() {
      $('#order .m-modal').hide();
    }
  }, {
    key: "changeSureBtnClass",
    value: function changeSureBtnClass() {
      if (this.eventEl.value - 0 === 0) {
        $('#sureBtn').attr({
          disabled: 'disabled'
        });
      } else {
        $('#sureBtn').removeAttr('disabled');
      }
    }
  }, {
    key: "renderOption",
    value: function renderOption(data, current) {
      return data.map(function (opt) {
        return "<option value=\"".concat(opt.pay_status, "\" ").concat(opt.pay_status === current ? 'selected' : '', " >").concat(opt.pay_status_name, "</option>");
      }).join('');
    }
  }, {
    key: "renderContent",
    value: function renderContent(data) {
      var m = this;
      this.toTgOrderData = {
        id: data.id,
        pay_status: data.pay_status,
        username: data.username,
        amount: data.amount,
        orderno: data.orderno
      };
      var html = "\n  <div class=\"m-modal-content detail\">\n  <!-- \u56FE\u7247\u8BE6\u60C5 image-text -->\n  <div class=\"m-row data-container\">\n  <div class=\"m-col-6 m-img-box\">\n    <img src=\"".concat(_utils.nullfy2str(data.img_src), "\" alt=\"\">\n  </div>\n  <div class=\"m-col-6 \">\n    <div class=\"m-debar\">\n      <!-- \u5217\u8868 -->\n      <!-- \u56FE\u7247\u6570\u636E -->\n      <div class=\"m-scroll\">\n        <table class=\"m-table \">\n          <thead>\n            <tr>\n              <th>ID</th>\n              <th>").concat(_utils.nullfy2str(data.id), "</th>\n            </tr>\n          </thead>\n          <tbody>\n          <tr><td>\u8BA2\u5355\u53F7</td><td>").concat(_utils.nullfy2str(data.orderno), "</td></tr>\n          <tr><td>\u63D0\u4EA4\u7C7B\u578B</td><td>").concat(_utils.nullfy2str(data.commit_type_name), "</td></tr>\n          <tr><td>\u5f69\u91d1\u7c7b\u578b</td><td>").concat(_utils.nullfy2str(data.commit_type_child_name), "</td></tr>\n          <tr><td>\u652F\u4ED8\u7C7B\u578B</td><td>").concat(_utils.nullfy2str(data.pay_type_name), "</td></tr>\n          <tr><td>\u4F1A\u5458\u8D26\u53F7</td><td>").concat(_utils.nullfy2str(data.username), "</td></tr>\n          <tr><td>\u5145\u503C\u91D1\u989D</td><td>").concat(_utils.nullfy2str(data.amount), "</td></tr>\n          <tr><td>\u8D60\u9001\u91D1\u989D</td><td>").concat(_utils.nullfy2str(data.giv_amount), "</td></tr>\n          <tr><td>\u72B6\u6001</td><td>").concat(_utils.nullfy2str(data.pay_status_name), "</td></tr>\n          <tr><td>\u6536\u6B3E\u4EBA</td><td>").concat(_utils.nullfy2str(data.collname), "</td></tr>\n          <tr><td>\u4ED8\u6B3E\u4EBA</td><td>").concat(_utils.nullfy2str(data.payname), "</td></tr>\n          <tr><td>\u5907\u6CE8</td><td>").concat(_utils.nullfy2str(data.desc), "</td></tr>\n          <tr><td>\u63D0\u4EA4\u7528\u6237</td><td>").concat(_utils.nullfy2str(data.commitname), "</td></tr>\n          <tr><td>\u7EC4\u522B</td><td>").concat(_utils.nullfy2str(data.groupname), "</td></tr>\n          <tr><td>\u63D0\u4EA4\u65F6\u95F4</td><td>").concat(_utils.nullfy2str(data.cmit_time), "</td></tr>\n          </tbody>\n        </table>\n      </div>\n\n    </div>\n\n  </div>\n  </div>\n  <section class=\"state\">\n\n  <div id=\"modalForm\" class=\"m-tool-bar clearfix\" method=\"post\">\n\n    <button id=\"sureBtn\" class=\"btn m-fr btn-primary\" onclick=\"m.on('submit')\">\u786E\u8BA4</button>\n    <button class=\"btn m-fr btn-info\" onclick=\"m.on('close')\">\u53D6\u6D88</button>\n    <p class=\"select-box m-fr\">\n    <b>\u8BF7\u9009\u62E9\u8BA2\u5355\u72B6\u6001</b>\n    <select name=\"pay_status\" id=\"pay_status\" onchange=\"m.on('pay-status-change',this)\" >\n      ").concat(this.renderOption(data.pays, data.pay_status - 0), "\n    </select>\n    </p>\n\n  </div>\n  </section>\n\n  <!-- \u64CD\u4F5C -->\n  <span class=\"m-close\" onclick=\"m.on('close')\">&times;</span>\n  </div>");
      $('#order .m-modal').html(html).show();
      setTimeout(function () {
        m.handleModalRendered();
        html = null;
        m = null;
      }, 500);
    } //定义钩子

  }, {
    key: "handleModalRendered",
    value: function handleModalRendered() {
      this.initStatusSelect();
    }
  }, {
    key: "handleClose",
    value: function handleClose() {
      this.closeModal();
    }
  }, {
    key: "handleOpen",
    value: function handleOpen() {
      var href = $(this.eventEl).attr('data-href');
      $.load('loading……');
      var m = this;
      this.api_getDetail(href).then(function (res) {
        $.loaded();
        m.renderContent(res.data);
        m.openModal();
        setTimeout(function name() {
          m.changeSureBtnClass();
        }, 500);
      }).catch(function (err) {
        $.loaded();
        $.alert(err.message);
      });
      href = null; //同步删
    }
  }, {
    key: "handleChange",
    value: function handleChange() {
      this.changeSureBtnClass();
    }
  }, {
    key: "handleSbumit",
    value: function handleSbumit() {
      var m = this;
      var state = $('#pay_status').val();

      if (!_utils.verifyNumber(state)) {
        alert('输入内容不合规范，请重新登录。请确保安全环境后再次执行！');

        _utils.logout();

        return;
      }

      this.toTgOrderData.pay_status = state;
      m.closeModal();
      $.load('提交中……');
      this.api_toTgOrder(this.toTgOrderData).then(function (res) {
        $.loaded();

        switch (res.code) {
          case 200:
            m.fn200(res);
            break;

          case 201:
            m.fn201(res);
            break;

          default:
            break;
        }

        m = state = null;
      }).catch(function (err) {
        $.alert(err.message);
      });
    } //执行器

    /**
     *
     * @param {string} name 事件名
     */

  }, {
    key: "on",
    value: function on(name, eventEl) {
      this.eventEl = eventEl;
      name += '';
      this.events[name].call(this);
    }
  }]);

  return Modal;
}(API);

var m = new Modal();