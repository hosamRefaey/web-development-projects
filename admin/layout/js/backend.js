/*global document,$,confirm*/
$(document).ready(function (){
    'use strict';
 //Calls the selectBoxIt method 
  $("select").selectBoxIt({
      autoWidth: false

  });
    $('.laSelect').click(function(){
        $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);
        if($(this).hasClass('selected')){
 
            $(this).html('<i class="fas fa-plus"></i>');
        }
        else{
             $(this).html('<i class="fas fa-minus"></i>');
             
        }
    });
    
    //placeholder focus and blur
    $('[placeholder]').focus(function(){
       $(this).attr('data-text',$(this).attr('placeholder')) ;
        $(this).attr('placeholder','');
    }).blur(function(){
       $(this).attr('placeholder',$(this).attr('data-text')); 
    });
    


    $('input').each(function(){
        if($(this).attr('required')=='required'){
            $(this).after('<span class="asterisk">*</span>');
        }
    });

    $('.eye').hover(function(){
        $('.password').attr('type','text');
    }, function(){
        $('.password').attr('type','password');

    });

    $('.confirm').click(function(){
        return confirm('Are You Sure?');
    });

    $('.cat h3').click(function(){
        $(this).next('.cat_info').fadeToggle(100);
    });
    $('.categories .option').click(function(){
       $(this).addClass('active').siblings('span').removeClass('active');
        if($(this).data('view')==='full'){
            $('.cat .cat_info').fadeIn(100);
        }
        else{
            $('.cat .cat_info').fadeOut(100);
        }
    });

    $('.child-delete').hover(function(){
        $(this).find('.show-delete').fadeIn(100);
    },function(){
        $(this).find('.show-delete').fadeOut(100);
    }); 
    
    //image popup
    
    $(".clickToPop").click(function () {
        var pop = "."+$(this).data("popup");
        $(pop+" .inner img").attr("src",$(this).attr("src"));
        $(pop).fadeIn(300);
      //  console.log($(pop+" .inner img").attr("src"));
    });
    
    $(".inner button").click(function (e) {
        e.preventDefault();
        $(this).parentsUntil(".popup").parent().fadeOut(200);
    });
    
    $(".inner").click(function (e) {
        e.stopPropagation();
    });
    
    $(document).keydown(function (e) {
       if (e.keyCode==27) {
           $(".popup").fadeOut(300);
       } 
    });
    
    $(".popup").click(function (){
       $(this).fadeOut(300); 
    });
    $("input").on("keyup",function () {
        if ($(this).val()!="") {
       var fLetter = $(this).val().charCodeAt(0);
         if (fLetter<=200) {
             $(this).css("direction","ltr");
             $(this).siblings(".asterisk").css({
                    right:"10px"
                })
         }
            else{
                $(this).css("direction","rtl");
                $(this).siblings(".asterisk").css({
                    right:"18em"
                })
            }
        }
    });
    
    //button effect
    (function effect(){$(".boxEffect").hover(function () {
       $(".boxEffect .effect").animate({
           width:"100%"
       },300);
    
    },function () {
        $(".boxEffect .effect").animate({
           width:"0"
       },300);
    });
                      
                }());
    
    //fixed menu
    $(".fMenu i").click(function () {
        $(this).toggleClass("active");
        if ($(this).hasClass("active")) {
        $(".fMenu").animate({
           left:"0px" 
        },260);
       $("body").animate({
           paddingLeft:"200px"
       },300);
        }
    
                        
        else{
       $(".fMenu").animate({
           left:"-201px" 
        },300);
       $("body").animate({
           paddingLeft:"0px"
       },290); 
    }});
});