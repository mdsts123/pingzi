/**
 *工具类
 */
class Utils {
  constructor() {}
  /**
   *只要纯数字字符
   * @param {str} string
   */
  verifyNumber(str) {
    return str - 0 + '' === str + '';
  }
  logout() {
    location.href = '/admin.php?s=/Admin/Public/logout.html';
  }
  refresh() {
    location.href = location.href;
  }
  nullfy2str(data) {
    return data ? data : '';
  }
}

/**
 * API类 后端交互
 */
class API {
  constructor() {}
  api_getDetail(url) {
    let isRes = false;
    return new Promise((resolve, reject) => {
      $.ajax({
        type: 'GET',
        url,
        timeout: 5000,
        success(res) {
          res = JSON.parse(res);
          if (res.code) {
            resolve(res);
          } else {
            $.alert('数据错误，请联系技术人员');
          }
        },
        error(err) {
          reject({ message: '连接错误！' });
        },
        complete() {
          isRes = true;
        },
      });
    });
  }

  api_toTgOrder(data) {
    let isRes = false;
    return new Promise((resolve, reject) => {
      $.ajax({
        type: 'post',
        url: '/admin.php?s=/Admin/TgOrder/toTgOrder',
        data,
        timeout: 5000,
        success(res) {
          isRes = true;
          res = JSON.parse(res);
          if (res.code) {
            resolve(res);
          } else {
            $.alert('数据错误，请联系技术人员');
          }
        },
        error(err) {
          reject({ message: '连接错误！' });
        },
        complete() {
          isRes = true;
        },
      });
    });
  }
}

/**
 * 封装模态类
 */
class Modal extends API {
  eventEl;
  toTgOrderData = {};
  status = 0; //默认未处理
  events = []; //时间列表
  apiToTgOrder = '';
  //定义事件
  constructor() {
    super();
    this.windowDeployUtils();
    this.compatiblePrompt();
    this.preventOperationEvents();
    this.initEvents();
  }
  //定义操作

  fn200(res) {
    $.pop(res.message, 1500);
  }
  fn201(res) {
    $.alert(res.message);
  }
  windowDeployUtils() {
    if (window) {
      window['_utils'] = new Utils();
    }
  }
  /**
   * 兼容提示
   * ie全不支持Promise
   */
  compatiblePrompt() {
    if (!window['Promise']) {
      $('.m-accidentalTip').show();
      $.alert('请使用急速模式或谷歌浏览器');
    } else {
      $('.m-accidentalTip').hide();
    }
  }
  //阻止操作按钮默认事件
  preventOperationEvents() {
    $('.label-pill').each(function(index, el) {
      if (el.text === '操作') {
        $(el).attr('data-href', el.href);
        $(el).removeAttr('href');
      }
    });
  }
  /**
   * 初始 状态选项
   */
  initStatusSelect(data) {
    data = data || $('#pay_status [selected]').val();
    this.eventEl = $('#pay_status')[0];
    this.eventEl.value = data;
    this.events['pay-status-change'].call(this);
  }
  initEvents() {
    this.events['open'] = this.handleOpen;
    this.events['close'] = this.handleClose;
    this.events['submit'] = this.handleSbumit;
    this.events['pay-status-change'] = this.handleChange;
  }
  openModal() {
    $('#order .m-modal').show();
  }
  closeModal() {
    $('#order .m-modal').hide();
  }
  changeSureBtnClass() {
    if (this.eventEl.value - 0 === 0) {
      $('#sureBtn').attr({ disabled: 'disabled' });
    } else {
      $('#sureBtn').removeAttr('disabled');
    }
  }
  renderOption(data, current) {
    return data
      .map(
        opt =>
          `<option value="${opt.pay_status}" ${
            opt.pay_status === current ? 'selected' : ''
          } >${opt.pay_status_name}</option>`,
      )
      .join('');
  }
  renderContent(data) {
    let m = this;
    this.toTgOrderData = {
      id: data.id,
      pay_status: data.pay_status,
      username: data.username,
      amount: data.amount,
      orderno: data.orderno,
    };
    let html = `
  <div class="m-modal-content detail">
  <!-- 图片详情 image-text -->
  <div class="m-row data-container">
  <div class="m-col-6 m-img-box">
    <img src="${_utils.nullfy2str(data.img_src)}" alt="">
  </div>
  <div class="m-col-6 ">
    <div class="m-debar">
      <!-- 列表 -->
      <!-- 图片数据 -->
      <div class="m-scroll">
        <table class="m-table ">
          <thead>
            <tr>
              <th>ID</th>
              <th>${_utils.nullfy2str(data.id)}</th>
            </tr>
          </thead>
          <tbody>
          <tr><td>订单号</td><td>${_utils.nullfy2str(data.orderno)}</td></tr>
          <tr><td>提交类型</td><td>${_utils.nullfy2str(
            data.commit_type_name,
          )}</td></tr>
          <tr><td>支付类型</td><td>${_utils.nullfy2str(
            data.pay_type_name,
          )}</td></tr>
          <tr><td>会员账号</td><td>${_utils.nullfy2str(data.username)}</td></tr>
          <tr><td>充值金额</td><td>${_utils.nullfy2str(data.amount)}</td></tr>
          <tr><td>赠送金额</td><td>${_utils.nullfy2str(
            data.giv_amount,
          )}</td></tr>
          <tr><td>状态</td><td>${_utils.nullfy2str(
            data.pay_status_name,
          )}</td></tr>
          <tr><td>收款人</td><td>${_utils.nullfy2str(data.collname)}</td></tr>
          <tr><td>付款人</td><td>${_utils.nullfy2str(data.payname)}</td></tr>
          <tr><td>备注</td><td>${_utils.nullfy2str(data.desc)}</td></tr>
          <tr><td>提交用户</td><td>${_utils.nullfy2str(
            data.commitname,
          )}</td></tr>
          <tr><td>组别</td><td>${_utils.nullfy2str(data.groupname)}</td></tr>
          <tr><td>提交时间</td><td>${_utils.nullfy2str(
            data.cmit_time,
          )}</td></tr>
          </tbody>
        </table>
      </div>

    </div>

  </div>
  </div>
  <section class="state">

  <div id="modalForm" class="m-tool-bar clearfix" method="post">

    <button id="sureBtn" class="btn m-fr btn-primary" onclick="m.on('submit')">确认</button>
    <button class="btn m-fr btn-info" onclick="m.on('close')">取消</button>
    <p class="select-box m-fr">
    <b>请选择订单状态</b>
    <select name="pay_status" id="pay_status" onchange="m.on('pay-status-change',this)" >
      ${this.renderOption(data.pays, data.pay_status - 0)}
    </select>
    </p>

  </div>
  </section>

  <!-- 操作 -->
  <span class="m-close" onclick="m.on('close')">&times;</span>
  </div>`;
    $('#order .m-modal')
      .html(html)
      .show();
    setTimeout(() => {
      m.handleModalRendered();
      html = null;
      m = null;
    }, 500);
  }

  //定义钩子
  handleModalRendered() {
    this.initStatusSelect();
  }
  handleClose() {
    this.closeModal();
  }
  handleOpen() {
    let href = $(this.eventEl).attr('data-href');
    $.load('loading……');
    let m = this;
    this.api_getDetail(href)
      .then(function(res) {
        $.loaded();
        m.renderContent(res.data);
        m.openModal();
        setTimeout(function name() {
          m.changeSureBtnClass();
        }, 500);
      })
      .catch(err => {
        $.loaded();
        $.alert(err.message);
      });
    href = null; //同步删
  }
  handleChange() {
    this.changeSureBtnClass();
  }
  handleSbumit() {
    let m = this;
    let state = $('#pay_status').val();
    if (!_utils.verifyNumber(state)) {
      alert('输入内容不合规范，请重新登录。请确保安全环境后再次执行！');
      _utils.logout();
      return;
    }
    this.toTgOrderData.pay_status = state;
    m.closeModal();
    $.load('提交中……');
    this.api_toTgOrder(this.toTgOrderData)
      .then(res => {
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
      })
      .catch(err => {
        $.alert(err.message);
      });
  }

  //执行器
  /**
   *
   * @param {string} name 事件名
   */
  on(name, eventEl) {
    this.eventEl = eventEl;
    name += '';
    this.events[name].call(this);
  }
}

//订单 新增 彩金 事件
function handleOrderControlLabelEvents() {
  $("input[name='commit_type']").on('change', function(e) {
    if (this.value === '1') {
      $('.item_commit_type_child').show();
    } else {
      $('.item_commit_type_child').hide();
    }
  });
}

//暴露模态实列
let m = new Modal();

handleOrderControlLabelEvents();
