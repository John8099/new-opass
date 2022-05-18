(function ($) {
  "use strict";

  $(function () {
    for (var nk = window.location, o = $(".nano-content li a").filter(function () {
      return this.href == nk;
    })
      .addClass("active")
      .parent()
      .addClass("active"); ;) {
      if (!o.is("li")) break;
      o = o.parent()
        .addClass("d-block")
        .parent()
        .addClass("active");
    }
  });


  /* 
  ------------------------------------------------
  Sidebar open close animated humberger icon
  ------------------------------------------------*/

  $(".hamburger").on('click', function () {
    $(this).toggleClass("is-active");
  });


  /* TO DO LIST 
  --------------------*/
  $(".tdl-new").on('keypress', function (e) {
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code == 13) {
      var v = $(this).val();
      var s = v.replace(/ +?/g, '');
      if (s == "") {
        return false;
      } else {
        $(".tdl-content ul").append("<li><label><input type='checkbox'><i></i><span>" + v + "</span><a href='#' class='ti-close'></a></label></li>");
        $.ajax({
          url: '../../backend/nodes.php?action=createTodo',
          data: {
            text: $(this).val(),
          },
          type: 'POST',
        });
        $(this).val("");
      }
    }
  });

  $(".tdl-content a").on("click", function () {
    var _li = $(this).parent().parent("li");
    _li.addClass("remove").stop().delay(100).slideUp("fast", function () {
      _li.remove();
    });
    return false;
  });

  // for dynamically created a tags
  $(".tdl-content").on('click', "a", function () {
    var _li = $(this).parent().parent("li");
    _li.addClass("remove").stop().delay(100).slideUp("fast", function () {
      _li.remove();
    });
    return false;
  });

})(jQuery);

// Notification

const duration = 3000; // every 3 seconds
$("#notificationBadge").hide();

let timeout = setInterval(function () {
  $.get("../../backend/nodes.php?action=getNotificationCount", function (data) {
    if (data > 0) {
      $("#notificationBadge").html(data);
      $("#notificationBadge").show();
    }
    else {
      $("#notificationBadge").html(data);
      $("#notificationBadge").hide();
    }
  }).fail(function () {
    clearTimeout(timeout);
  });
}, duration);


$("#notification").on("show.bs.dropdown", function (event) {
  clearTimeout(timeout);

  $.get("../../backend/nodes.php?action=getNotificationData", function (data) {
    $("#notificationData").html(data)
  });
});

$("#notification").on("hide.bs.dropdown", function (event) {

  $.get("../../backend/nodes.php?action=markAsSeen", function (data) {
    $("#notificationData").html(data)
  });

  timeout = setInterval(function () {
    $.get("../../backend/nodes.php?action=getNotificationCount", function (data) {
      if (data > 0) {
        $("#notificationBadge").html(data);
        $("#notificationBadge").show();
      }
      else {
        $("#notificationBadge").html(data);
        $("#notificationBadge").hide();
      }
    }).fail(function () {
      clearTimeout(timeout);
    });
  }, duration);
});
