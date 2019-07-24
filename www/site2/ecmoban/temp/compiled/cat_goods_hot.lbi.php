
<?php if ($this->_var['ad_child']): ?>
<div class="floor-new">
<ul>
	<?php $_from = $this->_var['ad_child']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ad');$this->_foreach['noad'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['noad']['total'] > 0):
    foreach ($_from AS $this->_var['ad']):
        $this->_foreach['noad']['iteration']++;
?>
    <li <?php if (($this->_foreach['noad']['iteration'] == $this->_foreach['noad']['total'])): ?> class="last"<?php endif; ?>>
    	<div class="new-left">
    		<a href="<?php echo $this->_var['ad']['ad_link']; ?>" target="_blank"><img src="<?php echo $this->_var['ad']['ad_code']; ?>" style=" max-width:<?php echo $this->_var['ad']['ad_width']; ?>px; max-height:<?php echo $this->_var['ad']['ad_height']; ?>px; text-align:center; float:none;"></a>
        </div>
    </li>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
</div>
<?php endif; ?>