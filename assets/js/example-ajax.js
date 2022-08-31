$(document).ready(function(){
    ajaxExample.init();
});

ajaxExample = {
    init:function()
    {
        $("#btnLoadPost").click(this.loadPostAjax)
    },
    loadPostAjax:function()
    {
        //La accion que especificamos en el php "configuration-theme.php"
        let action = "ejemplo";
        //la url que se encuentra dentro del objeto que especificamos en el archivo del template
        let url = ejemplo.ajax_url;

        $.post(url, {action}, function(response)
        {
            if(response.status == "ok")
            {
                $("#listadoPost").html("");
                let posts = response.data;
                posts.forEach(it => {
                    $("#listadoPost").append("<div class='col'>" + it.post_title + "</div>");
                });
            }
            else
            {
                alert(response.error);
            }
        });
    }
};