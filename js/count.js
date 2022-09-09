var settings = {
    "url": "https://beishifengqiban.cf//api/stats",
    "method": "GET",
    "timeout": 0,
  };
  $.ajax(settings).done(function (response) {
    console.log(response.counter);
    document.getElementById("SiteCounter").innerHTML="现在已有"+homo(response.counter)+"="+response.counter+"个人访问了本站";
  });