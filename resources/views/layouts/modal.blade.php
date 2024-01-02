<div class="modal fade" id="notifications" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">通知</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul>
        
        </ul>
      </div>
    </div>
  </div>
</div>
<script>
  $('.read_notification').on('click',function(){
    let $this = $(this);
    $.ajax({
      method:'POST',
      url:'read-notification',
      data:{id:$this.data('id')}
    })
    .done((msg)=>{
      if(msg.result){
        $this.find('.read').text('(已讀)');
      }
    })
  })
</script>