
    //页面上点击此属性，将当前页的列表数据全部选中
    $("#checkAll").click(function(){
    //判断当前点击的复选框处于什么状态$(this).is(":checked") 返回的是布尔类型
    if($(this).is(":checked")){
        $("input[name='ids[]']").prop("checked",true);
    }else{
        $("input[name='ids[]']").prop("checked",false);
        }
    });