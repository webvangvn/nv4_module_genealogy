<!-- BEGIN: main -->
<div class="pha-ky">
    <div class="pha-ky-one">
        <ul class="list-genealogy clearfix">
        	<li class="col-md-8 col-xs-12">
        		<a href="{GENEALOGY.link_main}">Thông tin chung </a>
        	</li>
        	<li class="col-md-8 col-xs-12 ">
        		<a href="{GENEALOGY.link_made_up}">Phả ký </a>
        	</li>
        	<li class="col-md-8 col-xs-12 {ACTIVE}">
        		<a href="{GENEALOGY.link_family_tree}">Phả đồ</a>
        	</li>
        	<li class="col-md-8 col-xs-12">
        		<a href="{GENEALOGY.link_convention}">Tộc ước</a>
        	</li>
        	<li class="col-md-8 col-xs-12">
        		<a href="{GENEALOGY.link_collapse}">Hương Hoả</a>
        	</li>
        	<li class="col-md-8 col-xs-12 ">
        		<a href="{GENEALOGY.link_anniversary}">Danh sách ngày giỗ</a>
        	</li>
        </ul>
    </div>
</div>
<!-- BEGIN: info -->
	<div class="tab-giapha padding-topbottom">
        <div class="tabnkv tabnkvs">
			<div class="fl col-md-24">
				<div class="title-gia-pha">
					<a class="fa fa-certificate">
						<span>
						Đời thứ {DATA.lev}: {DATA.full_name}
						</span>
					</a>
				</div>
				<table class="col-md-16 tabnkvv tabnkv table table-striped table-bordered table-hover">
						
						<colgroup><col style="width: 180px">
						</colgroup>
						<!-- BEGIN: loop -->
						<tbody {DATALOOP.class}>
							<tr>
								<td align="right">{DATALOOP.lang}:</td>
								<td>{DATALOOP.value}</td>
							</tr>
						</tbody>
						<!-- END: loop -->
					<tbody>
					</tbody>
				</table>
				<div id="imghome" class="fr col-md-8 text-center">
					<a href="{DATA.image}" title="" rel="shadowbox"><img alt="{DATA.full_name}" src="{DATA.image}" width="150"></a>
				</div>
			</div>

		</div>
		<div class="clearfix">&nbsp;</div>
	</div>

	<!-- BEGIN: content -->
	<div class="tab-giapha">
        <div class="tabnkv tabnkvsbc">
    	    <div class="title-gia-pha sunghiep-congduc">
                <a class="fa fa-certificate">
    		      <span>{LANG.u_content}</span>
                </a>
            </div>
        	<div class="comment sunghiep-congduc-a">
        	    <p>{DATA.content}</p>
        	</div>
        	<script type="text/javascript">
                $('.comment').readmore({maxHeight: 140});
            </script>

        </div>
    </div>
	<!-- END: content -->

	<!-- BEGIN: orgchart -->
	<script type="text/javascript" src="http://www.google.com/jsapi"></script>
	<script type="text/javascript">
		google.load('visualization', '1', {
			packages : ['orgchart']
		});

	</script>
	<script type="text/javascript">
		function drawVisualization() {
			// Create and populate the data table.
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Name');
			data.addColumn('string', 'Manager');
			data.addRows({DATACHARTROWS});
			
			<!-- BEGIN: looporgchart -->
				data.setCell({DATACHART.number}, 0, '{DATACHART.id}', '<a href="{DATACHART.link}">{DATACHART.full_name}</a>');
				<!-- BEGIN: looporgchart2 -->
				data.setCell({DATACHART.number}, 1, '{DATACHART.parentid}');
				<!-- END: looporgchart2 -->		
			<!-- END: looporgchart -->

			// Create and draw the visualization.
			new google.visualization.OrgChart(document.getElementById('visualization')).draw(data, {
				allowHtml : true
			});
		}
		google.setOnLoadCallback(drawVisualization);
	</script>
	<div class="tab-giapha padding-topbottom">
		<div class="tabnkv">
			<div class="title-gia-pha">
				<a class="fa fa-certificate">
					<span>Sơ đồ gia phả</span>
				</a>
			</div>
		</div>
		<div id="visualization" style="white-space: nowrap; width: 100%; overflow: auto;">
		</div>
		<br>
   </div>

	<!-- END: orgchart -->

	<!-- BEGIN: parentid -->
	<div class="tab-giapha padding-topbottom">
		<div class="tabnkv tabnkvsbc">
    	    <div class="title-gia-pha sunghiep-congduc">
                <a class="fa fa-certificate">
    		      <span>{PARENTIDCAPTION}</span>
                </a>
            </div>
        	<div class="comment sunghiep-congduc-a">
        	    <table class="tabnkv" width="100%">

					<col style="width: 20px" />
					<col style="width: 180px" />
					<thead>
						<td>STT</td>
						<td>Họ tên</td>
						<td>Ngày Sinh</td>
						<td>Trạng thái</td>
					</thead>
					<!-- BEGIN: loop2 -->
					<tbody {DATALOOP.class}>
						<tr>
							<td align="right">{DATALOOP.number}</td>
							<td><a href="{DATALOOP.link}">{DATALOOP.mogul} {DATALOOP.full_name}</a></td>
							<td> {DATALOOP.birthday}</td>
							<td> {DATALOOP.status}</td>
						</tr>
					</tbody>
					<!-- END: loop2 -->
				</table>
        	</div>
        	<script type="text/javascript">
                $('.comment').readmore({maxHeight: 140});
            </script>

        </div>
    </div>
	
	<!-- END: parentid -->
<!-- END:info -->
<!-- BEGIN: not_info -->
không có thông tin
<!-- END: not_info -->
<!-- END: main -->