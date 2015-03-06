function startUpload(){
      document.getElementById('f1_upload_process').style.visibility = 'visible';
      document.getElementById('f1_upload_form').style.visibility = 'hidden';
      return true;
}



function stopUpload(success, img_src){
      var result = '';
      if (success == 1){
        result = '<span class="msg">Успех!</span><br/><br/>';
		include_image(img_src);
      }
      else {
         result = '<span class="emsg">К сожалению, возникла ошибка с загрузкой<\/span><br/><br/>';
      }
      document.getElementById('f1_upload_process').style.visibility = 'hidden';
      document.getElementById('f1_upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn" value="Upload" /><\/label>';
      document.getElementById('f1_upload_form').style.visibility = 'visible';      
      return true;   
}