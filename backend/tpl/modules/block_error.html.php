
<?php if( isset($_HTML['info_str']) ): ?>
    <div class="bs-callout bs-callout-info">
      <?=$_HTML['info_str']?>  
    </div>
<?php elseif( isset($_HTML['error_str']) ): ?>
    <div class="bs-callout bs-callout-info">
      <?=$_HTML['error_str']?>  
    </div>
<?php endif; ?>
