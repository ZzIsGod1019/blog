<div class="marBanner">
    <?php $_from = $this->_var['ad_child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');if (count($_from)):
    foreach ($_from AS $this->_var['child']):
?>
        <a href="<?php echo $this->_var['child']['ad_link']; ?>" target="_blank"><img src="<?php echo $this->_var['child']['ad_code']; ?>" width="<?php echo $this->_var['child']['ad_width']; ?>" height="<?php echo $this->_var['child']['ad_height']; ?>" alt="" /></a>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>                             	
</div>
