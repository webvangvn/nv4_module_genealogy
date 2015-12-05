var ABOUT = "\u00C2m l\u1ECBch Vi\u1EC7t Nam - Version 1.0"+"\n\u00A9 2004 H\u1ED3 Ng\u1ECDc \u0110\u1EE9c [http://come.to/duc]";

var TK21 = new Array(
	0x46c960, 0x2ed954, 0x54d4a0, 0x3eda50, 0x2a7552, 0x4e56a0, 0x38a7a7, 0x5ea5d0, 0x4a92b0, 0x32aab5,
	0x58a950, 0x42b4a0, 0x2cbaa4, 0x50ad50, 0x3c55d9, 0x624ba0, 0x4ca5b0, 0x375176, 0x5c5270, 0x466930,
	0x307934, 0x546aa0, 0x3ead50, 0x2a5b52, 0x504b60, 0x38a6e6, 0x5ea4e0, 0x48d260, 0x32ea65, 0x56d520,
	0x40daa0, 0x2d56a3, 0x5256d0, 0x3c4afb, 0x6249d0, 0x4ca4d0, 0x37d0b6, 0x5ab250, 0x44b520, 0x2edd25,
	0x54b5a0, 0x3e55d0, 0x2a55b2, 0x5049b0, 0x3aa577, 0x5ea4b0, 0x48aa50, 0x33b255, 0x586d20, 0x40ad60,
	0x2d4b63, 0x525370, 0x3e49e8, 0x60c970, 0x4c54b0, 0x3768a6, 0x5ada50, 0x445aa0, 0x2fa6a4, 0x54aad0,
	0x4052e0, 0x28d2e3, 0x4ec950, 0x38d557, 0x5ed4a0, 0x46d950, 0x325d55, 0x5856a0, 0x42a6d0, 0x2c55d4,
	0x5252b0, 0x3ca9b8, 0x62a930, 0x4ab490, 0x34b6a6, 0x5aad50, 0x4655a0, 0x2eab64, 0x54a570, 0x4052b0,
	0x2ab173, 0x4e6930, 0x386b37, 0x5e6aa0, 0x48ad50, 0x332ad5, 0x582b60, 0x42a570, 0x2e52e4, 0x50d160,
	0x3ae958, 0x60d520, 0x4ada90, 0x355aa6, 0x5a56d0, 0x462ae0, 0x30a9d4, 0x54a2d0, 0x3ed150, 0x28e952
); /* Years 2000-2099 */

var CAN = new Array("Gi\341p", "\u1EA4t", "B\355nh", "\u0110inh", "M\u1EADu", "K\u1EF7", "Canh", "T\342n", "Nh\342m", "Qu\375");
var CHI = new Array("T\375", "S\u1EEDu", "D\u1EA7n", "M\343o", "Th\354n", "T\u1EF5", "Ng\u1ECD", "M\371i", "Th\342n", "D\u1EADu", "Tu\u1EA5t", "H\u1EE3i");
var TUAN = new Array("Ch\u1EE7 nh\u1EADt", "Th\u1EE9 hai", "Th\u1EE9 ba", "Th\u1EE9 t\u01B0", "Th\u1EE9 n\u0103m", "Th\u1EE9 s\341u", "Th\u1EE9 b\u1EA3y");
var THANG = new Array("Gi\u00EAng", "Hai", "Ba", "T\u01B0", "N\u0103m", "S\u00E1u", "B\u1EA3y", "T\u00E1m", "Ch\u00EDn", "M\u01B0\u1EDDi", "M\u1ED9t", "Ch\u1EA1p");
var GIO_HD = new Array("110100101100", "001101001011", "110011010010", "101100110100", "001011001101", "010010110011");
var TIETKHI = new Array("Xu\u00E2n ph\u00E2n", "Thanh minh", "C\u1ED1c v\u0169", "L\u1EADp h\u1EA1", "Ti\u1EC3u m\u00E3n", "Mang ch\u1EE7ng",
	"H\u1EA1 ch\u00ED", "Ti\u1EC3u th\u1EED", "\u0110\u1EA1i th\u1EED", "L\u1EADp thu", "X\u1EED th\u1EED", "B\u1EA1ch l\u1ED9",
	"Thu ph\u00E2n", "H\u00E0n l\u1ED9", "S\u01B0\u01A1ng gi\u00E1ng", "L\u1EADp \u0111\u00F4ng", "Ti\u1EC3u tuy\u1EBFt", "\u0110\u1EA1i tuy\u1EBFt",
	"\u0110\u00F4ng ch\u00ED", "Ti\u1EC3u h\u00E0n", "\u0110\u1EA1i h\u00E0n", "L\u1EADp xu\u00E2n", "V\u0169 Th\u1EE7y", "Kinh tr\u1EADp"
);

/* Create lunar date object, stores (lunar) date, month, year, leap month indicator, and Julian date number */
function LunarDate(dd, mm, yy, leap, jd, length) {
	this.day = dd;
	this.month = mm;
	this.year = yy;
	this.leap = leap;
	this.jd = jd;
	this.length = length;
}

var PI = Math.PI;

/* Discard the fractional part of a number, e.g., INT(3.2) = 3 */
function INT(d) {
	return Math.floor(d);
}

function jdn(dd, mm, yy) {
	var a = INT((14 - mm) / 12);
	var y = yy+4800-a;
	var m = mm+12*a-3;
	var jd = dd + INT((153*m+2)/5) + 365*y + INT(y/4) - INT(y/100) + INT(y/400) - 32045;
	return jd;
}

function decodeLunarYear(yy, k) {
	var monthLengths, regularMonths, offsetOfTet, leapMonth, leapMonthLength, solarNY, currentJD, j, mm;
	var ly = new Array();
	monthLengths = new Array(29, 30);
	regularMonths = new Array(12);
	offsetOfTet = k >> 17;
	leapMonth = k & 0xf;
	leapMonthLength = monthLengths[k >> 16 & 0x1];
	solarNY = jdn(1, 1, yy);
	currentJD = solarNY+offsetOfTet;
	j = k >> 4;
	for(i = 0; i < 12; i++) {
		regularMonths[12 - i - 1] = monthLengths[j & 0x1];
		j >>= 1;
	}
	if (leapMonth == 0) {
		for(mm = 1; mm <= 12; mm++) {
			ly.push(new LunarDate(1, mm, yy, 0, currentJD, regularMonths[mm-1]));
			currentJD += regularMonths[mm-1];
		}
	} else {
		for(mm = 1; mm <= leapMonth; mm++) {
			ly.push(new LunarDate(1, mm, yy, 0, currentJD, regularMonths[mm-1]));
			currentJD += regularMonths[mm-1];
		}
		ly.push(new LunarDate(1, leapMonth, yy, 1, currentJD, leapMonthLength));
		currentJD += leapMonthLength;
		for(mm = leapMonth+1; mm <= 12; mm++) {
			ly.push(new LunarDate(1, mm, yy, 0, currentJD, regularMonths[mm-1]));
			currentJD += regularMonths[mm-1];
		}
	}
	return ly;
}

function getYearInfo(yyyy) {
	var yearCode;
	if (yyyy < 1900) {
		yearCode = TK19[yyyy - 1800];
	} else if (yyyy < 2000) {
		yearCode = TK20[yyyy - 1900];
	} else if (yyyy < 2100) {
		yearCode = TK21[yyyy - 2000];
	} else {
		yearCode = TK22[yyyy - 2100];
	}
	return decodeLunarYear(yyyy, yearCode);
}

var FIRST_DAY = jdn(25, 1, 1800); // Tet am lich 1800
var LAST_DAY = jdn(31, 12, 2199);

function findLunarDate(jd, ly) {
	if (jd > LAST_DAY || jd < FIRST_DAY || ly[0].jd > jd) {
		return new LunarDate(0, 0, 0, 0, jd, 0);
	}
	var i = ly.length-1;
	while (jd < ly[i].jd) {
		i--;
	}
	var off = jd - ly[i].jd;
	ret = new LunarDate(ly[i].day+off, ly[i].month, ly[i].year, ly[i].leap, jd, ly[i].length);
	return ret;
}

function getLunarDate(dd, mm, yyyy) {
	var ly, jd;
	ly = getYearInfo(yyyy);
	jd = jdn(dd, mm, yyyy);
	if (jd < ly[0].jd) {
		ly = getYearInfo(yyyy - 1);
	}
	return findLunarDate(jd, ly);
}

/* Compute the longitude of the sun at any time.
 * Parameter: floating number jdn, the number of days since 1/1/4713 BC noon
 * Algorithm from: "Astronomical Algorithms" by Jean Meeus, 1998
 */
function SunLongitude(jdn) {
	var T, T2, dr, M, L0, DL, lambda, theta, omega;
	T = (jdn - 2451545.0 ) / 36525; // Time in Julian centuries from 2000-01-01 12:00:00 GMT
	T2 = T*T;
	dr = PI/180; // degree to radian
	M = 357.52910 + 35999.05030*T - 0.0001559*T2 - 0.00000048*T*T2; // mean anomaly, degree
	L0 = 280.46645 + 36000.76983*T + 0.0003032*T2; // mean longitude, degree
	DL = (1.914600 - 0.004817*T - 0.000014*T2)*Math.sin(dr*M);
	DL = DL + (0.019993 - 0.000101*T)*Math.sin(dr*2*M) + 0.000290*Math.sin(dr*3*M);
    theta = L0 + DL; // true longitude, degree
    // obtain apparent longitude by correcting for nutation and aberration
    omega = 125.04 - 1934.136 * T;
    lambda = theta - 0.00569 - 0.00478 * Math.sin(omega * dr);
    // Convert to radians
    lambda = lambda*dr;
	lambda = lambda - PI*2*(INT(lambda/(PI*2))); // Normalize to (0, 2*PI)
    return lambda;
}

/* Compute the sun segment at start (00:00) of the day with the given integral Julian day number.
 * The time zone if the time difference between local time and UTC: 7.0 for UTC+7:00.
 * The function returns a number between 0 and 23.
 * From the day after March equinox and the 1st major term after March equinox, 0 is returned.
 * After that, return 1, 2, 3 ...
 */
function getSolarTerm(dayNumber, timeZone) {
	return INT(SunLongitude(dayNumber - 0.5 - timeZone/24.0) / PI * 12);
}

function getYearCanChi(year) {
	return CAN[(year+6) % 10] + " " + CHI[(year+8) % 12];
}

/*
 * Can cua gio Chinh Ty (00:00) cua ngay voi JDN nay
 */
function getCanHour0(jdn) {
	return CAN[(jdn-1)*2 % 10];
}

function getGioHoangDao(jd) {
	var chiOfDay = (jd+1) % 12;
	var gioHD = GIO_HD[chiOfDay % 6]; // same values for Ty' (1) and Ngo. (6), for Suu and Mui etc.
	var ret = "";
	var count = 0;
	for (var i = 0; i < 12; i++) {
		if (gioHD.charAt(i) == '1') {
			ret += CHI[i];
			ret += ' ('+(i*2+23)%24+'-'+(i*2+1)%24+')';
			if (count++ < 5) ret += ', ';
			if (count == 3) ret += '\n';
		}
	}
	return ret;
}

function YearlyEvent(dd, mm, info) {
	this.day = dd;
	this.month = mm;
	this.info = info;
}

var YEARLY_EVENTS = new Array(
  new YearlyEvent(0,0,'')
);

function findEvents(dd, mm) {
	var ret = new Array();
	for (var i = 0; i < YEARLY_EVENTS.length; i++) {
		evt = YEARLY_EVENTS[i];
		if (evt.day == dd && evt.month == mm) {
			ret.push(evt);
		}
	}
	return ret;
}

function getDayInfo(dd, mm) {
	var events = findEvents(dd, mm);
	var ret = '';
	for (var i = 0; i < events.length; i++) {
		ret += events[i].info+' ';
	}
	ret += '&nbsp;';
	//ret = 'Gi\u1ED7 T\u1ED5 H\u00F9ng V\u01B0\u01A1ng (10/3 \u00C2L)';
	return ret;
}

function alertInfo(jd) {
	//var hoangdao = 'Gi\u1EDD ho\u00E0ng \u0111\u1EA1o: '+getGioHoangDao(jd);
	//alert(hoangdao);
}



function printToday() {
	var today = new Date();
	var currentLunarDate = getLunarDate(today.getDate(), today.getMonth()+1, today.getFullYear());
	var currentDate = today.getDate();
	var currentMonth = today.getMonth()+1;
	var currentYear = today.getFullYear();
	return printDate(currentLunarDate, currentDate, currentMonth, currentYear);
}

function getCurrentTime() {
	var today = new Date();
	var Std = today.getHours();
	var Min = today.getMinutes();
	var s1  = ((Std < 10) ? "0" + Std : Std);
	var s2  = ((Min < 10) ? "0" + Min : Min);
	return s1 + ":" + s2;
}

function updateDateTime() {
	var today = new Date();
	var currentDate = today.getDate();
	var currentMonth = today.getMonth()+1;
	var currentYear = today.getFullYear();
	var lunarDate = getLunarDate(currentDate, currentMonth, currentYear);
	var yy = lunarDate.year;
	var mm = lunarDate.month;
	var dd = lunarDate.day;
	var jd = lunarDate.jd;
	var Std = today.getHours();
	var Min = today.getMinutes();
	var Sec = today.getSeconds();
	var s1  = ((Std < 10) ? "0" + Std : Std);
	var s2  = ((Min < 10) ? "0" + Min : Min);
	var s3  = ((Sec < 10) ? "0" + Sec : Sec);
	var now = s1 + ":" + s2 + ":" + s3;
	var chiGio = (Std >= 23) ? 0 : INT((Std + 1) / 2);
	var canGio = ((jd-1)*2 + INT((Std + 1) / 2)) % 10;
	var dayOfWeek = TUAN[(jd + 1) % 7];
	var nhuan = (lunarDate.leap == 1) ? ' nhu\u1EADn' : '';
	var thangAm = 'Th\u00E1ng '+THANG[mm-1]+nhuan+(lunarDate.length == 30 ? ' (\u0110)' : ' (T)');
	var thang = CAN[(yy*12+mm+3) % 10] + " " + CHI[(mm+1)%12];
	var ngay = CAN[(jd + 9) % 10] + " " + CHI[(jd+1)%12];
	var info = getDayInfo(dd, mm);

  //document.getElementById("gio").innerHTML = now;
  document.getElementById("thu").innerHTML = dayOfWeek;
  document.getElementById("ngayd").innerHTML = currentDate;
  document.getElementById("thangd").innerHTML = 'Th\u00E1ng '+ currentMonth;
  document.getElementById("namd").innerHTML = currentYear;
//  document.getElementById("ngayduong").innerHTML = currentDate+'/'+currentMonth+'/'+currentYear;
  document.getElementById("dayinfo").innerHTML = info;
	document.getElementById("thangam").innerHTML = thangAm;
	document.getElementById("ngayam").innerHTML = dd;
	document.getElementById("namam").innerHTML = 'N\u0103m '+getYearCanChi(yy);
	document.getElementById("canchithang").innerHTML = 'Th\u00E1ng '+thang;
	document.getElementById("canchingay").innerHTML = 'Ng\u00E0y '+ngay;
	document.getElementById("canchigio").innerHTML = 'Gi\u1EDD '+CAN[canGio]+' '+CHI[chiGio];
	document.getElementById("tietkhi").innerHTML = 'Ti\u1EBFt '+TIETKHI[getSolarTerm(jd+1, 7.0)];
}


function updateTime() {
  var now = getCurrentTime();
  document.getElementById("gio").innerHTML = now;
}

function showDateTime() {
  updateDateTime();
	window.setInterval("updateDateTime()",2000);
}

