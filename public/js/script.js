/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).on('click', '.shrink .menu li', function () {
    $('.shrink .menu li ul').stop().slideUp(800).not($(this).find('ul').stop().slideToggle(800));
});

$(document).ready(function () {
    $(this).scrollTop(0);
    $(document).on('click', '.menuBar', function () {
        if ($(this).hasClass('shrinkIt')) {
            $('.leftMenu').removeClass('shrink');
            $('.leftMenu').addClass('dashboard-off-canvas-menu-left');
            $('.contentContainer').addClass('containerShrink');
            $(this).removeClass('shrinkIt');
        } else {
            $('.leftMenu').addClass('shrink');
            $('.leftMenu').removeClass('dashboard-off-canvas-menu-left');
            $('.contentContainer').removeClass('containerShrink');
            $(this).addClass('shrinkIt');
        }

    });
    //Minimise

    $(document).on('click', '.minimise', function () {
        $(this).parents('.panel_header').next().slideToggle();
        if ($(this).parents('.panels').hasClass('collapsible')) {
            $(this).parents('.panels').addClass('show');
            $(this).parents('.panels').removeClass('collapsible');
            $(this).children().removeClass('fa-minus');
            $(this).children().addClass('fa-plus');
        } else if ($(this).parents('.panels').hasClass('show')) {
            $(this).parents('.panels').removeClass('show');
            $(this).parents('.panels').addClass('collapsible');
            $(this).children().addClass('fa-minus');
            $(this).children().removeClass('fa-plus');
        }
    });

    $(document).on('click', '.setting', function (e) {
        e.stopPropagation();
        if ($(this).hasClass('show')) {
            $(this).find('.settingPanels').css('display', 'none');
            $(this).removeClass('show');
        } else {
            $(this).find('.settingPanels').css('display', 'block');
            $(this).addClass('show');
        }
    });

    $(document).on('click', '.settingPanels li', function (e) {
        e.stopPropagation();
        var id = $(this).find('.rowShort').attr('id');
        if ($(this).find('.rowShort').is(':checked')) {
            $(this).find('.rowShort').prop('checked', false);
            $('.'+id).hide();
        }
        else{
            $(this).find('.rowShort').prop('checked', true);
            $('.'+id).show();
        }
    });
    $(document).on('change', '.switch input[type="checkbox"]', function () {
        if ($(this).is(":checked")) {
            $(this).closest('.formInputContainer').find('.error').text("Checked");
        }
        else{
            $(this).closest('.formInputContainer').find('.error').text("");
        }
    });
    /* For Target Page Events*/
     $('#add_user').on('submit',function(e){
        // alert("ggg");
            var name                        = $('#name').val();
            var country                      = $('#country').val();
            var buis_name                   = $('#buis_name').val();
            var street_addrress                   = $('#street_addr').val();
             var city                         = $('#city').val();
             var state                         = $('#state').val();
             var postalcode                         = $('#postalcode').val();

            $.ajax({

                type:"POST",
                url:'/user-insert',
                data: {
                    'name': name,
                    'country': country,
                    'buis_name': buis_name,
                    'street_addrress': street_addrress,
                    'state': state,
                    'postalcode': postalcode,
                    'city': city,
                },
                async: false,
                success: function(data){
                    console.log(data);
                },
                error: function(data){

                }
            })
        });
});