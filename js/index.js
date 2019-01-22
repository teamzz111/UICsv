$(document).ready(function () {
    $('section.step2, section.step3').fadeOut(1);
    $("#fileToUpload").submit(function (e) {
        $('section.step1').fadeOut(1000, function () {
            $('section.step2').fadeIn(1500, function () {

                var fd = new FormData(this);
                fd.append('file', $('#file')[0].files[0]);

                $.ajax({
                    method: "POST",
                    url: "uploader.php",
                    data: fd,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        var successMessage = JSON.parse(data);
                        $('section.step2').fadeOut(1000, function(){
                            $('section.step3').fadeIn(500, function(){
                                if(successMessage.status == 1)
                                {
                                    $('section.step3 .container .der .message').css({background: "#00ff00", textAlign: "center"})
                                } 
                                else
                                {
                                    $('section.step3 .container .der .message').css({background: "#ff0000", textAlign: "center", textTransform: "none"})
                                }
                                $('section.step3 .container .der .message').text(successMessage.message)

                            })
                        })
                    },
                    error: function (xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            })
        })
        e.preventDefault()
    })



    $("input:file").change(function () {
        var fileName = $(this).val()
        $('.step1 .container .der form i').removeClass()
        $('.step1 .container .der form i').addClass("fa")
        $('.step1 .container .der form i').addClass("fa-check")
        $('.step1 .container .der form span').text("Archivo cargado con éxito")
        $('.step1 .container .der form .label').css({
            border: "2px dashed lightgreen"
        })
        $('.step1 .container .der form .title').css("color", "black")
    });
})