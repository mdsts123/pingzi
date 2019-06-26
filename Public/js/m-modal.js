function preventDefaultEvents() {
  $('.label-success-outline').on('click', function(event) {
    event.preventDefault();
  });
}
/**
 * 分装订单类 处理订单交互
 */
class order {
  constructor() {}
  getDetail(url) {
    $.ajax({
      type: 'GET',
      url,
      success(data) {
        console.log(data);
      },
    });
  }
}

/**
 * 封装模态类
 */
class modal extends order {
  clickEl
  //定义事件
  constructor() {
    super()
    preventDefaultEvents();
    this.events = {
      close: this.handleClose,
      open: this.handleOpen,
    };
  }
  //定义操作
  openModal() {
    $('#order .m-modal').show();
  }
  closeModal() {
    $('#order .m-modal').hide();
  }
  //定义钩子
  handleClose() {
    this.closeModal();
  }
  handleOpen() {
    this.getDetail($(this.clickEl).attr('href'))
    // this.openModal();
  }
  //定义事件
  initEvents(obj) {
    this.events = obj;
  }

  //执行器
  /**
   *
   * @param {string} name 事件名
   */
  on(name,clickEl) {
    this.clickEl=clickEl
    name += '';
    if(name==='open')this.handleOpen()
    if(name==='close')this.handleClose()
    // this.events[name]();
  }
}
let m = new modal();
