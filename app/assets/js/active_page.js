function set_active_page(page) {
    var formatted_page = "#" + page;
    if (formatted_page.indexOf("account") !== -1) {
        $("#account").trigger("click");
    }
    if (formatted_page.indexOf("admin") !== -1) {
        $("#admin").trigger("click");
    }
    $(formatted_page).addClass("active");
}

