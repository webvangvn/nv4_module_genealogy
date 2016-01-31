<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{error}</div>
<!-- END: error -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.core.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.theme.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.menu.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.autocomplete.css" rel="stylesheet" />
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.datepicker.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_DATA}.js"></script>

<form class="form-inline m-bottom confirm-reload" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" enctype="multipart/form-data" method="post">
	<div class="row">
		<div class="col-sm-24 col-md-18">
			<table class="table table-striped table-bordered">
				<col class="w200" />
				<col />
				<tbody>
					<tr>
						<td><strong>{LANG.name}</strong>: <sup class="required">(∗)</sup></td>
						<td><input type="text" maxlength="255" value="{rowcontent.title}" id="idtitle" name="title" class="form-control"  style="width:350px"/><span class="text-middle"> {GLANG.length_characters}: <span id="titlelength" class="red">0</span>. {GLANG.title_suggest_max} </span></td>
					</tr>
					<tr>
						<td><strong>{LANG.alias}: </strong></td>
						<td><input class="form-control" name="alias" id="idalias" type="text" value="{rowcontent.alias}" maxlength="255"  style="width:350px"/>&nbsp; <em class="fa fa-refresh fa-lg fa-pointer" onclick="get_alias();">&nbsp;</em></td>
					</tr>
				</tbody>
			</table>
			
			<table class="table table-striped table-bordered table-hover">
				<col class="w200" />
				<col />
				<tbody>
					<tr>
						<td><strong>{LANG.content_homeimg}</strong></td>
						<td><input class="form-control" style="width:380px" type="text" name="homeimg" id="homeimg" value="{rowcontent.homeimgfile}"/> <input id="select-img-post" type="button" value="Browse server" name="selectimg" class="btn btn-info" /></td>
					</tr>
					<tr>
						<td>{LANG.content_homeimgalt}</td>
						<td><input class="form-control" type="text" maxlength="255" value="{rowcontent.homeimgalt}" id="homeimgalt" name="homeimgalt" style="width:100%" /></td>
					</tr>
					<tr>
						<td>{LANG.imgposition}</td>
						<td>
						<select class="form-control" name="imgposition">
							<!-- BEGIN: looppos -->
							<option value="{id_imgposition}" {posl}>{title_imgposition}</option>
							<!-- END: looppos -->
						</select></td>
					</tr>
				</tbody>
			</table>
			<table class="table table-striped table-bordered table-hover">
				<tbody>
					<tr>
						<td><strong>{LANG.content_hometext}</strong> <i>{LANG.content_notehome}.</i> {GLANG.length_characters}: <span id="descriptionlength" class="red">0</span>. {GLANG.description_suggest_max} </td>
					</tr>
					<tr>
						<td><textarea id="description" name="hometext" rows="5" cols="75" style="font-size:14px; width: 100%; height:100px;" class="form-control">{rowcontent.hometext}</textarea></td>
					</tr>
					<tr>
						<td><strong>{LANG.content_bodytext}</strong> <i>{LANG.content_bodytext_note}</i></td>
					</tr>
					<tr>
						<td>
						<div style="padding:2px; background:#CCCCCC; margin:0; display:block; position:relative">
							{edit_bodytext}
						</div></td>
					</tr>
					<tr>
						<td><strong>{LANG.content_rule}</strong> <i>{LANG.content_rule_note}</i></td>
					</tr>
					<tr>
						<td>
						<div style="padding:2px; background:#CCCCCC; margin:0; display:block; position:relative">
							{edit_rule}
						</div></td>
					</tr>
					<tr>
						<td><strong>{LANG.content}</strong> <i>{LANG.content_note}</i></td>
					</tr>
					<tr>
						<td>
						<div style="padding:2px; background:#CCCCCC; margin:0; display:block; position:relative">
							{edit_content}
						</div></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-sm-24 col-md-6">
			<div class="row">
				<div class="col-sm-12 col-md-24">
					<ul style="padding-left:4px; margin:0">
						<li>
							<p class="message_head">
								<cite>{LANG.content_fam}:</cite> <sup class="required">(∗)</sup>
							</p>
							<div class="message_body" style=" overflow: auto">
								<table class="table table-striped table-bordered table-hover">
									<tbody>
										<select class="form-control" name="fids" id="fids">
											<option value="-1"> -- {LANG.fam_all} -- </option>
											<!-- BEGIN: fid -->
											<option value="{FAMS.fid}" {FAMS.selected}>{FAMS.title}</option>
											<!-- END: fid -->
										</select>
									</tbody>
								</table>
							</div>
							<p class="message_head">
								<cite>{LANG.location}:</cite> 
							</p>
							<div class="message_body" style=" overflow: auto">
								<table class="table table-striped table-bordered table-hover">
									<tbody>
										<tr>
											<td> 
												{LANG.city}: <sup class="required">(∗)</sup>
											</td>
										</tr>
										<tr>
											<td>
												<select class="form-control" name="cityid" id="cityid">
												<!-- BEGIN: city -->
													<option value="{CITY.city_id}" {CITY.selected}>{CITY.title}</option>
												<!-- END: city -->
												</select>
											</td>
										</tr>
										<tr>
											<td> 
												{LANG.district}: <sup class="required">(∗)</sup>
											</td>
										</tr>
										<tr>
											<td>
												<select class="form-control" name="districtid" id="districtid">
													<option value="-1" >{LANG.choose_district}</option>
												</select>
											</td>
										</tr>
										<tr>
											<td> 
												{LANG.ward}: <sup class="required">(∗)</sup>
											</td>
										</tr>
										<tr>
											<td>
												<select class="form-control" name="wardid">
													<option value="-1" >{LANG.choose_ward}</option>
												</select>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</li>
						<li>
							<p class="message_head">
								<cite>{LANG.content_year}:</cite>
							</p>
							<div class="message_body">
								<input class="form-control" type="text" maxlength="255" value="{rowcontent.years}" name="years" style="width:100%" />
							</div>
						</li>
						<li>
							<p class="message_head">
								<cite>{LANG.content_author}:</cite>
							</p>
							<div class="message_body">
								<input class="form-control" type="text" maxlength="255" value="{rowcontent.author}" name="author" style="width:100%" />
							</div>
						</li>
						<li>
							<p class="message_head">
								<cite>{LANG.content_patriarch}:</cite>
							</p>
							<div class="message_body">
								<input class="form-control" type="text" maxlength="255" value="{rowcontent.patriarch}" name="patriarch" style="width:100%" />
							</div>
						</li>
						<li>
							<p class="message_head">
								<cite>{LANG.content_contact}:</cite>
							</p>
							<div class="message_body">
								<input class="form-control" type="text" maxlength="255" value="{rowcontent.full_name}" name="full_name" style="width:100%" />
							</div>
						</li>
						<li>
							<p class="message_head">
								<cite>{LANG.content_email}:</cite>
							</p>
							<div class="message_body">
								<input class="form-control" type="text" maxlength="255" value="{rowcontent.email}" name="email" style="width:100%" />
							</div>
						</li>
						<li>
							<p class="message_head">
								<cite>{LANG.content_mobile}:</cite>
							</p>
							<div class="message_body">
								<input class="form-control" type="text" maxlength="255" value="{rowcontent.mobile}" name="telephone" style="width:100%" />
							</div>
						</li>
						<li>
							<p class="message_head">
								<cite>{LANG.content_tag}:</cite>
							</p>
							<div class="message_body" style="overflow: auto">
								<div class="clearfix uiTokenizer uiInlineTokenizer">
		                            <div id="keywords" class="tokenarea">
		                                <!-- BEGIN: keywords -->
		                                <span class="uiToken removable" title="{KEYWORDS}" ondblclick="$(this).remove();">
		                                    {KEYWORDS}
		                                    <input type="hidden" autocomplete="off" name="keywords[]" value="{KEYWORDS}" />
		                                    <a onclick="$(this).parent().remove();" class="remove uiCloseButton uiCloseButtonSmall" href="javascript:void(0);"></a>
		                                </span>
		                                <!-- END: keywords -->
		                            </div>
		                            <div class="uiTypeahead">
		                                <div class="wrap">
		                                    <input type="hidden" class="hiddenInput" autocomplete="off" value="" />
		                                    <div class="innerWrap">
		                                        <input id="keywords-search" type="text" placeholder="{LANG.input_keyword_tags}" class="form-control textInput" style="width: 100%;" />
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                	</div>
						</li>
						<li>
							<p class="message_head">
								<cite>{LANG.content_publ_date}</cite>
							</p>
							<div class="message_body">
								<input class="form-control" name="publ_date" id="publ_date" value="{publ_date}" style="width: 90px;" maxlength="10" type="text"/>
							</div>
						</li>
						<li>
							<p class="message_head">
								<cite>{LANG.content_exp_date}:</cite>
							</p>
							<div class="message_body">
								<input class="form-control" name="exp_date" id="exp_date" value="{exp_date}" style="width: 90px;" maxlength="10" type="text"/>
								<div style="margin-top: 5px;">
									<input type="checkbox" value="1" name="archive" {archive_checked} />
									<label> {LANG.content_archive} </label>
								</div>
							</div>
						</li>
					</ul>
				</div>
				<div class="col-sm-12 col-md-24">
					<ul style="padding:4px; margin:0">
						<li>
							<p class="message_head">
								<cite>{LANG.content_allowed_comm}:</cite>
							</p>
							<div class="message_body">
								<!-- BEGIN: allowed_comm -->
								<div class="row">
									<label><input name="allowed_comm[]" type="checkbox" value="{ALLOWED_COMM.value}" {ALLOWED_COMM.checked} />{ALLOWED_COMM.title}</label>
								</div>
								<!-- END: allowed_comm -->
								<!-- BEGIN: content_note_comm -->
								<div class="alert alert-info">{LANG.content_note_comm}</div>
								<!-- END: content_note_comm -->
							</div>
						</li>
						<li>
							<p class="message_head">
								<cite>{LANG.content_extra}:</cite>
							</p>
							<div class="message_body">
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="inhome" {inhome_checked}/>
									<label> {LANG.content_inhome} </label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_rating" {allowed_rating_checked}/>
									<label> {LANG.content_allowed_rating} </label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_send" {allowed_send_checked}/>
									<label> {LANG.content_allowed_send} </label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_print" {allowed_print_checked} />
									<label> {LANG.content_allowed_print} </label>
								</div>
								<div style="margin-bottom: 2px;">
									<input type="checkbox" value="1" name="allowed_save" {allowed_save_checked} />
									<label> {LANG.content_allowed_save} </label>
								</div>
								<div style="margin-bottom: 2px;">
								<input type="checkbox" value="1" name="copyright"{checkcop}/>
									<label> {LANG.content_copyright} </label>
								</div>
							</div>
						</li>
						
						<!-- BEGIN: googleplus -->
						<li>
							<p class="message_head">
								<cite>{LANG.googleplus}:</cite>
							</p>
							<div class="message_body">
								<select class="form-control" name="gid">
									<!-- BEGIN: gid -->
									<option value="{GOOGLEPLUS.gid}"{GOOGLEPLUS.selected}>{GOOGLEPLUS.title}</option>
									<!-- END: gid -->
								</select>
							</div>
						</li>
						<!-- END: googleplus -->
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="text-center">
		<br/>
		<input type="hidden" value="1" name="save" />
		<input type="hidden" value="{rowcontent.id}" name="id" />
		<!-- BEGIN:status -->
		<input class="btn btn-primary submit-post" name="statussave" type="submit" value="{LANG.save}" />
		<!-- END:status -->
		<!-- BEGIN:status0 -->
		<input class="btn btn-primary submit-post" name="status4" type="submit" value="{LANG.save_temp}" />
		<input class="btn btn-primary submit-post" name="status1" type="submit" value="{LANG.publtime}" />
		<!-- END:status0 -->
        <!-- BEGIN:status1 -->
		<input class="btn btn-primary submit-post" name="status4" type="submit" value="{LANG.save_temp}" />
		<input class="btn btn-primary submit-post" name="status6" type="submit" value="{LANG.save_send_admin}" />
            <!-- BEGIN:status0 -->
            <input class="btn btn-primary submit-post" name="status0" type="submit" value="{LANG.save_send_spadmin}" />
            <!-- END:status0 -->
		<!-- END:status1 -->
		<br />
	</div>
</form>
<div id="message"></div>
<script type="text/javascript">
//<![CDATA[
var LANG = [];
var CFG = [];
CFG.uploads_dir_user = '{UPLOADS_DIR_USER}';
CFG.upload_current = '{UPLOAD_CURRENT}';
LANG.content_tags_empty = '{LANG.content_tags_empty}.<!-- BEGIN: auto_tags --> {LANG.content_tags_empty_auto}.<!-- END: auto_tags -->';
LANG.alias_empty_notice = '{LANG.alias_empty_notice}';
var content_checkcatmsg = "{LANG.content_checkcatmsg}";

<!-- BEGIN: getalias -->
$("#idtitle").change(function() {
	get_alias();
});
<!-- END: getalias -->
//]]>
</script>
<script type="text/javascript">
	$(document).ready(function() { 
		
		//  This script only runs if there is a province field on the page.
        var city = $('select[name=cityid]');
        if (city.length == 0) return;
        if ($('select[name=districtid]').length == 0) return;
        city.unbind('change');
        city.change(function() {
            var cityCode = $('select[name=cityid]').val();
				
			var districtCode = $('select[name=districtid]').val();
            if (cityCode == null || cityCode.trim().length == 0) {
                return;
            };
			if (districtCode == null || districtCode.trim().length == 0) {
					return;
            };	
            $("select[name=districtid]").empty(); 
			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=province&cityid=' + cityCode + '&districtid={rowcontent.districtid}', function(res) {
				$("select[name=districtid]").append("<option value='-1'> {LANG.choose_district} </option>");
				$("select[name=districtid]").append(res);	
				$("select[name=wardid]").empty();
				if( districtCode == -1) {
					districtCode={rowcontent.districtid};
					$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ward&districtid='+districtCode+'&wardid={rowcontent.wardid}', function(res) {
						$("select[name=wardid]").append("<option value> {LANG.choose_ward}  </option>");
						$("select[name=wardid]").append(res);
					});
				}else{
					$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ward&districtid='+districtCode+'&wardid={rowcontent.wardid}', function(res) {
						$("select[name=wardid]").append("<option value> {LANG.choose_ward}  </option>");
					});
				}					
			});
			return;
        });
        // Set the default country code to the United States (US).
        $("select[name='cityid']").val('79').change();
		var district = $('select[name=districtid]');
        district.unbind('change');
        district.change(function() {
            var districtCode = $('select[name=districtid]').val();
			if (districtCode == null || districtCode.trim().length == 0) {
					return;
            };
			$("select[name=wardid]").empty();

			$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ward&districtid='+districtCode+'&wardid={rowcontent.wardid}', function(res) {
				$("select[name=wardid]").append("<option value> {LANG.choose_ward}  </option>");
				$("select[name=wardid]").append(res);
			});
			return;
        });
		$("#fids").select2({
			language: '{NV_LANG_DATA}'
		});
		$("#cityid").select2({
			language: '{NV_LANG_DATA}'
		});
	});
</script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.menu.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.autocomplete.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/ui/jquery.ui.datepicker.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/admin_default/js/genealogy_content.js"></script>
<!-- END:main -->