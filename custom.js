$("input[type=radio]").change(function() {
    if(this.value == "option1")
    {
        $("#id1").css("display","block");
        $("#id2").css("display","none");
    }
    else
    {
        $("#id1").css("display","none");
        $("#id2").css("display","block");
    }
});


/*$("#main-form").submit(function(event){

});*/

$("div[name=new-category-form]").on('keypress',function(event)
{
    if(event.which == 13) {
        event.preventDefault();
        var cat = $(this).find("input[name=new-category]").val();

        $(this).find("input[name=new-category]").val("");

        var n1 = $(this).parent(".listing").find(".bigge").attr("name").substr(4);

        var n2 = 1;
        if($(this).parent(".listing").find("div[name=category-group]").length > 0) {

            n2 = $(this).parent(".listing").find("div[name=category-group]").length + 1;
        }

        var block;

        if($(this).parent(".listing").find("div[name=category-group]").length > 0)
        {
            block = $(this).parent(".listing").find("div[name=category-group]").last();
        }
        else
        {
            block = $(this).parent(".listing").find("div[name=email-loc]").last();
        }


        $("<div class=\"form-check form-check-inline\" name=\"category-group\"><input checked class=\"form-check-input\" type=\"checkbox\" name=\"category" + n1 + "-" + n2 + "\" value=\""+ cat + "\">\n" +
            "<label class=\"form-check-label\" for=\"inlineCheckbox1\">" + cat + "</label></div>").insertAfter(block);

    }
});


$(".img-thumbnail").click(function(event)
{
   if($(this).hasClass("selected"))
   {
       $(this).removeClass("selected");
   }
   else
   {
       $(this).addClass("selected");
   }

   var name = $(this).attr('name');
   var block = $(this).parent(".images").find("input[name=" + name + "]");

   if(block.is(':checked'))
   {
       block.attr("checked", false);
   }
   else
   {
       block.attr("checked", true);
   }


});

/*
setTimeout(function(){
$( document ).ready(function() {
    $("#main-form").submit();
});
},2000);*/
