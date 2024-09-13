$(document).ready(function(){

  var url=window.location.origin+'/home_diary';

  // function updateTime() {
  //     const now = new Date();
  //     let hours = now.getHours();
  //     const amPm = hours >= 12 ? 'PM' : 'AM';
  //     hours = hours % 12 || 12;
  //     const minutes = now.getMinutes().toString().padStart(2, '0');
  //     const seconds = now.getSeconds().toString().padStart(2, '0');
  //     document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds} ${amPm}`;
  // }
  // updateTime();
  // setInterval(updateTime, 1000); 
	
$('.submitForm').on('submit', function(e) {
  e.preventDefault();
  var form = $(this);
  var formData = form.serialize();
  submitForm(formData, form);
});

$('.submitUpdateForm').on('submit', function(e) {
  e.preventDefault();
  var form = $(this);
  var formData = form.serialize();
  
  submitForm(formData, form);
});

$('.viewImage').click(function(e) {
  e.preventDefault();
  var image="<center><img class='img-fluid img-thumbnail' src='"+$(this).attr('src')+"'></center>";
  $('#addImage').html(image);
  $('#bigImage').modal('show');
});


$('a[href="#logoutID"]').click(function(e) {
  e.preventDefault();
  $('#logoutID').modal('show');
});

$(".searchEmployee").on("keyup", function() {
  var value = $(this).val().toLowerCase();
  $(".allEmployee tr").filter(function() {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  });
});

//Employee Delete
var employeeID;
$('.deleteBtnEmployee').click(function(e){
  e.preventDefault();
  employeeID=$(this).attr('employeeID');
});

$('.yesDeleteEmployee').click(function(){
  $.ajax({
    method:'POST',
    url:url+'/files/employee.php',
    data:{'employeeDelete':'employeeDelete','employeeID':employeeID},
    success: function(response) {
      // show_message(response);
      $('#employeeDelete').modal('hide');
      Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Employee has been Deleted",
        showConfirmButton: false,
        timer: 1500
      });

      setTimeout(() => {
        window.location.reload();
      }, 2000);
    }
  });
});

//Employee Delete
var dutysheetID;
$('.deleteBtnDutysheet').click(function(e){
  e.preventDefault();
  dutysheetID=$(this).attr('dutysheetID');
});
$('.yesDeleteDutysheet').click(function(){
  $.ajax({
    method:'POST',
    url:url+'/files/dutysheet.php',
    data:{'dutysheetDelete':'dutysheetDelete','dutysheetID':dutysheetID},
    success: function(response) {
      show_message(response);
    }
  });
});



$('.employeeUpdateBtn').click(function(){
  var employeeId=$(this).attr('employeeID');
  $.ajax({
    method:'POST',
    url: url+'/files/employee.php',
    data:{'employeeData':'employeeData','employeeId':employeeId},
    success: function(response) 
    {
      var response=$.parseJSON(response);
      if (response.success) 
      {
        $('#employeeUpdateForm').html(response.message);
        $('#updateEmployeeModal').modal('show');
      }
    }
  });
});

$('.idLogOut').click(function(){
  window.location.href = url+"/files/logout.php";
});




function submitForm(formData, form) 
{
  
    $.ajax({
      url: form.attr('action'),
      method: form.attr('method'),
      data: formData,
      success: function(response) {
        console.log('response' , response);
        
        // show_message(response);
        form.closest('.modal').modal('hide');
        // $('#updateEmployeeModal').modal('hide');
        Swal.fire({
          position: "center",
          icon: "success",
          title: "Record has been saved",
          showConfirmButton: false,
          timer: 1500
        });

        setTimeout(() => {
          window.location.reload();
        }, 2000);
      },
      error: function(err){
        console.log(err);
        
      }
    });
  
    // var formData = new FormData(form[0]);
    // $.ajax({
    //   method: form.attr('method'),
    //   url: form.attr('action'),
    //   data: formData,
    //   success: function(response) {
    //     show_message(response);
    //   },
    //   cache: false,
    //   contentType: false,
    //   processData: false,
    // });
    
}

function show_message(response) {
  if (typeof response === 'string' && response.trim().startsWith('<')) {
    // Handle non-JSON response (likely HTML or error page)
    $('.alertError').html('<br><center><div class="custom-alert alert alert-danger">Unexpected error occurred.</div></center>');
    return;
  }

  try {
    var parsedResponse = $.parseJSON(response);
    
    if (parsedResponse.success) {
      if (parsedResponse.signout) {
        setTimeout(function() {
          window.location.reload();
        }, 2000);
      } else if (parsedResponse.url) {
        setTimeout(function() {
          window.location = parsedResponse.url;
        }, 2000);
      }
      $('.submitForm')[0].reset();
      $('.alertError').html('<br><center><div class="custom-alert alert alert-success" style="background-color:#d4edda;border-color:#c3e6cb;color:#155724;"><a href="#" class="close" data-dismiss="alert">&times;</a>' + parsedResponse.message + '</div><center>');
    } else if (parsedResponse.error) {
      $('.alertError').html('<br><center><div class="custom-alert alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times; </a> ' + parsedResponse.message + '</div></center>');
    }
  } catch (e) {
    console.error('Error parsing JSON:', e);
    $('.alertError').html('<br><center><div class="custom-alert alert alert-danger">Unexpected response format.</div></center>');
  }
}

});



