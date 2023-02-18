// Loading function
function RenderElement(className, HTMLText_Url){
    $.get(HTMLText_Url, function (html_text) {
        $(className).html(html_text);
   });
}

function RenderElementAfter(className, HTMLText_Url){
    $.get(HTMLText_Url, function (html_text) {
        $(className).after(html_text);
   });
}

// Untity Function
function print(textToBePrinted){
    console.log(textToBePrinted);
};