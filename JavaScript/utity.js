// Loading function
function RenderElement(className, HTMLText_Url){
    $.get(HTMLText_Url, function (html_text) {
        $(className).html(html_text);
   });
}

function RenderElementAfter(className, HTMLText_Url, callback_function=null){
    $.get(HTMLText_Url, function (html_text) {
        $(className).after(html_text);
        if (callback_function)
            callback_function();
   });
}

function RenderElementInside(className, HTMLText_Url, callback_function=null){
    $.get(HTMLText_Url, function (html_text) {
        $(className).html(html_text);
        if (callback_function)
            callback_function();
   });
}

// Untity Function
function print(textToBePrinted){
    console.log(textToBePrinted);
};


function getid(event){
    return
};

// XXS 
function escapeQuotes(string_input){
    return string_input.replace(/"/g, '&quot;').replace(/'/g, '&quot;');
}

function string_sanitization(input){
    input = String(input);
    var sanitized_input = escapeQuotes(input.escapeHTML());
    return sanitized_input;
}

function int_sanitization(input){
    input = String(input);
    var sanitized_input = escapeQuotes(input.escapeHTML());
    return Number(sanitized_input);
}