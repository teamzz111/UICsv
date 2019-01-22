$(document).ready(function(){
    $('section.step2, section.step3').fadeOut(1);
    $("#fileToUpload").submit(function(e){
        $('section.step1').fadeOut(1000, function(){
            $('section.step2').fadeIn(1000, function(){
                
            })
        })
        e.preventDefault()
    })
    $("input:file").change(function (){
        var fileName = $(this).val()
        $('.step1 .container .der form i').removeClass()
        $('.step1 .container .der form i').addClass("fa")
        $('.step1 .container .der form i').addClass("fa-check")
        $('.step1 .container .der form span').text("Archivo cargado con éxito")
        $('.step1 .container .der form .label').css({border: "2px dashed lightgreen"})
        $('.step1 .container .der form .title').css("color", "black")
    });
})