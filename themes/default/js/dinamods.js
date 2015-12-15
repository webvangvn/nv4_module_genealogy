//** Tab Content script v2.0- © Dynamic Drive DHTML code library (http://www.dynamicdrive.com)

function dinamods(tabinterfaceid){this.tabinterfaceid=tabinterfaceid;this.tabs=document.getElementById(tabinterfaceid).getElementsByTagName("a");this.enabletabpersistence=true;this.hottabspositions=[];this.currentTabIndex=0; this.subcontentids=[];this.revcontentids=[];this.selectedClassTarget="link";}
dinamods.getCookie=function(Name){ 
	var re=new RegExp(Name+"=[^;]+", "i");
	if (document.cookie.match(re))
		return document.cookie.match(re)[0].split("=")[1];
	return "";
}
dinamods.setCookie=function(name, value){
	document.cookie = name+"="+value+";path=/";
}
dinamods.prototype={
	cycleit:function(dir, autorun){
		if (dir=="next"){
			var currentTabIndex=(this.currentTabIndex<this.hottabspositions.length-1)? this.currentTabIndex+1 : 0;
		}
		else if (dir=="prev"){
			var currentTabIndex=(this.currentTabIndex>0)? this.currentTabIndex-1 : this.hottabspositions.length-1;
		}
		if (typeof autorun=="undefined")
			this.cancelautorun();
		this.expandtab(this.tabs[this.hottabspositions[currentTabIndex]]);
	},
	setpersist:function(bool){
			this.enabletabpersistence=bool;
	},
	setselectedClassTarget:function(objstr){
		this.selectedClassTarget=objstr || "link";
	},
	getselectedClassTarget:function(tabref){ 
		return (this.selectedClassTarget==("linkparent".toLowerCase()))? tabref.parentNode : tabref;
	},
	expandtab:function(tabref){
		var subcontentid=tabref.getAttribute("rel");
		var associatedrevids=(tabref.getAttribute("rev"))? ","+tabref.getAttribute("rev").replace(/\s+/, "")+"," : "";
		this.expandsubcontent(subcontentid);
		this.expandrevcontent(associatedrevids);
		for (var i=0; i<this.tabs.length; i++){
			this.getselectedClassTarget(this.tabs[i]).className=(this.tabs[i].getAttribute("rel")==subcontentid)? "dm_selected" : "";
		}
		if (this.enabletabpersistence)
			dinamods.setCookie(this.tabinterfaceid, tabref.tabposition);
		this.setcurrenttabindex(tabref.tabposition);

		// Google Analytics integration
		if (typeof this.autoruntimer!="undefined" && this.autoruntimer==false  && typeof(pageTracker) != 'undefined' && typeof(pageTracker._trackPageview) != 'undefined' ) {
   			pageTracker._trackPageview( tabref.getAttribute("href"));
		}
	},
	expandsubcontent:function(subcontentid){
		for (var i=0; i<this.subcontentids.length; i++){
			var subcontent=document.getElementById(this.subcontentids[i]); 
			subcontent.style.display=(subcontent.id==subcontentid)? "block" : "none";
		}
	},
	expandrevcontent:function(associatedrevids){
		var allrevids=this.revcontentids;
		for (var i=0; i<allrevids.length; i++){
			document.getElementById(allrevids[i]).style.display=(associatedrevids.indexOf(","+allrevids[i]+",")!=-1)? "block" : "none";
		}
	},
	setcurrenttabindex:function(tabposition){
		for (var i=0; i<this.hottabspositions.length; i++){
			if (tabposition==this.hottabspositions[i]){
				this.currentTabIndex=i;
				break;
			}
		}
	},
	autorun:function(){
		this.cycleit('next', true);
	},
	cancelautorun:function(){
		if (typeof this.autoruntimer!="undefined") {
			clearInterval(this.autoruntimer);
			this.autoruntimer=false;
		}
	},
	init:function(automodeperiod,change){
		var persistedtab=dinamods.getCookie(this.tabinterfaceid);
		var persisterror=true;
		this.automodeperiod=automodeperiod || 0;
		for (var i=0; i<this.tabs.length; i++){
			this.tabs[i].tabposition=i;
			if (this.tabs[i].getAttribute("rel")){
				var tabinstance=this;
				this.hottabspositions[this.hottabspositions.length]=i;
				this.subcontentids[this.subcontentids.length]=this.tabs[i].getAttribute("rel");
				this.tabs[i].onclick=function(){
					 tabinstance.expandtab(this);
					 tabinstance.cancelautorun();
					 return false;
				}
				if (change==1){
					this.tabs[i].onmouseover=this.tabs[i].onclick
				}
				if (this.tabs[i].getAttribute("rev")){
					this.revcontentids=this.revcontentids.concat(this.tabs[i].getAttribute("rev").split(/\s*,\s*/));
				}
				if (this.enabletabpersistence && parseInt(persistedtab)==i || !this.enabletabpersistence && this.getselectedClassTarget(this.tabs[i]).className=="dm_selected"){
					this.expandtab(this.tabs[i]);
					persisterror=false;
				}
			}
		}
		if (persisterror)
			this.expandtab(this.tabs[this.hottabspositions[0]]);
		if (parseInt(this.automodeperiod)>500 && this.hottabspositions.length>1){
			this.autoruntimer=setInterval(function(){tabinstance.autorun()}, this.automodeperiod);
		}

	}
}