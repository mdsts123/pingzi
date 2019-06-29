function preventDefaultEvents() {
  $('.label-success-outline').on('click', function(event) {
    event.preventDefault();
  });
}
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
    return new Promise((resolve, reject) => {
      $.ajax({
        type: 'GET',
        url,
        success(res) {
          res = JSON.parse(res);
          resolve(res.data);
        },
      });
    });
  }

  api_toTgOrder(data) {
    let url = '/admin.php?s=/Admin/TgOrder/toTgOrder';
    return new Promise((resolve, reject) => {
      $.ajax({
        type: 'post',
        url,
        data,
        success(res) {
          res = JSON.parse(res);
          if (res.code !== 200) {
            return reject(res);
          }
          resolve(res);
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
  u = new Utils();
  events = []; //时间列表
  //定义事件
  constructor() {
    super();
    preventDefaultEvents();
    this.initEvents();

  }
  //定义操作
  initEvents(){
    this.events['open'] = this.handleOpen;
    this.events['close'] = this.handleClose;
    this.events['submit'] = this.handleSbumit;
    this.events['change'] = this.handleChange;
  }
  openModal() {
    $('#order .m-modal').show();
  }
  closeModal() {
    $('#order .m-modal').hide();
  }
  changeSureBtnClass() {
    if (this.eventEl.value-0 === 0) {
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
    this.toTgOrderData = {
      id: data.id,
      pay_status: data.pay_status,
      username: data.username,
      amount: data.amount,
      orderno: data.amount,
    };
    let html = `
  <div class="m-modal-content detail">
  <!-- 图片详情 image-text -->
  <div class="m-row data-container">
  <div class="m-col-6 m-img-box">
    <img src="${this.u.nullfy2str(data.img_src)}" alt="">
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
              <th>${this.u.nullfy2str(data.id)}</th>
            </tr>
          </thead>
          <tbody>
          <tr><td>订单号</td><td>${this.u.nullfy2str(data.orderno)}</td></tr>
          <tr><td>提交类型</td><td>${this.u.nullfy2str(
            data.commit_type_name,
          )}</td></tr>
          <tr><td>支付类型</td><td>${this.u.nullfy2str(
            data.pay_type_name,
          )}</td></tr>
          <tr><td>会员账号</td><td>${this.u.nullfy2str(data.username)}</td></tr>
          <tr><td>充值金额</td><td>${this.u.nullfy2str(data.amount)}</td></tr>
          <tr><td>赠送金额</td><td>${this.u.nullfy2str(data.giv_amount)}</td></tr>
          <tr><td>状态</td><td>${this.u.nullfy2str(
            data.pay_status_name,
          )}</td></tr>
          <tr><td>收款人</td><td>${this.u.nullfy2str(data.collname)}</td></tr>
          <tr><td>付款人</td><td>${this.u.nullfy2str(data.payname)}</td></tr>
          <tr><td>备注</td><td>${this.u.nullfy2str(data.desc)}</td></tr>
          <tr><td>提交用户</td><td>${this.u.nullfy2str(data.commitname)}</td></tr>
          <tr><td>组别</td><td>${this.u.nullfy2str(data.groupname)}</td></tr>
          <tr><td>提交时间</td><td>${this.u.nullfy2str(data.cmit_time)}</td></tr>
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
    <select name="pay_status" id="pay_status" onchange="m.on('change',this)" >
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
      html=null;
  }

  //定义钩子
  handleClose() {
    this.closeModal();
  }
  handleOpen() {
    let m=this
    this.api_getDetail($(this.eventEl).attr('href')).then(function(data) {
      m.renderContent(data);
      m.openModal();
      m=null
    });

  }
  handleChange() {
    this.changeSureBtnClass();
  }
  handleSbumit() {
    let m = this;
    let state = $('#pay_status').val();
    if (!this.u.verifyNumber(state)) {
      alert('输入内容不合规范，请重新登录。请确保安全环境后再次执行！');
      this.u.logout();
      return;
    }

    this.toTgOrderData.pay_status = state;
    this.api_toTgOrder(this.toTgOrderData)
      .then(data => {
        m.u.refresh();
        m.closeModal();
        m=state = null;
      })
      .catch(res => {
        alert(res.message);
        m.closeModal();
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
let m = new Modal();
