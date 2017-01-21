$(function(){
  var jfk = $(".bubble"); 
  $("#email").focus(function(){
    jfk.addClass("active");
  }).blur(function(){
    jfk.removeClass("active");
  });
});

$(function(){
  var jfk = $(".passbubble"); 
  $("#password").focus(function(){
    jfk.addClass("active");
  }).blur(function(){
    jfk.removeClass("active");
  });
});



