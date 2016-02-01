<!-- BEGIN: main -->
<div class="col-md-24">	
	<div class="tab-giapha padding-topbottom">
		<table class="tabnkv col-md-24 table table-stripeds table-bordereds table-hover">
			<caption class="title-gia-pha">
					  Tìm kiếm gia phả : 
			</caption>
		</table>
		<div class="clear" style="height: 0px">&nbsp;</div>
	</div>
</div>
<div class="col-md-24">
		
		
	<div class="tab-giapha padding-topbottom">
		<table class="tabnkv col-md-24 table table-stripeds table-bordereds table-hover">
			<caption class="title-gia-pha">
					  Các Gia phả tại : {LOCAL_TITLE}
			</caption>
			<thead class="list-gia-pha main-title">
				<tr><th class="col-md-1">STT</th>
				<th class="col-md-5">Dòng họ</th>
				<th class="col-md-2">Tộc trưởng</th>
				<th class="col-md-2">Năm biên soạn</th>
				<th class="col-md-1">Số đời</th>
				<th class="col-md-1">Số Người</th>
			</tr></thead>
			<tbody class="dong-ho">
				<!-- BEGIN: loop -->
					<tr>
						<td>{DATA.weight}</td>
						<td><a title="{DATA.title}" href="{DATA.link}"><b>{DATA.title}</b></a></td>
						<td>{DATA.patriarch}</td>
						<td>{DATA.years}</td>
						<td>{DATA.maxlev}</td>
						<td>{DATA.number}</td>
					</tr>
				<!-- END: loop -->
				
			</tbody>
		</table>
		<div class="clear" style="height: 0px">&nbsp;</div>
	</div>
</div>
<!-- END: main -->