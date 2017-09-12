/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function () {
    $('input[data-required]').blur(function () {
        if ($(this).val() == '') {
            $(this).css('border-color', '#ff6262');
        } else {
            $(this).css('border-color', '#3a4249');
        }
    });
});

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var value;
var validatation;
var user = /^[a-z\sA-Z]+$/;
//var email = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?![gmail]|[yahoo]|[live])(?:[a-z0-9-]*[a-z0-9])?\.)+((([a-z0-9.](?:[a-z0-9]*[a-z0-9])){1,4})?)/;
var email = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+((([a-z0-9.](?:[a-z0-9]*[a-z0-9])){1,4})?)/;
var phone = /^[0-9]{10,15}$/;
$(document).ready(function () {
    $('input[name="name"]').blur(function () {
        value = $(this).val();
        if (value == '') {
            $(this).siblings('span.error-keyup-3').remove();
            $(this).after('<span class="error error-keyup-3" style="color:red;">Required field cannot be left blank</span>');
            validatation = false;
        } else {
            $(this).siblings('span.error-keyup-3').remove();
            if (!user.test(value)) {
                $(this).after('<span class="error error-keyup-3 OErr" style="color:red;">Please Enter a valid name</span>');
                validatation = false;
            } else {
                $(this).siblings('span.error-keyup-3').remove();
                validatation = true;
            }

        }
    });
    $('input[name="email"]').blur(function () {
        value = $(this).val();
        var element = $(this);
        if (value == '') {
            $(this).siblings('span.error-keyup-3').remove();
            $(this).after('<span class="error error-keyup-3" style="color:red;">Required field cannot be left blank</span>');
            validatation = false;
        } else {
            $(this).siblings('span.error-keyup-3').remove();
            if (!email.test(value)) {
                $(this).after('<span class="error error-keyup-3 OErr" style="color:red;">Please Enter a valid email</span>');
                validatation = false;
            } else {
                $(this).siblings('span.error-keyup-3').remove();
                var rawFile = new XMLHttpRequest();
                rawFile.open("GET", "js/companyList.txt", true);
                rawFile.onreadystatechange = function ()
                {
                    if (rawFile.readyState === 4)
                    {
                        var allText = rawFile.responseText;
                        var n = allText.indexOf(value.split("@")[1]);
                        if (n == -1) {
                            validatation = true;
                        } else {
                            element.after('<span class="error error-keyup-3 OErr" style="color:red;">Please Enter a valid corporate email</span>');
                            validatation = false;
                        }
                    }
                }
                rawFile.send();
            }
        }
    });
    $('input[name="phone"]').blur(function () {
        value = $(this).val();
        if (value == '') {
            $(this).siblings('span.error-keyup-3').remove();
            $(this).after('<span class="error error-keyup-3" style="color:red;">Required field cannot be left blank</span>');
            validatation = false;
        } else {
            $(this).siblings('span.error-keyup-3').remove();
            if (!phone.test(value)) {
                $(this).after('<span class="error error-keyup-3 OErr" style="color:red;">Please Enter a valid phone number</span>');
                validatation = false;
            } else {
                $(this).siblings('span.error-keyup-3').remove();
                validatation = true;
            }
        }

    });
    $('input[name="company"]').blur(function () {
        value = $(this).val();
        if (value == '') {
            $(this).siblings('span.error-keyup-3').remove();
            $(this).after('<span class="error error-keyup-3" style="color:red;">Required field cannot be left blank</span>');
            validatation = false;
        } else {
            $(this).siblings('span.error-keyup-3').remove();
            validatation = true;
        }
    });
    $('form[data-validation="validateData"]').submit(function () {
        $('form[data-validation="validateData"]').find('input[type="text"],input[type="number"],input[type="password"],input[type="email"]').each(function () {
            if ($(this).val() == "") {
                validatation = false;
                $(this).siblings('span.error-keyup-3').remove();
                $(this).after('<span class="error error-keyup-3" style="color:red;">Required field cannot be left blank</span>');
            }
        });
        if (!validatation) {
            return false;
        }
    });
});

