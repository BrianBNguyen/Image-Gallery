function checkAll(controller, selection){
    // grab all of the checkbox images
    var check = document.getElementsByName(selection);
    // find how many images user trying to delete
    var length = check.length;

    // select or unselect all boxes
    for(var i = 0; i< length; i ++){
        check[i].checked = controller.checked;
    }
}
