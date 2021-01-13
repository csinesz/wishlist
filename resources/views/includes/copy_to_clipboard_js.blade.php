copyLink = '';
function copyToClipboard() {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(copyLink).select();
    document.execCommand("copy");
    $temp.remove();
}
