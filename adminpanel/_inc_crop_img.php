<!-- Modal -->
<div class="modal fade" id="cropImg" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Crop Image</h4>
      </div>
      <div class="modal-body">
        <iframe id="imageToBeCorped" style="border:0px;width:570px;height:350px;"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" onclick="$('#imageToBeCorped').contents().find('form').submit();" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
<script>
function cropImg(mod,imgURL_orginal,imgURL_target,current_cords,target_size)
{
	$('#imageToBeCorped').attr('src', '_inc_crop_img_frame.php?d='+Math.random()+'&o='+imgURL_orginal+'&t='+imgURL_target+'&c='+current_cords+'&s='+target_size);
}


</script>
