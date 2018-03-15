$(document).ready(function(){

  $("#search").click(function(){

    var bname      = $("#bname").val();
    var author     = $("#author").val();
    var publisher  = $("#publisher").val();
    var rating     = $("#rating").val();

    var pricemin   = $("#price-min").val();
    var pricemax   = $("#price-max").val();
    var code       = $("#code").val();

    if(bname == "" && author == "" && publisher == "" && rating == "" && pricemin == "" && pricemax == "")
    {
      alert("Please select an option");
      return 0;      
    }

    $.ajax({
          type:"POST",
          url: "admin-ajax.php",
          data: { 'action': 'call_my_ajax_handler','bname':bname,'author':author,'publisher':publisher, 'rating':rating,'pricemin':pricemin,'pricemax':pricemax,'code':code },
          success: function(result){          
            if(result)
            {
              var JSONObject = JSON.parse(result);
              //console.log(JSONObject);
              //$("#results").html(JSONObject);
              var rows = "<tr><th>No</th><th>Book Name</th><th>Price</th><th>Auther</th><th>Publisher</th><th>Rating</th></tr>";
              var j =1;
              var result = JSONObject;
              if(!result.length)
              {
                rows = rows + "<tr><td colspan='6'>No Record Found.</td></tr>";
              }

              for (var i = result.length - 1; i >= 0; i--) {               
                rows = rows + "<tr><td>"+j+"</td>" + "<td>"+result[i]["book_title"]+"</td>" + "<td>"+result[i]["price"]+"</td>" + "<td>"+result[i]["author"]+"</td>" + "<td>"+result[i]["publisher"]+"</td>" + "<td>"+result[i]["rating"]+"</td></tr>";
                j++;              
              };
            }
            
            $("#results").html(rows);
          }
    });

  });

});