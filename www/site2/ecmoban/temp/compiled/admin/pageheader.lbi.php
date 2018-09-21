<div class="admin-header clearfix" style="min-width:1280px;">
	<!-- 颜色选择层 bylu -->
	<div class="bgSelector"></div>
	<div class="admin-logo">
    	<a href="javascript:void(0);" data-param="home" target="workspace">
        <?php if ($this->_var['admin_logo']): ?>
        <img src="<?php echo $this->_var['admin_logo']; ?>" />
        <?php else: ?>
        <img src="images/admin-logo.png" />
        <?php endif; ?>
        </a>
    	<div class="foldsider"><i class="icon icon-indent-left"></i></div>
    </div>
	<div class="module-menu">
		<ul>
        <?php $_from = $this->_var['nav_top']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');if (count($_from)):
    foreach ($_from AS $this->_var['nav']):
?>
        	<?php if ($this->_var['nav']['children'] && $this->_var['nav']['type'] != 'home'): ?>
				<?php if ($this->_var['nav']['type'] != ""): ?><li data-param="<?php echo $this->_var['nav']['type']; ?>"><a href="javascript:void(0);"><?php echo $this->_var['nav']['label']; ?></a></li><?php endif; ?>
            <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</div>
	<div class="admin-header-right">
		<div class="manager">
			<dl>
				<dt class="name"><?php echo $this->_var['admin_info']['user_name']; ?></dt>
				<dd class="group">超级管理员</dd>
			</dl>
			<span class="avatar">
				<form  action="index.php" id="fileForm" method="post"  enctype="multipart/form-data"  runat="server" >
					<input name="img" type="file" class="admin-avatar-file" id="_pic" title="设置管理员头像">
				</form>
				<img nctype="admin_avatar" src="<?php if ($this->_var['admin_info']['admin_user_img']): ?>../<?php echo $this->_var['admin_info']['admin_user_img']; ?><?php else: ?>images/admin.png<?php endif; ?>" />
			</span>
            <div id="admin-manager-btn" class="admin-manager-btn"><i class="arrow"></i></div>
			<div class="manager-menu">
				<div class="title">
					<h4>最后登录</h4>
					<a href="privilege.php?act=edit&id=<?php echo $_SESSION['admin_id']; ?>" target="workspace" class="edit_pwd">修改密码</a>
				</div>
				<div class="login-date">
					<strong><?php echo $this->_var['admin_info']['last_login']; ?></strong>
					<span>(IP:<?php echo $this->_var['admin_info']['last_ip']; ?>)</span>
				</div>
				<div class="title mt10">
					<h4>常用操作</h4>
					<a href="javascript:;" class="add_nav">添加菜单</a>
				</div>
				<div class="quick_link">
					<ul>
                        <?php $_from = $this->_var['auth_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');if (count($_from)):
    foreach ($_from AS $this->_var['vo']):
?>
                        <li class="tl"><a href="<?php echo $this->_var['vo']['1']; ?>" target="workspace"><?php echo $this->_var['vo']['0']; ?></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="operate">
			<li style="position: relative;" ectype="oper_msg">
				<a href="javascript:void(0);" class="msg" title="查看消息">&nbsp;</a>
				<!-- 消息通知 bylu -->
				<div id="msg_Container">
                	<div class="item">
                        <h3 class="order_msg" ectype="msg_tit">订单提示<em class="iconfont icon-up"></em></h3>
                        <div class="msg_content" ectype="orderMsg" style="display:block;"></div>
                    </div>
					
					<div class="item">
                        <h3 ectype="msg_tit">商品提示<em class="iconfont icon-down"></em></h3>
                        <div class="msg_content" ectype="goodMsg"></div>
                    </div>
					
                    <div class="item">
                    	<h3 ectype="msg_tit">商家审核提示<em class="iconfont icon-down"></em></h3>
                        <div class="msg_content" ectype="sellerMsg"></div>
                    </div>
					
                    <div class="item">
                    	<h3 ectype="msg_tit">广告位提示<em class="iconfont icon-down"></em></h3>
                        <div class="msg_content" ectype="advMsg"></div>
                    </div>
					
                    <div class="item">
                    	<h3 ectype="msg_tit">会员提醒<em class="iconfont icon-down"></em></h3>
                        <div class="msg_content" ectype="userMsg"></div>
                    </div>
					
					<div class="item">
                    	<h3 ectype="msg_tit">活动提醒<em class="iconfont icon-down"></em></h3>
                        <div class="msg_content" ectype="promotionMsg"></div>
                    </div>
				</div>
			</li>
			<i></i>
			<li><a href="../" target="_blank" class="home" title="新窗口打开商城首页">&nbsp;</a></li>
			<i></i>
			<li><a href="javascript:void(0);" class="sitemap" title="查看全部管理菜单">&nbsp;</a></li>
			<i></i>
			<li><a href="javascript:void(0);" id="trace_show" class="style-color" title="给管理中心换个颜色">&nbsp;</a></li>
			<i></i>
			<li><a href="index.php?act=clear_cache" class="clear" target="workspace" title="清除缓存">&nbsp;</a></li>
			<i></i>
			<li><a href="privilege.php?act=logout" class="prompt" title="安全退出管理中心">&nbsp;</a></li>
		</div>
	</div>
</div>

<!-- 快捷菜单弹窗 bylu -->
<div id="allMenu" style="display: none;">
	<div class="admincp-map ui-widget-content ui-draggable" nctype="map_nav" id="draggable">
		<div class="title ui-widget-header ui-draggable-handle" style="border: none;background: #fff;">
			<h3>管理中心全部菜单</h3>
			<h5>切换显示全部管理菜单，通过点击勾选可添加菜单为管理常用操作项，最多添加10个</h5>
			<span><a nctype="map_off" onclick="$('#allMenu').hide();" href="JavaScript:void(0);">X</a></span></div>
		<div class="content">
			<ul class="admincp-map-nav">
				<li class=""><a href="javascript:void(0);" data-param="map-system">平台</a></li>
				<li class="selected"><a href="javascript:void(0);" data-param="map-shop">商城</a></li>
				<li class=""><a href="javascript:void(0);" data-param="map-mobile">手机端</a></li>
                <li class=""><a href="javascript:void(0);" data-param="map-cms">APP</a></li>
                <li class=""><a href="javascript:void(0);" data-param="map-cms">资源</a></li>
			</ul>
			<div class="admincp-map-div" data-param="map-system" style="display: none;">
                <?php $_from = $this->_var['nav_top']['menuplatform']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');if (count($_from)):
    foreach ($_from AS $this->_var['vo']):
?>
                <dl>
                    <dt><?php echo $this->_var['vo']['label']; ?></dt>
                    <?php $_from = $this->_var['vo']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo2');if (count($_from)):
    foreach ($_from AS $this->_var['vo2']):
?>
                    <dd class="
                    <?php $_from = $this->_var['auth_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo3');if (count($_from)):
    foreach ($_from AS $this->_var['vo3']):
?>
                    <?php if ($this->_var['vo3']['0'] == $this->_var['vo2']['label']): ?>selected<?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    "><a href="<?php echo $this->_var['vo2']['action']; ?>" data-param="" target="workspace"><?php echo $this->_var['vo2']['label']; ?></a><i
                            class="fa fa-check-square-o"></i></dd>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                </dl>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
			<div class="admincp-map-div" data-param="map-shop" style="display: block;">
                <?php $_from = $this->_var['nav_top']['menushopping']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');if (count($_from)):
    foreach ($_from AS $this->_var['vo']):
?>
				<dl>
					<dt><?php echo $this->_var['vo']['label']; ?></dt>
                    <?php $_from = $this->_var['vo']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo2');if (count($_from)):
    foreach ($_from AS $this->_var['vo2']):
?>
					<dd class="
					<?php $_from = $this->_var['auth_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo3');if (count($_from)):
    foreach ($_from AS $this->_var['vo3']):
?>
                    <?php if ($this->_var['vo3']['0'] == $this->_var['vo2']['label']): ?>selected<?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					"><a href="<?php echo $this->_var['vo2']['action']; ?>" data-param="" target="workspace"><?php echo $this->_var['vo2']['label']; ?></a><i
							class="fa fa-check-square-o"></i></dd>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

				</dl>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
            <div class="admincp-map-div" data-param="map-mobile" style="display: none;">
                <?php $_from = $this->_var['nav_top']['ectouch']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');if (count($_from)):
    foreach ($_from AS $this->_var['vo']):
?>
                <dl>
                    <dt><?php echo $this->_var['vo']['label']; ?></dt>
                    <?php $_from = $this->_var['vo']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo2');if (count($_from)):
    foreach ($_from AS $this->_var['vo2']):
?>
                    <dd class="
                    <?php $_from = $this->_var['auth_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo3');if (count($_from)):
    foreach ($_from AS $this->_var['vo3']):
?>
                    <?php if ($this->_var['vo3']['0'] == $this->_var['vo2']['label']): ?>selected<?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    "><a href="<?php echo $this->_var['vo2']['action']; ?>" data-param="" target="workspace"><?php echo $this->_var['vo2']['label']; ?></a><i
                            class="fa fa-check-square-o"></i></dd>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                </dl>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
            
            <div class="admincp-map-div" data-param="map-cms" style="display: none;">
                <?php $_from = $this->_var['nav_top']['ecjia']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');if (count($_from)):
    foreach ($_from AS $this->_var['vo']):
?>
                <dl>
                    <dt><?php echo $this->_var['vo']['label']; ?></dt>
                    <?php $_from = $this->_var['vo']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo2');if (count($_from)):
    foreach ($_from AS $this->_var['vo2']):
?>
                    <dd class="
                    <?php $_from = $this->_var['auth_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo3');if (count($_from)):
    foreach ($_from AS $this->_var['vo3']):
?>
                    <?php if ($this->_var['vo3']['0'] == $this->_var['vo2']['label']): ?>selected<?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    "><a href="<?php echo $this->_var['vo2']['action']; ?>" data-param="" target="workspace"><?php echo $this->_var['vo2']['label']; ?></a><i
                            class="fa fa-check-square-o"></i></dd>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                </dl>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
			<div class="admincp-map-div" data-param="map-cms" style="display: none;">
                <?php $_from = $this->_var['nav_top']['menuinformation']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');if (count($_from)):
    foreach ($_from AS $this->_var['vo']):
?>
                <dl>
                    <dt><?php echo $this->_var['vo']['label']; ?></dt>
                    <?php $_from = $this->_var['vo']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo2');if (count($_from)):
    foreach ($_from AS $this->_var['vo2']):
?>
                    <dd class="
                    <?php $_from = $this->_var['auth_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo3');if (count($_from)):
    foreach ($_from AS $this->_var['vo3']):
?>
                    <?php if ($this->_var['vo3']['0'] == $this->_var['vo2']['label']): ?>selected<?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    "><a href="<?php echo $this->_var['vo2']['action']; ?>" data-param="" target="workspace"><?php echo $this->_var['vo2']['label']; ?></a><i
                            class="fa fa-check-square-o"></i></dd>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                </dl>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
			
		</div>
	</div>
</div>
