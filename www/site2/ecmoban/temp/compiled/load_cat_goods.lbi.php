<?php echo $this->_var['get_adv']; ?>
<div class="floor-line-con floorOne floor-color-type-<?php echo $this->_var['goods_cat']['floor_sort_order']; ?>" data-title="<?php echo $this->_var['goods_cat']['alias_name']; ?>" data-idx="<?php echo $this->_var['goods_cat']['floor_sort_order']; ?>" id="floor_<?php echo $this->_var['goods_cat']['floor_sort_order']; ?>" ectype="floorItem">
    <div class="floor-hd" ectype="floorTit">
    	<i class="box_hd_arrow"></i>
    	<i class="box_hd_dec"></i>
        <div class="hd-tit"><?php echo $this->_var['goods_cat']['name']; ?></div>
        <div class="hd-tags">
            <ul>
                <li class="first current"><span>新品推荐</span><i class="arrowImg"></i></li>
                <?php $_from = $this->_var['goods_cat']['goods_level2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'cat');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['cat']):
        $this->_foreach['foo']['iteration']++;
?>
                <?php if ($this->_var['key'] < 7): ?>
                <li data-value='{"id":<?php echo empty($this->_var['cat']['id']) ? '0' : $this->_var['cat']['id']; ?>,"floornum":<?php echo empty($this->_var['goods_cat']['floor_num']) ? '0' : $this->_var['goods_cat']['floor_num']; ?>,"warehouse":<?php echo empty($this->_var['goods_cat']['warehouse_id']) ? '0' : $this->_var['goods_cat']['warehouse_id']; ?>,"area":<?php echo empty($this->_var['goods_cat']['area_id']) ? '0' : $this->_var['goods_cat']['area_id']; ?>}' data-flooreveval="0" ectype="floor_cat_content"><span><?php echo $this->_var['cat']['name']; ?></span><i class="arrowImg"></i></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
    </div>
	<?php if ($this->_var['goods_cat']['floor_style_tpl'] == 1): ?>
	<div class="floor-bd bd-mode-02">
		<div class="bd-left">
			<div class="floor-left-slide">
				<div class="bd">
					<?php echo $this->_var['cat_goods_banner']; ?>
				</div>
				<div class="hd"><ul></ul></div>
			</div>
			<div class="floor-left-adv mt10">
				<?php if ($this->_var['cat_goods_ad_left']): ?>
				<?php echo $this->_var['cat_goods_ad_left']; ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="bd-right">
			<div class="floor-tabs-content clearfix">
				<div class="f-r-main f-r-m-adv">
					<?php if ($this->_var['cat_goods_ad_right']): ?>
					<?php echo $this->_var['cat_goods_ad_right']; ?>
					<?php endif; ?>
				</div>
				<?php $_from = $this->_var['goods_cat']['goods_level3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'goods_level3');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['goods_level3']):
        $this->_foreach['foo']['iteration']++;
?>
				<div class="f-r-main" id="floor_cat_<?php echo $this->_var['goods_level3']['cats']; ?>">
					<ul class="p-list">
						<?php $_from = $this->_var['goods_level3']['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['foo']['iteration']++;
?>
						<li class="opacity_img">
							<a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>">
								<div class="p-img"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></div>
								<div class="p-name"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></div>
								<div class="p-price">
									<?php if ($this->_var['goods']['promote_price'] != ''): ?>
										<?php echo $this->_var['goods']['promote_price']; ?>
									<?php else: ?>
										<?php echo $this->_var['goods']['shop_price']; ?>
									<?php endif; ?>
								</div>
							</a>
						</li>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</div>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
		</div>
	</div>
	<?php elseif ($this->_var['goods_cat']['floor_style_tpl'] == 2): ?>
    <div class="floor-bd bd-mode-03">
        <div class="bd-left">
            <div class="floor-left-adv">
                <?php if ($this->_var['cat_goods_ad_left']): ?>
                <?php echo $this->_var['cat_goods_ad_left']; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="bd-right">
            <div class="floor-tabs-content clearfix">
                <div class="f-r-main f-r-m-adv">
                    <?php if ($this->_var['cat_goods_ad_right']): ?>
                    <?php echo $this->_var['cat_goods_ad_right']; ?>
                    <?php endif; ?>
                </div>
                <?php $_from = $this->_var['goods_cat']['goods_level3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'goods_level3');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['goods_level3']):
        $this->_foreach['foo']['iteration']++;
?>
                <div class="f-r-main" id="floor_cat_<?php echo $this->_var['goods_level3']['cats']; ?>">
                    <ul class="p-list">
                        <?php $_from = $this->_var['goods_level3']['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['foo']['iteration']++;
?>
                        <li class="opacity_img">
                            <a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>">
                                <div class="p-img"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></div>
                                <div class="p-name"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></div>
                                <div class="p-price">
                                    <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['goods']['promote_price']; ?>
                                    <?php else: ?>
                                        <?php echo $this->_var['goods']['shop_price']; ?>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
    </div>
	<?php elseif ($this->_var['goods_cat']['floor_style_tpl'] == 3): ?>
    <div class="floor-bd bd-mode-04">
        <div class="bd-left">
            <div class="floor-left-adv">
                <?php if ($this->_var['cat_goods_ad_left']): ?>
                <?php echo $this->_var['cat_goods_ad_left']; ?>
                <?php endif; ?>
            </div>
            <div class="floor-left-slide mt10">
                <div class="bd">
                    <?php echo $this->_var['cat_goods_banner']; ?>
                </div>
                <div class="hd"><ul></ul></div>
            </div>
        </div>
        <div class="bd-right">
            <div class="floor-tabs-content clearfix">
                <div class="f-r-main f-r-m-adv">
                    <?php if ($this->_var['cat_goods_ad_right']): ?>
                    <?php echo $this->_var['cat_goods_ad_right']; ?>
                    <?php endif; ?>
                </div>
                <?php $_from = $this->_var['goods_cat']['goods_level3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'goods_level3');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['goods_level3']):
        $this->_foreach['foo']['iteration']++;
?>
                <div class="f-r-main" id="floor_cat_<?php echo $this->_var['goods_level3']['cats']; ?>">
                    <ul class="p-list">
                        <?php $_from = $this->_var['goods_level3']['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['foo']['iteration']++;
?>
                        <li class="opacity_img">
                            <a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>">
                                <div class="p-img"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></div>
                                <div class="p-name"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></div>
                                <div class="p-price">
                                    <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['goods']['promote_price']; ?>
                                    <?php else: ?>
                                        <?php echo $this->_var['goods']['shop_price']; ?>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
    </div>
	<?php else: ?>
    <div class="floor-bd bd-mode-01">
        <div class="bd-left">
            <div class="floor-left-slide">
                <div class="bd">
                    <?php echo $this->_var['cat_goods_banner']; ?>
                </div>
                <div class="hd"><ul></ul></div>
            </div>
            <div class="floor-left-adv">
                <?php if ($this->_var['cat_goods_ad_left']): ?>
                <?php echo $this->_var['cat_goods_ad_left']; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="bd-right">
            <div class="floor-tabs-content clearfix">
                <div class="f-r-main f-r-m-adv">
                    <?php if ($this->_var['cat_goods_ad_right']): ?>
                    <?php echo $this->_var['cat_goods_ad_right']; ?>
                    <?php endif; ?>
                </div>
                <?php $_from = $this->_var['goods_cat']['goods_level3']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'goods_level3');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['goods_level3']):
        $this->_foreach['foo']['iteration']++;
?>
                <div class="f-r-main" id="floor_cat_<?php echo $this->_var['goods_level3']['cats']; ?>">
                    <ul class="p-list">
                        <?php $_from = $this->_var['goods_level3']['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['foo']['iteration']++;
?>
                        <li class="opacity_img">
                            <a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>">
                                <div class="p-img"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></div>
                                <div class="p-name"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></div>
                                <div class="p-price">
                                    <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['goods']['promote_price']; ?>
                                    <?php else: ?>
                                        <?php echo $this->_var['goods']['shop_price']; ?>
                                    <?php endif; ?>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
    </div>
	<?php endif; ?>
    <?php if ($this->_var['brands_theme2']): ?>
    <div class="floor-fd">
        <div class="floor-fd-brand clearfix">
            <?php $_from = $this->_var['brands_theme2']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key1', 'brands');$this->_foreach['b_foo1'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['b_foo1']['total'] > 0):
    foreach ($_from AS $this->_var['key1'] => $this->_var['brands']):
        $this->_foreach['b_foo1']['iteration']++;
?>
            <?php $_from = $this->_var['brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key2', 'brands');$this->_foreach['b_foo2'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['b_foo2']['total'] > 0):
    foreach ($_from AS $this->_var['key2'] => $this->_var['brands']):
        $this->_foreach['b_foo2']['iteration']++;
?>
            <div class="item">
                <a href="<?php echo $this->_var['brands']['url']; ?>" target="_blank">
                    <div class="link-l"></div>
                    <div class="img"><img src="<?php echo $this->_var['brands']['brand_logo']; ?>" title="<?php echo $this->_var['brands']['brand_name']; ?>"></div>
                    <div class="link"></div>
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <?php endif; ?>
</div>