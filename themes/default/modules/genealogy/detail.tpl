<!-- BEGIN: main -->
<div id="biagiapha">
    <div class="biagiapha">

    	<div class="gia-pha-vietnam">
    	    <span>Họ {DETAIL.ftitle}</span>
    	    <br>
    	    <span>Gia tộc: {DETAIL.title}</span>
    	</div>
    	<div class="buttom-giapha">
    		<ul class="list-genealogy clearfix">
    			<li class="col-md-5 col-xs-12">
    				<a href="{DETAIL.link_made_up}">Phả ký </a>
    			</li>
    			<li class="col-md-5 col-xs-12">
    				<a href="{DETAIL.link_family_tree}">Phả đồ</a>
    			</li>
    			<li class="col-md-5 col-xs-12">
    				<a href="{DETAIL.link_convention}">Tộc ước</a>
    			</li>
    			<li class="col-md-5 col-xs-12">
    				<a href="{DETAIL.link_collapse}">Hương Hoả</a>
    			</li>
    			<li class="col-md-4 col-xs-12">
    				<a href="{DETAIL.link_anniversary}">Ngày giỗ</a>
    			</li>
				
    		</ul>
			<!-- BEGIN: adminlink -->
			<div aligh="center">
				{ADMINLINK} 
			</div>
				<!-- END: adminlink -->
    	</div>
    	<div class="gia-pha-vn">
    		
    		
    		<span>Người liên hệ: {DETAIL.full_name}</span>
            <br>
            
            <span>Email: {DETAIL.email}</span>
            <br>
            <span>Tổng số : {DETAIL.maxlev} đời, số thành viên trong gia phả {DETAIL.number}</span>
    	</div>
    	<div>

    	</div>
	</div>
	<div id="banners">
           <img src="//www.nguyenvan.vn/themes/default/images/genealogy/bg-genealogy.jpg">
    </div>
</div>

<!-- END: main -->
<!-- BEGIN: no_permission -->
<div class="alert alert-info">
	{NO_PERMISSION}
</div>
<!-- END: no_permission -->

