
$(document).ready(function() {

    // $(".cd-popup-trigger").trigger('click');
    var loginoptions = {
            dataType:  'json',  
            beforeSubmit: function(data) { 
                
                var loading = "<img src='"+basedomain+"assets/images/loading.gif' width='40%'/>";
                 // loading += "<p>Please Wait ...</p>";
                // $('#imgupload').css('height','100%');
                $('.popuptext').html(loading);
                $(".cd-popup-trigger").trigger('click');
                
            },
            success : function(data) {

                if(data.status==true){
                    if (data.flag==true){
                        redirect(basedomain+"account/pelaporan");
                    }else{
                        redirect(basedomain+"account");
                    }
                              
                } else {
                    $('.popuptext').html("Username atau Password salah");
                    
                }
                         
            }
        };  

    // $("#loginForm").ajaxForm(loginoptions);

    var resetoptions = {
            dataType:  'json',  
            beforeSubmit: function(data) { 
                
                var loading = "<img src='"+basedomain+"assets/images/loading.gif' width='50%'/>";
                 loading += "<p>Please Wait ...</p>";
                // $('#imgupload').css('height','100%');
                $('.popuptext').html(loading);
                $(".cd-popup-trigger").trigger('click');
                
            },
            success : function(data) {

                if(data.status==true){
                    $('.popuptext').html("Silahkan verifikasi email anda untuk melanjutkan");            
                } else {
                    $('.popuptext').html("Email tidak terdaftar");
                    
                }
                         
            }
        };  

    // $("#resetakun").ajaxForm(resetoptions);

});

function submit_confirm(txt)
{
    var txt;
    if (txt) txt = txt;
    else txt = "Simpan data ?";
    var r = confirm(txt);
    if (r == true) {
        // do something
    } else {
        return false;
    }
}

function clog(data)
{
    console.log(data);
}

function redirect(data)
{
    window.location.href=data;
}

function readURLpose(input, target) {
    console.log(input);
    if (input.files && input.files[0]) {

        var reader = new FileReader();
        reader.onload = function (e) {
            $('#'+target).attr('src', e.target.result);
            $('#'+target).attr('width', '100px');
            // $('#'+target).attr('height', '200px');
        }
        reader.readAsDataURL(input.files[0]);
    }
}


$(document).on('change', '#suratPengantar', function(){
    readURLpose(this, "previewPengantar");
});

$(document).on('blur', '.email', function(){
    
    ValidateEmail(this)
    var email = $(this).val();
    $.post(basedomain+"home/ajax", {email:email}, function(data){

      if (data.status==1){
        alert('Email sudah terdaftar');
        
      }

    }, "JSON")
});


function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}    
