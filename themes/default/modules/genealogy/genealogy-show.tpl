<!-- BEGIN: tree -->
<li>
	<span {DIRTREE.class} id="iduser_{DIRTREE.id}"><a href="{DIRTREE.link}">{DIRTREE.lev}.{DIRTREE.weight}: {DIRTREE.full_name}</a></span>
	<!-- BEGIN: wife -->
	- <span {WIFE.class} id="iduser_{WIFE.id}"><a href="{WIFE.link}">{WIFE.full_name}</a></span>
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
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.dialog.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.treeview.css" rel="stylesheet" />
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.treeview.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.dialog.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/contextmenu/jquery.contextmenu.r2.js"></script>
<div class="pha-ky">
    <div class="pha-ky-one">
        <ul class="list-genealogy clearfix">
        	<li class="col-md-8 col-xs-12">
        		<a href="{DATA.link_main}">Thông tin chung </a>
        	</li>
        	<li class="col-md-8 col-xs-12 ">
        		<a href="{DATA.link_made_up}">Phả ký </a>
        	</li>
        	<li class="col-md-8 col-xs-12 {ACTIVE}">
        		<a href="{DATA.link_family_tree}">Phả đồ</a>
        	</li>
        	<li class="col-md-8 col-xs-12">
        		<a href="{DATA.link_convention}">Tộc ước</a>
        	</li>
        	<li class="col-md-8 col-xs-12">
        		<a href="{DATA.link_collapse}">Hương Hoả</a>
        	</li>
        	<li class="col-md-8 col-xs-12 ">
        		<a href="{DATA.link_anniversary}">Danh sách ngày giỗ</a>
        	</li>
        </ul>
		<!-- BEGIN: adminlink -->
		<ul class="list-genealogy clearfix">
        	<li class="col-md-24 col-xs-24">
        		{ADMINLINK}
        	</li>
        </ul>
		<!-- END: adminlink -->
    </div>
</div>

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
		
	});
	//]]>
</script>

<!-- END: main -->