<!-- BEGIN: main -->
			<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.core.css" rel="stylesheet" />
			<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.theme.css" rel="stylesheet" />
			<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.dialog.css" rel="stylesheet" />
			<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.treeview.css" rel="stylesheet" />
			<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.treeview.min.js"></script>
			<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.core.min.js"></script>
			<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.dialog.min.js"></script>
<div class="col-md-24">
		
		
	<div class="tab-giapha padding-topbottom">
		<table class="tabnkv col-md-24 table table-stripeds table-bordereds table-hover">
			<caption class="title-gia-pha">
					  Danh sách gia phả bạn quản trị
			</caption>
			<thead class="list-gia-pha main-title">
				<tr><th class="col-md-1">STT</th>
				<th class="col-md-5">Dòng họ</th>
				<th class="col-md-2">Tộc trưởng</th>
				<th class="col-md-1">Số Người</th>
				<th class="col-md-4">Quản lý</th>
			</tr></thead>
			<tbody class="dong-ho">
				<!-- BEGIN: loop -->
					<tr>
						<td>{DATA.weight}</td>
						<td><a title="{DATA.title}" href="{DATA.link}"><b>{DATA.title}</b></a></td>
						<td>{DATA.patriarch}</td>
						<td>{DATA.number}</td>
						<td><a href="{DATA.linkmanager}" >Quản trị gia phả</a> - Xóa</td>
					</tr>
				<!-- END: loop -->
				
			</tbody>
			<tr class="main-title">
				<td class="col-md-24" colspan="7"><input type="button" value="Thêm gia phả" onclick="creategenealogy(1)"></td>
			</tr>
		</table>
		<div class="clear" style="height: 0px">&nbsp;</div>
	</div>
</div>
<div id="create_genealogy" style="overflow:auto;display:none;padding:10px;" title="Thêm gia phả">
	<iframe id="modalIFrame" width="100%" height="100%" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="auto"></iframe>
</div>
<!-- END: main -->