'use strict';
$(document).ready(function() {
  $("#register2").click(registerShow);
  $("#register").click(register);
  $("#login2").click(loginShow);
  $("#login").click(login);
  //$("#loginForm").show();
});

var registerShow = function() {
  //$("#loginForm").hide();
  //$("#registerForm").show();
  $('.flip-container').toggleClass('hover');
},

loginShow = function() {
  //$("#loginForm").show();
  //$("#registerForm").hide();
  $('.flip-container').toggleClass('hover');
},

register = function() {
  $.ajax({
    type: 'post',
    url: 'services/register.php',
    data: $("#registerForm").serialize(),
    success: function(data){
      console.debug('chiamato');
      alert(data);
    },
    dataType: 'json'
  });
},

login = function() {
  $.ajax({
    type: 'post',
    url: 'login.php',
    data: $("#loginForm").serialize(),
    success: function(data){
      if (data.login === true) {
        $('.flip-container').toggleClass('login');
        setTimeout(function(){document.location.href = data.url;}, 500);
      } else {
        $('#message').html(data.msg).show();
      }
    },
    dataType: 'json'
  });  
};