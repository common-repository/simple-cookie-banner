//All the cookie setting stuff
function SetCookie(cookieName, cookieValue, nDays) {
    "use strict";
    var today = new Date();
    var expire = new Date();
    if (nDays == null || nDays == 0) 
        nDays = 1;
    expire.setTime(today.getTime() + 3600000 * 24 * nDays);
    document.cookie = cookieName + "=" + escape(cookieValue) + ";expires=" +
            expire.toGMTString() + "; path=/";
}
function ReadCookie(cookieName) {
    "use strict";
    var theCookie = " " + document.cookie;
    var ind = theCookie.indexOf(" " + cookieName + "=");
    if (ind == -1) 
        ind = theCookie.indexOf(";" + cookieName + "=");
    if (ind == -1 || cookieName == "") 
        return "";
    var ind1 = theCookie.indexOf(";", ind + 1);
    if (ind1 == -1) 
        ind1 = theCookie.length;
    return unescape(theCookie.substring(ind + cookieName.length + 2, ind1));
}
function DeleteCookie(cookieName) {
    "use strict";
    var today = new Date();
    var expire = new Date() - 30;
    expire.setTime(today.getTime() - 3600000 * 24 * 90);
    document.cookie = cookieName + "=" + escape(cookieValue) + ";expires=" +
            expire.toGMTString();
}
function OptOutAnalytics() {
    SetCookie("sgCookies", true, 30);
    SetCookie("no-ana", true, 30);
    location.reload();
}
function AcceptCookies() {
    SetCookie("sgCookies", true, 30);
    jQuery("#cookie-bar").hide();
}
function RenderBanner(options)
{
    var cookie_bar = document.createElement("div");
    cookie_bar.id = "cookie-bar";   
    cookie_bar.style.backgroundColor = options.banner_bg;
    cookie_bar.style.color = options.banner_text_color;

    var contentcookie = document.createElement("div")
    contentcookie.id = "contentcookie";

    var contentcookie_p = document.createElement("p");

    var button = document.createElement("button");
    button.style.backgroundColor = options.button_bg;
    button.style.color = options.button_color;
    button.onclick = function(){
        AcceptCookies();
    }
    button.innerText = options.btn_text;

    var clearfix = document.createElement("div");
    clearfix.className = "clear";

    contentcookie_p.innerHTML = options.msg;
    contentcookie.appendChild(contentcookie_p);
    contentcookie.appendChild(button);
    contentcookie.appendChild(clearfix);

    cookie_bar.appendChild(contentcookie);

    return cookie_bar;
}
/* Inject banner */
jQuery(document).ready(function (e) {
    /* Inject Styling */    
    var styleSheet = document.createElement("link");
    styleSheet.rel = "stylesheet";
    styleSheet.href = php_var.style_url;
    
    document.head.appendChild(styleSheet);
    /* Generate banner */
    var banner = RenderBanner({
        msg:php_var.msg, 
        btn_text:php_var.btn, 
        banner_bg:php_var.bck,
        banner_text_color:php_var.font,
        button_bg: php_var.btn_bck,
        button_color: php_var.btn_font
    });
    var toTop = jQuery("body").append(banner);
    if (!ReadCookie("no-ana")) {
        try {
            window['ga-disable-' + Object.keys(window.gaData)[0]] = true;
        } catch (e) {}
    }
    if (!ReadCookie("sgCookies")) {
        //If the cookie has not been set
        jQuery("#cookie-bar").show();
        setTimeout(function () {
            AcceptCookies()
        }, 100000);
    } else {
        jQuery("#cookie-bar").hide();
    }
});