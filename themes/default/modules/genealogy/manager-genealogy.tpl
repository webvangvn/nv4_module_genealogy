<!-- BEGIN: tree -->
<li>
	<span {DIRTREE.class} id="iduser_{DIRTREE.id}">{DIRTREE.lev}.{DIRTREE.weight}: {DIRTREE.full_name}</span>
	<!-- BEGIN: wife -->
	- <span {WIFE.class} id="iduser_{WIFE.id}">{WIFE.full_name}</span>
	<!-- END: wife -->
	<!-- BEGIN: tree_content -->
	<ul>
		<!-- BEGIN: loop -->
		{TREE_CONTENT} <!-- END: loop -->
	</ul>
	<!-- END: tree_content -->
</li>
<!-- END: tree -->

<!-- BEGIN: main -->
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/dinamods.js"></script>
	<div id="dm_tabs_1"><!-- Tabs -->
			<ul class="dm_menu_1">
				<li class="dm_menu_item_1" id="start_tab"><a id="dm_tabs_1_1" class="dm_selected" rel="dm_tab_1_1" href="">Thông tin chi họ</a></li>
				<li class="dm_menu_item_1" id="center_tab"><a id="dm_tabs_1_2" rel="dm_tab_1_2" href="" > Phả đồ</a></li>
				<li class="dm_menu_item_1" id="center_tab"><a id="dm_tabs_1_3" rel="dm_tab_1_3" href="" > Ngày giỗ</a></li>
			</ul>
		</div>
		<div class="clearfix">
		</div>
		<div id="dm_container_11" >
			<div class="dm_tabcontent" id="dm_tab_1_1" >
				<form action="{NV_ACTION_FILE}" method="post">
					<table class="tab1" width="100%">
						<tbody>
							<tr>
								<td>Chi họ</td>
								<td>
								<select name="fid">
									<option value="0">-- Chọn chi họ hoặc nhập tên chi họ --</option>
									<!-- BEGIN: family -->
									<option value="{FAMILY.fid}"{FAMILY.selected}>{FAMILY.title}</option>
									<!-- END: family -->
								</select>
								</td>
							</tr>
						</tbody>
						<tbody class="second">
							<tr>
								<td>Thông tin chi họ:</span></td>
								<td>
								<input name="title" value="{DATA.title}" maxlength="255" style="width: 450px;" type="text"> <span style="color: #CC0000">(*)</span>
								<br>
								<br>
								(ghi chú phần này chỉ nhập tên dòng họ và nội dung đến Quận huyện. VD: <br> <b> Nguyễn Văn, làng Nông Sơn, Điện Phước, Điện Bàn, Quãng Nam </b> )
								<br>
								</td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>Phường/Xã :</td>
								<td>
								<select name="wardid">
									<option value="0">-- Chọn phường --</option>
									<!-- BEGIN: ward -->
									<option value="{WARD.ward_id}" {WARD.selected}>{WARD.title}</option>
									<!-- END: ward -->
								</select> <span style="color: #CC0000">(*)</span></td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>Quận/Huyện :</td>
								<td>
								<select name="districtid">
									<option value="0">-- Chọn quận --</option>
									<!-- BEGIN: district -->
									<option value="{DISTRICT.district_id}" {DISTRICT.selected}>{DISTRICT.title}</option>
									<!-- END: district -->
								</select> <span style="color: #CC0000">(*)</span></td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>Tỉnh/TP:</td>
								<td>
								<select name="cityid">
									<option value="0">-- Chọn tỉnh --</option>
									<!-- BEGIN: city -->
									<option value="{CITY.city_id}" {CITY.selected}>{CITY.title}</option>
									<!-- END: city -->
								</select> <span style="color: #CC0000">(*)</span></td>
							</tr>
						</tbody>
						
						<tbody class="second">
							<tr>
								<td colspan="2"><b>Phả ký</b> (nguồn gốc xuất xứ của gia tộc, hành trạng của Thuỷ tổ)
								<br>
								<br>
								{DATA.bodytext}</td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td colspan="2"><b>Tộc ước </b>(Các quy định của dòng họ)
								<br>
								<br>
								{DATA.rule}</td>
							</tr>
						</tbody>
						<tbody class="second">
							<tr>
								<td colspan="2"><b>Từ đường - Hương hoả</b> Ghi chép về phần đất (lăng mộ dòng họ) tài sản dòng họ( Vật thể như nhà thờ, phi vật thể như các bài cúng, tế ...)
								<br>
								<br>
								{DATA.content}</td>
							</tr>
						</tbody>
					</table>
					
					<table class="tab1" width="100%">
						<caption>Thông tin biên soạn</caption>		
						<tbody class="second">
							<tr>
								<td>Năm biên soạn:</span></td>
								<td>
								<input name="years" value="{DATA.years}" maxlength="55" style="width: 200px;" type="text">
								</td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>Người biên soạn:</span></td>
								<td>
								<input name="author" value="{DATA.author}" maxlength="255" style="width: 450px;" type="text">
								</td>
							</tr>
						</tbody >
						<tbody>
							<tr>
								<td>Tộc trưởng:</span></td>
								<td>
								<input name="patriarch" value="{DATA.patriarch}" maxlength="255" style="width: 450px;" type="text">
								</td>
							</tr>
						</tbody >
						<tbody class="second">
							<tr>
								<td>Người liên hệ:</span></td>
								<td>
								<input name="full_name" value="{DATA.full_name}" maxlength="255" style="width: 450px;" type="text">
								</td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>Điện thoại:</span></td>
								<td>
								<input name="telephone" value="{DATA.telephone}" maxlength="255" style="width: 200px;" type="text">
								</td>
							</tr>
						</tbody>
						<tbody  class="second">
							<tr>
								<td>Email:</span></td>
								<td>
								<input name="email" value="{DATA.email}" maxlength="255" style="width: 450px;" type="text">
								</td>
							</tr>
						</tbody>
						<tbody>
							<tr>
								<td>Thành viên được xem gia phả:</td>
								<td>
								<select name="who_view">
									<!-- BEGIN: who_view -->
									<option value="{WHO_VIEW.id}" {WHO_VIEW.selected}>{WHO_VIEW.title}</option>
									<!-- END: who_view -->
								</select> <span style="color: #CC0000">(*)</span></td>
							</tr>
						</tbody>		
					</table>
					<div style="text-align: center">
						<input name="gid" type="hidden" value="{DATA.gid}" />
						<input name="submit" type="submit" value="{LANG.save}" style="width: 200px;" />
					</div>
					<br/>
						Ghi chú: Nếu không nhập thông tin người liên hệ, hệ thống sẽ lấy thông tin của người tạo chi họ
						<br/><br/>
				</form>
			</div>
		</div>
		<div class="dm_tabcontent" id="dm_tab_1_2" >
			<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.core.css" rel="stylesheet" />
			<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.theme.css" rel="stylesheet" />
			<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.dialog.css" rel="stylesheet" />
			<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.treeview.css" rel="stylesheet" />
			<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.treeview.min.js"></script>
			<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.core.min.js"></script>
			<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.dialog.min.js"></script>
			<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/contextmenu/jquery.contextmenu.r2.js"></script>
			<div id="module_show_list">
			<center>
					<b>Hướng dẫn:</b><i>Click chuột phải lên từng thành viên để có thể cập nhật hoặc thêm mới vợ con.</i>
				</center>
				<br>
				<!-- BEGIN: foldertree -->
				<ul id="foldertree" class="filetree">
					{DATATREE}
				</ul>
				<!-- END: foldertree -->
				<!-- BEGIN: contextMenu -->
				<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/contextmenu/jquery.contextmenu.r2.js"></script>
				<div class="contextMenu" id="menu_genealogy_show">
					<ul>
						<li id="news1">
							<img src="{NV_BASE_SITEURL}assets/js/contextmenu/icons/copy.png" /> Thêm Con
						</li>
						<li id="news2">
							<img src="{NV_BASE_SITEURL}assets/js/contextmenu/icons/copy.png" /> Thêm Vợ
						</li>
						<li id="news3">
							<img src="{NV_BASE_SITEURL}assets/js/contextmenu/icons/copy.png" /> Thêm chồng
						</li>
						<li id="edit">
							<img src="{NV_BASE_SITEURL}assets/js/contextmenu/icons/rename.png" /> Sửa
						</li>
						<li id="delete">
							<img src="{NV_BASE_SITEURL}assets/js/contextmenu/icons/delete.png" /> Xóa
						</li>
					</ul>
				</div>
				<!-- END: contextMenu -->
			</div>
			<div id="create_genealogy_users" style="overflow:auto;display:none;padding:10px;" title="Họ : Nguyễn Văn{PAGE_TITLE}">
				<iframe id="modalIFrame" width="100%" height="100%" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto"></iframe>
			</div>
			<script type="text/javascript">
				//<![CDATA[
				$(document).ready(function() {
					$("#foldertree").treeview();
					$('#foldertree span').contextMenu('menu_genealogy_show', {
						menuStyle:{width:'120px'}, 
						onShowMenu : function(e, menu) {
							var idclass = $(e.target).attr('class');
							if(idclass.search('noadd')>0){
								$('#news1,#news2,#news3', menu).remove();
							}
							if(idclass== 'female' || idclass== 'female hover') {
								$('#news2', menu).remove();
							}
							else if(idclass== 'male' || idclass== 'male hover') {
								$('#news3', menu).remove();
							}
							return menu;
						},
						bindings : {
							'news1' : function(t) {
								var r_split = t.id.split("_");
								$("div#create_genealogy_users").dialog({
									autoOpen : false,
									width : 800,
									height : 500,
									modal : true,
									position : "center"
								}).dialog("open");
								
								$("#modalIFrame").attr('src', nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=users&gid={DATA.id}&relationships=1&parentid=' + r_split[1]);
							},
							'news2' : function(t) {
								var r_split = t.id.split("_");
								$("div#create_genealogy_users").dialog({
									autoOpen : false,
									width : 800,
									height : 500,
									modal : true,
									position : "center"
								}).dialog("open");
								$("#modalIFrame").attr('src', nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=users&gid={DATA.id}&relationships=2&parentid=' + r_split[1]);
							},
							'news3' : function(t) {
								var r_split = t.id.split("_");
								$("div#create_genealogy_users").dialog({
									autoOpen : false,
									width : 800,
									height : 500,
									modal : true,
									position : "center"
								}).dialog("open");
								$("#modalIFrame").attr('src', nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=users&gid={DATA.id}&relationships=3&parentid=' + r_split[1]);
							},
							'edit' : function(t) {
								var r_split = t.id.split("_");
								$("div#create_genealogy_users").dialog({
									autoOpen : false,
									width : 800,
									height : 500,
									modal : true,
									position : "center"
								}).dialog("open");
								
								$("#modalIFrame").attr('src', nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=users&id=' + r_split[1]);
							},
							'delete' : function(t) {
								if(confirm('Bạn có chắc chọn xóa thành viên, xóa thành viên này hệ thống sẽ xóa tất cả các thành viên là vợ, con, cháu ..')) {
									var r_split = t.id.split("_");
									window.location = nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=shows&&gid={DATA.id}&deleteid=' + r_split[1];
								}
							}
						}
					});
				});
				//]]>
			</script>
			<!-- BEGIN: create_users -->
			<script type="text/javascript">
				//<![CDATA[
				$(document).ready(function() {
					
					$("div#create_genealogy_users").dialog({
						autoOpen : false,
						width : 800,
						height : 500,
						modal : true,
						position : "center"
					}).dialog("open");
					$("#modalIFrame").attr('src', nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=users&gid={DATA.id}&parentid=0');
				});
				//]]>
			</script>
			<!-- END: create_users -->
		</div>
		<div class="dm_tabcontent" id="dm_tab_1_3" >
		</div>
<script type="text/javascript">
		function addLoadEvent(func) { if (typeof window.onload != 'function') { window.onload = func; } else { var oldonload = window.onload; window.onload = function() { if (oldonload) { oldonload(); } func(); } } }
		addLoadEvent(function(){
			var Dinamods=new dinamods('dm_tabs_1');
			Dinamods.setpersist(true);
			Dinamods.setselectedClassTarget('link');
			Dinamods.init(0,0);});
	</script>
<!-- END: main -->
