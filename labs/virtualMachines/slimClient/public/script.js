$(document).ready(function() {
  $("#peopleForm").submit(function(event) {
    var form = $(this);
    event.preventDefault();
    $.ajax({
      type: "POST",
      url: "http://localhost:8080/firstslim/people",
      data: form.serialize(), // serializes the form's elements.
      success: function(data) {
        window.location.replace("http://localhost:8080/slimClient");
      }
    });
  });
  $("#peopleEditForm").submit(function(event) {
    alert( "TODO: build submit handler.  See peopleForm submit handler for inspiration " );

  });
  $( ".deletebtn" ).click(function() {
  alert( "TODO: build delete handler with confirmation dialog See here for confirmation details:  https://developer.mozilla.org/en-US/docs/Web/API/Window/confirm" );
});
});
