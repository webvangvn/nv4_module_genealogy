<!-- BEGIN: main -->

<div class="pha-ky">
    <div class="pha-ky-one">
        <ul class="list-genealogy clearfix">
        	<li class="col-md-8 col-xs-12">
        		<a href="{DATA.link_main}">Thông tin chung </a>
        	</li>
        	<li class="col-md-8 col-xs-12 ">
        		<a href="{DATA.link_made_up}">Phả ký </a>
        	</li>
        	<li class="col-md-8 col-xs-12">
        		<a href="{DATA.link_family_tree}">Phả đồ</a>
        	</li>
        	<li class="col-md-8 col-xs-12">
        		<a href="{DATA.link_convention}">Tộc ước</a>
        	</li>
        	<li class="col-md-8 col-xs-12">
        		<a href="{DATA.link_collapse}">Hương Hoả</a>
        	</li>
        	<li class="col-md-8 col-xs-12 {ACTIVE}">
        		<a href="{DATA.link_anniversary}">Danh sách ngày giỗ</a>
        	</li>
        </ul>
    </div>
    <div class="pha-ky-tow">
        <table class="tabnkv" width="100%">
	<caption>
		Ngày giỗ: {DATA.title}
	</caption>
	<tbody>
		<thead>
		<tr>
			<td>Số Thứ tự</td>
			<td>Ngày giỗ</td>
			<td>Họ và tên</td>
		</tr>
		</thead>
	<!-- BEGIN: loop -->
	<tbody {ANNIVERSARY.class}>
		<tr>
			<td>{ANNIVERSARY.number}</td>
			<td>{ANNIVERSARY.date}</td>
			<td>{ANNIVERSARY.full_name}</td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
    </div>
</div>




{DATA.description}

<!-- END: main -->