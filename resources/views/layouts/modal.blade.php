<div class="modal fade" id="notifications" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">通知</h1>
        <!-- 以下button為關閉的按鈕 -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul>
            @foreach($notifications as $notification)
                <li class="read_notification" data-id="{{ $notification->id }}">{{ $notification->data['msg'] }}
                  <span class="read">
                    @if($notification->read_at)
                      (已讀)
                    @endif
                  </span>
                </li>
            @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>
<script> // 此處有個重點是.on裡面的function不可以使用箭頭函式
  $('.read_notification').on('click',function(){
    let $this = $(this);
    // 此處的$(this)，就是代表class=read_notification的整個元素，包含內部小元素
    $.ajax({
      method:'POST',
      url:'read-notification',
      data:{id:$this.data('id')} // 此處為使用jquery的方式取得let $this宣告的$this的id，也就是上面li中的data-id="{{ $notification->id }}"
    })
    .done((msg)=>{
      if(msg.result){
        $this.find('.read').text('(已讀)'); // 尋找li class="read_notification"中是否有子元素是class="read"
      }
    })
  })
</script>