<!-- BEGIN: main -->
<!-- BEGIN: fam_title -->
<div style="background:#eee;padding:10px">
	{FAM_TITLE}
</div>
<!-- END: fam_title -->
<!-- BEGIN: data -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<col span="6" style="white-space: nowrap;" />
		<col class="w250" />
		<col style="white-space: nowrap;" />
		<thead>
			<tr>
				<th class="text-center">{LANG.weight}</th>
				<th class="text-center">{LANG.name}</th>
				<th class="text-center">{LANG.inhome}</th>
				<th class="text-center">{LANG.numlinks}</th>
				<th class="text-center"><img src="{NV_BASE_SITEURL}themes/default/images/icons/new.gif" title="{LANG.newday}"/></th>
				<th class="text-center">{LANG.viewfam_page}</th>
				<th class="text-center">{LANG.functional}</th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">
				<!-- BEGIN: stt -->
				{STT}
				<!-- END: stt -->
				<!-- BEGIN: weight -->
				<select class="form-control" id="id_weight_{ROW.fid}" onchange="nv_chang_fam('{ROW.fid}','weight');">
					<!-- BEGIN: loop -->
					<option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
					<!-- END: loop -->
				</select>
				<!-- END: weight -->
				</td>
				<td><a href="{ROW.link}"><strong>{ROW.title}</strong>
				<!-- BEGIN: numsubfam -->
				<span class="red">({NUMSUBFAM})</span>
				<!-- END: numsubfam -->
				</a></td>
				<td class="text-center">
				<!-- BEGIN: disabled_inhome -->
				{INHOME}
				<!-- END: disabled_inhome -->
				<!-- BEGIN: inhome -->
				<select class="form-control" id="id_inhome_{ROW.fid}" onchange="nv_chang_fam('{ROW.fid}','inhome');">
					<!-- BEGIN: loop -->
					<option value="{INHOME.key}"{INHOME.selected}>{INHOME.title}</option>
					<!-- END: loop -->
				</select>
				<!-- END: inhome -->
				</td>
				<td class="text-center">
				<!-- BEGIN: title_numlinks -->
				{NUMLINKS}
				<!-- END: title_numlinks -->
				<!-- BEGIN: numlinks -->
				<select class="form-control" id="id_numlinks_{ROW.fid}" onchange="nv_chang_fam('{ROW.fid}','numlinks');">
					<!-- BEGIN: loop -->
					<option value="{NUMLINKS.key}"{NUMLINKS.selected}>{NUMLINKS.title}</option>
					<!-- END: loop -->
				</select>
				<!-- END: numlinks -->
				</td>
				<td class="text-center">
				<!-- BEGIN: title_newday -->
				{NEWDAY}
				<!-- END: title_newday -->
				<!-- BEGIN: newday -->
				<select class="form-control" id="id_newday_{ROW.fid}" onchange="nv_chang_fam('{ROW.fid}','newday');">
					<!-- BEGIN: loop -->
					<option value="{NEWDAY.key}"{NEWDAY.selected}>{NEWDAY.title}</option>
					<!-- END: loop -->
				</select>
				<!-- END: newday -->
				</td>
				<td class="text-left">
				<!-- BEGIN: disabled_viewfam -->
				{VIEWFAM}
				<!-- END: disabled_viewfam -->
				<!-- BEGIN: viewfam -->
				<select class="form-control" id="id_viewfam_{ROW.fid}" onchange="nv_chang_fam('{ROW.fid}','viewfam');">
					<!-- BEGIN: loop -->
					<option value="{VIEWFAM.key}"{VIEWFAM.selected}>{VIEWFAM.title}</option>
					<!-- END: loop -->
				</select>
				<!-- END: viewfam -->
				</td>
				<td class="text-center">{ROW.adminfuncs}</td>
			</tr>
			<!-- END: loop -->
		</tbody>
	</table>
</div>
<!-- END: data -->
<!-- END: main -->