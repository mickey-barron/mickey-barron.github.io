let currentTest = 0;

let reviews = ['So incredibly helpful - your insight and ability to problem solve is impeccable!', 'Thanks to you, we are able to provide effective and valuable care to our patients.', 'I am absolutely amazed with the quality of your work - you picked up on so many important things; thank you!'];
let reviewers = ['Roger S, Physician Executive at Massachussets General Hospital', 'Michelle W, Program Manager at Johns Hopkins', 'Kerri H, Director at Harvard Medical School Teaching Hospital'];

let reqs = ["name", "title", "email", "zip", "subject", "message", "agreement"];
let criteria = Array();
criteria["email"] = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

function enableSmoothScroll(){
    $('a[href*="#"]').not('[href="#"]').not('[href="#0"]').click(function(event) {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: (target.offset().top - 100)
            }, 1000, function() {
                var $target = $(target);
                $target.focus();
                if ($target.is(":focus")) {
                    return false;
                } else {   
                $target.focus(); 
                };
            });
            }
        }
    });
}

function genNavDots(){
    let navDotsHTML = "";
    reviews.forEach(function(){
        navDotsHTML += "<i class = 'material-icons navDot'>stop_circle</i>"; 
    });
    return navDotsHTML;
}

function toggleClass(target, cls1, cls2){
    if(cls2 == null){
        cls2 = cls1;
    }
    if ($(target).hasClass(cls1)){
        $(target).addClass(cls2);
        $(target).removeClass(cls1);

    } else{
        $(target).addClass(cls1);
        $(target).removeClass(cls2);
    }

}

function infoNotify(target, icon, message){
    $(target).children().children('p').html(message);
    $(target).children().children('#notificationIcon').html(icon);
    $(target).addClass('active');
    $(target).removeClass('inactive');
}

function updateTestimonial(current, move){
    let target = current + move;
    let indexes = reviews.length - 1
    if (target < 0){
        target = indexes;
    }else if(target > indexes){
        target = 0;
    }
    let review = reviews[target];
    let reviewer = reviewers[target];
    $('#testimonial').fadeOut(function(){
        $(this).text(review).fadeIn();
    });
    $('#customer').fadeOut(function(){
        $(this).text(reviewer).fadeIn();
    });
    $('#testNavDots').children().eq(current).fadeIn().removeClass('active');
    $('#testNavDots').children().eq(target).fadeIn().addClass('active');
    currentTest = target;
}

function advanceTest(){
    updateTestimonial(currentTest, 1)
}

function updateInvalidInput(reqId){
    $(reqId).parent().addClass("incomplete");
    $(reqId).siblings('aside').html("!");

}

function updateValidInput(reqId){
    $(reqId).parent().removeClass("incomplete");
    $(reqId).siblings('aside').html("*");
}

function validateInput(target){
    let reqId = "#" + target;
    let currentValue = $(reqId).val();
    if (criteria[target] != null){
        let criterium = criteria[target];
        if (criterium.test(String(currentValue).toLowerCase())){
            updateValidInput(reqId);
            return 0;
        } else{
            updateInvalidInput(reqId);
            return 1;
        }
    } else {
        if (currentValue == ""){
            updateInvalidInput(reqId);
            return 1;
        }else{
            updateValidInput(reqId);
            return 0;
        }
    }
}

function submitForm(package){
    let jsonPackage = JSON.stringify(package);
    return new Promise(function(resolve, reject){
        $.ajax({
            url: "assets/scripts/contact.php",
            type: 'POST',
            data: {data: jsonPackage},
            success: function(data){
                resolve(data);
            },
            error: function(error){
                reject(error);
            }
        });
    });
}

$(document).ready(function(){

    $("#agreement").change(function(){
        if(this.checked){
            $(this).val("I agree to the Terms & Conditions, Privacy Policy, and Disclaimer of Novel Healthcare Solutions, LLC.");
        } else {
            $(this).val(""); 
        }
    });

    $('#testNavDots').html(genNavDots());
    updateTestimonial(0,0);
    window.setInterval(advanceTest, 10000);


    $('#test_left').click(function(){
        updateTestimonial(currentTest, -1);
    });
    
    $('#test_right').click(function(){
        updateTestimonial(currentTest, 1);
    });
    
    $('#contactWidgetButton').click(function(){
        toggleClass($('#contactWidget'), 'active', 'inactive');
    });

    $('#infoCloseButton').click(function(){
        let parent = $(this).parent().parent();
        parent.addClass('inactive');
        parent.removeClass('active');
    });



    reqs.forEach(function(field){
        let reqId = "#" + field;
        $(reqId).focusout(function(){
           validateInput(field);
        });
    });

    $('#agreement').change(function(){
        validateInput('agreement');
    });

    $('#submit').click(function(){
        event.preventDefault();
        let dataPackage = Object();
        let targets = ["name", "company", "title", "position", "zip", "email", "phone", "subject", "agreement", "message"];
        targets.forEach(function(field){
            let fieldId = "#" + field;
            dataPackage[field] = $(fieldId).val();
        });
        
        let incompletes = 0;
        reqs.forEach(function(field){
            incompletes += validateInput(field);
        });

        if (incompletes > 0 ){
            infoNotify('#infoWidgetContainer', 'error_outline', "Please ensure you have completed all required fields prior to submitting the form. Incomplete fields have been denoted with a trailing '!'.");
        } else{
            $('#submit').html('<i class = "material-icons spinner">cached</i> Submitting...');
            submitForm(dataPackage).then(function(data){
                response = JSON.parse(data);
                if(response["result"] == "rejected"){
                    responseText = "<h4> Our server rejected your request. The errors are listed below: </h4> <ul>";
                    response['errors'].forEach(function(error){
                        responseText += "<li>" +error+"</li>";
                    });
                    responseText+="</ul>"
                    infoNotify('#infoWidgetContainer', 'error_outline', responseText);
                } else if(response["result"] == "accepted"){
                    console.log(response["response"]);
                }
                
            }).catch(function(error){
                console.log(error);
            });
        }
        
    });
    
    $('#mobileMenuIcon').click(function(){
        if ($('nav').hasClass('active')){
            $('nav').addClass('inactive');
            $('nav').removeClass('active');
        } else{
            $('nav').addClass('active');
            $('nav').removeClass('inactive');
        }
    });
    enableSmoothScroll();
});


