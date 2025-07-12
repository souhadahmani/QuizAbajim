/***update section status */
$(document).on("click",".updateQuizStatus",function(){
    var  status= $(this).children("i").attr("status");
    var quiz_id=$(this).attr("quiz_id");
 // alert(section_id);
    $.ajax({
      headers:{
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type:'post',
      url:'/update-quiz-status',
      data:{status:status,quiz_id:quiz_id},

      success:function(resp){
        location.reload();
        },error:function(){
           alert ('error');
       }
  })

});


// dynamique javascript form delete


$(document).on("click",".confirmDelete",function(){
    var module = $(this) .attr('module');
   var moduleid = $(this) .attr('moduleid');
 //  alert(module);
 //  return false;
    Swal.fire({

        title: 'هل أنت متأكد؟',
        text: "لن تتمكن من التراجع عن هذا!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم، احذفه!'
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire(
            'تم الحذف!',
            'لقد تم حذف ملفك.',
            'success'
          )
          window.location="/delete-"+module+"/"+moduleid;
        }
      })

    });

