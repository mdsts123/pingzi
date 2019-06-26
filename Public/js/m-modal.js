function preventDefaultEvents() {
    $('.label-success-outline').on('click',function (event){
        event.preventDefault();
    })
}
let m = (function() {
    preventDefaultEvents();
    //定义操作
    function openModal() {
        $('#order .m-modal').show();
    }
    function closeModal() {
        $('#order .m-modal').hide();
    }
    //定义钩子
    function handleClose(){
        closeModal()
    }
    function handleOpen(){
        openModal();
    }
    //定义事件
    let events={
        close:handleClose,
        open:handleOpen
    }
    //执行器
    /**
     *
     * @param {string} name 事件名
     */
    function on(name){
        name+='';
        events[name]();
    }
    return {
        openModal,
        closeModal,
        on
    };
})();
