
var myHeaders = new Headers();
myHeaders.append("Content-Type", "application/json");
myHeaders.append("Authorization", "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiIsImlhdCI6MTY0Mzc4MzY3OH0.eyJzZXJ2aWNlQ29uc3VtZXJJZCI6ODY1LCJ0eXBlSWQiOjQsImNsaWVudElkIjo3ODI4fQ.4p9jd87kFbVBRUlW3Kiu_gePSnltHgag7QuGy6fgAI8");
myHeaders.append("Accept-Encoding", "gzip, deflate");
var raw = JSON.stringify({
  "Field": {
    "excluded": [
      "longDescription"
    ]
  }
});
var requestOptions = {
  method: "POST",
  headers: myHeaders,
  body: raw,
  redirect: "follow"
};
fetch("https://api.whise.eu/v1/estates/list", requestOptions)
.then(response => response.json())
.then(data_from_fetched => {
console.log(data_from_fetched.estates[0]);


jQuery(document).ready(function($){
 
  //console.log(test)
for( i =0; i < data_from_fetched.estates.length; ++i ){
  $.ajax({
    url: "admin-ajax.php",
    data: {
      "action": "my_action",
        'name': data_from_fetched.estates[i]['name'],
        'title' : data_from_fetched.estates[i]['name'],
        'content' : data_from_fetched.estates[i]['name'],
        'estateid' : data_from_fetched.estates[i]['id'],
        'price' : data_from_fetched.estates[i]['price'],
        'address' : data_from_fetched.estates[i]['address'],
        'number' : data_from_fetched.estates[i]['number'],
        'box' : data_from_fetched.estates[i]['box'],
        'zip' : data_from_fetched.estates[i]['zip'],
        'city' : data_from_fetched.estates[i]['city'],
        // 'estateid' : data_from_fetched.estates[i]['courntry'],
        'createDateTime' : data_from_fetched.estates[i]['createDateTime'],
        'area' : data_from_fetched.estates[i]['area'],
        // 'estateid' : data_from_fetched.estates[i]['status'],
     
      
    },
    success: function(data){
      console.log("Happy")
    }
  });
}
jQuery.post(ajaxurl, data, function(response) {
			
		});
});

})
