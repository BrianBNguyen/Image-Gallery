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

// ask user to confirm deleting images
function confirm_delete(){
    var answer = confirm("are you sure you want to delete these images?");
    return answer;
}
