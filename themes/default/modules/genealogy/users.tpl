<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Language" content="vi" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="copyright" content="{NV_SITE_COPYRIGHT}" />
		<meta name="generator" content="{NV_SITE_NAME}" />
		<title>{NV_SITE_TITLE}</title>
		<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/default/css/admin.css" />
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/{NV_LANG_INTERFACE}.js"></script>
		<script type="text/javascript">
            var nv_siteroot = '{NV_BASE_SITEURL}';
            var nv_lang_interface = '{NV_LANG_INTERFACE}';
            var nv_name_variable = '{NV_NAME_VARIABLE}';
            var nv_fc_variable = '{NV_OP_VARIABLE}';
            var nv_lang_variable = '{NV_LANG_VARIABLE}';
            var nv_module_name = '{MODULE_NAME}';
            var nv_my_ofs = '{NV_SITE_TIMEZONE_OFFSET}';
            var nv_my_abbr = '{NV_CURRENTTIME}';
            var nv_cookie_prefix = '{NV_COOKIE_PREFIX}';
            var nv_area_admin = 1;

		</script>
		<!--[if IE 6]>
		<script src="{NV_BASE_SITEURL}js/fix-png-ie6.js"></script>
		<script type="text/javascript">
		/* EXAMPLE */
		DD_belatedPNG.fix('.logo, img');
		/* string argument can be any CSS selector */
		/* .png_bg example is unnecessary */
		/* change it to what suits you! */
		</script>
		<![endif]-->
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/admin.js"></script>
		<!-- BEGIN: nv_add_my_head -->
		{NV_ADD_MY_HEAD} <!-- END: nv_add_my_head -->
	</head>
	<body>
		<form action="{NV_BASE_SITEURL}index.php" method="post">
			<input type="hidden" name ="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
			<input type="hidden" name ="{NV_OP_VARIABLE}" value="{OP}" />
			<input type="hidden" name ="id" value="{DATA.id}" />
			<input type="hidden" name ="gid" value="{DATA.gid}" />
			<input type="hidden" name ="parentid" value="{DATA.parentid}" />
			<input type="hidden" name ="opanniversary" value="{DATA.opanniversary}" />
			<input name="save" type="hidden" value="1" />
			<table summary="" class="tab1">
				<!-- BEGIN: root -->
				<tbody class="second">
					<tr>
						<td align="right">{LANG.u_relationships}: </td>
						<td><!-- BEGIN: relationships -->
						<input type="radio" name="relationships" value="{RELATIONSHIPS.value}" {RELATIONSHIPS.checked}/>
						{RELATIONSHIPS.title} &nbsp;&nbsp; <!-- END: relationships --></td>
						<td align="right"> {LANG.u_weight}: </td>
						<td>
						<select name="weight">
							<!-- BEGIN: weight -->
							<option value="{WEIGHT.value}" {WEIGHT.selected}> {WEIGHT.title} </option>
							<!-- END: weight -->
						</select></td>
					</tr>
				</tbody>
				<!-- END: root -->
				<tbody>
					<tr>
						<td align="right">{LANG.u_code}: </td>
						<td>
						<input style="width: 100px" name="code" type="text" value="{DATA.code}" maxlength="50" />
						&nbsp;&nbsp;
						({LANG.u_code_if_exit}) </td>
						<td align="right" nowrap="nowrap"> {LANG.u_mother}: </td>
						<td>
						<select name="parentid2">
							<!-- BEGIN: parentid2 -->
							<option value="{PARENTID2.id}" {PARENTID2.selected}> {PARENTID2.full_name} </option>
							<!-- END: parentid2 -->
						</select></td>
					</tr>
				</tbody>
				<tbody class="second">
					<tr>
						<td align="right"><b>{LANG.u_full_name}: </b></td>
						<td>
						<input style="width: 200px" name="full_name" type="text" value="{DATA.full_name}" maxlength="255" />
						<span style="color: #CC0000">(*)</span></td>
						<td align="right">{LANG.u_birthday}: </td>
						<td>
						<input name="birthday_date" id="birthday_date" value="{DATA.birthday_date}" style="width: 80px;" maxlength="10" type="text"/>
						<select name="birthday_hour">
							{DATA.birthday_hour}
						</select>:
						<select name="birthday_min">
							{DATA.birthday_min}
						</select></td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td align="right">{LANG.u_name1}: </td>
						<td>
						<input style="width: 200px" name="name1" type="text" value="{DATA.name1}" maxlength="200" />
						</td>
						<td align="right">{LANG.u_dieday}: </td>
						<td>
						<input name="dieday_date" id="dieday_date" value="{DATA.dieday_date}" style="width: 80px;" maxlength="10" type="text"/>
						<select name="dieday_hour">
							{DATA.dieday_hour}
						</select>:
						<select name="dieday_min">
							{DATA.dieday_min}
						</select></td>
					</tr>
				</tbody>
				<tbody class="second">
					<tr>
						<td align="right">{LANG.u_status}: </td>
						<td><!-- BEGIN: status -->
						<input type="radio" name="status" value="{STATUS.value}" {STATUS.checked}/>
						{STATUS.title} &nbsp;&nbsp; <!-- END: status --></td>
						<td align="right">{LANG.u_gender}: </td>
						<td nowrap="nowrap"><!-- BEGIN: gender --><span id="id_gender_{GENDER.value}">
							<input type="radio" name="gender" value="{GENDER.value}" {GENDER.checked}/>
							{GENDER.title}</span><!-- END: gender --></td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td align="right">{LANG.u_name2}: </td>
						<td>
						<input style="width: 200px" name="name2" type="text" value="{DATA.name2}" maxlength="200" />
						</td>
						<td align="right">{LANG.u_life}: </td>
						<td>
						<select name="life">
							<!-- BEGIN: life -->
							<option value="{LIFE.value}" {LIFE.selected}> {LIFE.title} </option>
							<!-- END: life -->
						</select></td>
					</tr>
				</tbody>
				<tbody class="second">
					<tr>
						<td align="right" id="burial_address">{LANG.burial_address}: </td>
						<td colspan="3">
						<input style="width: 450px" name="burial" type="text" value="{DATA.burial}" maxlength="255" />
						</td>
					</tr>
				</tbody>
				<tbody>
					<tr>
						<td colspan="4">{LANG.u_content}
						<br>
						<br>
						{HTMLBODYTEXT} </td>
					</tr>
				</tbody>
			</table>
			<center>
				<input name="submit1" type="submit" value="{LANG.save}" />
			</center>
		</form>
		<script type="text/javascript">
            //<![CDATA[
            $(document).ready(function()
            {
                $("input[name=relationships]").change(function()
                {
                    var relationships = $("input[name=\'relationships\']:checked").val();
                    if(relationships == "2")
                    {
                        var $radios = $("input:radio[name=\'gender\']");
                        $radios.filter("[value=2]").attr("checked", true);
                        $("#id_gender_0").hide();
                        $("#id_gender_1").hide();
                    }
                    else
                    {
                        $("#id_gender_0").show();
                        $("#id_gender_1").show();
                    }
                });

                $("input[name=status]").change(function()
                {
                    var status = $("input[name=\'status\']:checked").val();
                    if(status == "0")
                    {
                        $("#burial_address").text("{LANG.u_burial}: ");
                    }
                    else
                    {
                        $("#burial_address").text("{LANG.u_address}: ");
                    }
                });

                $("#birthday_date,#dieday_date").datepicker(
                {
                    showOn : "button",
                    dateFormat : "dd/mm/yy",
                    changeMonth : true,
                    changeYear : true,
                    showOtherMonths : true,
                    buttonImage : nv_siteroot + "images/calendar.gif",
                    buttonImageOnly : true
                });
            });
            //]]>
		</script>
		<div id="run_cronjobs" style="visibility:hidden;display:none;">
			<img alt="" title="" src="{NV_BASE_SITEURL}index.php?second=cronjobs&amp;p={NV_GENPASS}" width="1" height="1" />
		</div>
		<!-- BEGIN: nv_if_mudim -->
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/mudim.js"></script>
		<!-- END: nv_if_mudim -->
	</body>
</html>
<!-- END: main -->