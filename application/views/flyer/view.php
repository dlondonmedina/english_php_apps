<div id="docx">
  <textarea></textarea>
</div> <br/>
<button type="button" name="button" class="btn btn-primary btn-lg pull-right"
    onclick='makeDocument("/templates/template.docx", <?php echo isset($content) ? $content : ''; ?>)'>
  Create!
</button>
