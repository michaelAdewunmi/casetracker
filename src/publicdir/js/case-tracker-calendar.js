if($==undefined) {
    var $ = jQuery;
}

class Calendar {
    constructor() {
        this.attachEvents();
        this.updateMonthDivToPresent();
        this.updateYearDivToPresent();
        this.loadCalendarUi();
    }

    attachEvents() {
        $("#next--btn").on('click', this.increaseYear.bind(this));
        $("#prev--btn").on('click', this.reduceYear.bind(this));
        $("#prev--mnth").on('click', this.prevMonth.bind(this));
        $("#next--mnth").on('click', this.nextMonth.bind(this));
    }

    increaseYear() {
        const presentYear = $("#ctyear").attr("data-ctyear");
        const increaseYearByOne = Number(presentYear)+1;
        $("#ctyear").attr("data-ctyear", increaseYearByOne).html(increaseYearByOne);
        this.loadCalendarUi();
    }

    reduceYear() {
        const presentYear = $("#ctyear").attr("data-ctyear");
        const reduceYearByOne = Number(presentYear)-1;
        $("#ctyear").attr("data-ctyear", reduceYearByOne).html(reduceYearByOne);
        this.loadCalendarUi();
    }

    prevMonth() {
        const monthsArr = this.monthAndVal();
        if($("#ctmonth").attr("data-ctmonth")=="1") {
            $("#ctmonth").attr("data-ctmonth", 12).html(monthsArr[11]);
            this.reduceYear();
        } else {
            const presentMonth = $("#ctmonth").attr("data-ctmonth");
            const newMonth = Number(presentMonth)-1
            $("#ctmonth").attr("data-ctmonth", newMonth).html(monthsArr[newMonth-1]);
            this.loadCalendarUi();
        }
    }

    nextMonth() {
        const monthsArr = this.monthAndVal();
        if($("#ctmonth").attr("data-ctmonth")=="12") {
            $("#ctmonth").attr("data-ctmonth", 1).html(monthsArr[0]);
            this.increaseYear();
        } else {
            const presentMonth = $("#ctmonth").attr("data-ctmonth");
            const newMonth = Number(presentMonth)+1
            $("#ctmonth").attr("data-ctmonth", newMonth).html(monthsArr[newMonth-1]);
            this.loadCalendarUi();
        }
    }

    monthAndVal() {
        return [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ]
    }


    idIsHovered(elem){
        return $(elem + ":hover").length > 0;
    }

    isJSON(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    }

    getCasesInTheMonth() {
        const yr = $("#ctyear").attr("data-ctyear");
        const month = $("#ctmonth").attr("data-ctmonth");
        const monthDays =  this.numberOfDaysInMonth(String(month), yr);
        const monthObjectForCaseCall = {}
        for (let i=0; i<monthDays; i++) {
            monthObjectForCaseCall[`day${this.addLeadingZeros(i+1)}${this.addLeadingZeros(month)}${yr}`] = this.renderFullDate(yr, month, i+1)
        }
        this.sendDataForCaseAjaxCall(monthObjectForCaseCall);
    }

    revealToolTip(elem) {
        $(".pop-over").removeClass("revealjbct");
        $(elem.lastElementChild).show();
        setTimeout(function() { $(elem.lastElementChild).addClass("revealjbct"); }, 50);
    }

    hideToolTip(elem) {
        if($(elem).attr("class")=="case-wrapper") {
            if($(".pop-over:hover").length<1) {
                $(elem.lastElementChild).removeClass("revealjbct");
                $(elem.lastElementChild).hide();
            }
        } else {
            $(elem).removeClass("revealjbct");
            $(elem).hide();
        }

    }

    renderCaseOutput(arr) {

        if(arr.length<1) {
            return `<em class="no-case">There is no Assigned Case on this day</em>`;
        } else {
            const allVal = arr.map(val=>{
                const { all_meta, post_title, post_content, ID, guid } = val;
                return `
                    <div class="case-wrapper" onmouseover="cal.revealToolTip(this)" onmouseout="cal.hideToolTip(this)">
                        <div id="${ID}" class="case-single">
                            <span class="case_meta main">Suit No: ${post_title}</span>
                        </div>
                        <div class="pop-over" onmouseout="cal.hideToolTip(this)">
                            <span class="case_meta preview">
                            <a href="${guid}">${post_title}</a></span>
                            <span class="case_meta preview">${post_content}</span>
                            <span class="case_meta preview">Laywer Assigned: ${all_meta.lawyer_assigned}</span>
                            <span class="case_meta preview">Judge Assigned: ${all_meta.judge_assigned}</span>
                        </div>
                    </div>
                `
            }).join("")
            return `<div id="case-all">${allVal}</div>`;
        }
    }

    renderCasesIntoCal() {
        const jsonCases = localStorage.getItem("cases");
        const parsedCases = jsonCases!=undefined && jsonCases!=null && jsonCases!=""
            && this.isJSON(jsonCases) ? JSON.parse(jsonCases): "";

        for( let key in parsedCases ) {
            const divWithSameKey = $(`#${key}`);
            const output = this.renderCaseOutput(parsedCases[key]);
            $(`#${key} em:last-child`).remove();
            $(`#${key} #case-all:last-child`).remove();
            divWithSameKey.append(output);
        }
        localStorage.removeItem("cases");
    }

    sendDataForCaseAjaxCall(jbct_cal_data) {
        localStorage.removeItem("cases");
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', jbct_data.userRestApiNonce)
            },
            url: `${jbct_data.baseUrl}/ajaxcasearchives`,
            type: 'POST', data: {jbct_data: jbct_cal_data},
            success: (res) => {
                const jsonRes = res!=undefined && res!=null && res!="" ? JSON.stringify(res.cases) : "";
                localStorage.setItem("cases", jsonRes);
                this.renderCasesIntoCal();
            },
            error: (err) => {
                console.log(err);
            }
        })
    }

    updateMonthDivToPresent() {
        const presentMonth = new Date().getMonth();
        const monthsArr = this.monthAndVal();
        $("#ctmonth").attr("data-ctmonth", presentMonth+1).html(monthsArr[presentMonth])
    }

    updateYearDivToPresent() {
        const presentYear = new Date().getFullYear();
        $("#ctmonth").attr("data-ctyear", presentYear).html(presentYear)
    }

    loadCalendarUi() {
        const selectedMonth = $("#ctmonth").attr("data-ctmonth");
        const selectedYear = $("#ctyear").attr("data-ctyear");
        const dayOfTheWeek = String(new Date(`${selectedMonth}-01-${selectedYear}`)).slice(0,3);

        this.RenderDaysOfTheMonth(dayOfTheWeek, selectedMonth, selectedYear);
        this.getCasesInTheMonth();
    }

    RenderDaysOfTheMonth(startDay, month, year) {
        $("#jbct--date")
        $("#jbct--date").html("");
        this.PrependOrAppendFillerDatesForUnfilledDays(startDay, month, year);
        this.appendDaysInTheMonthToCal(month, year, $("#jbct--date"));
        this.appendDatesIntoCalendarBlankWeekDays(month, year)
    }

    appendDatesIntoCalendarBlankWeekDays(month, year) {
        const NoOfDays = this.numberOfDaysInMonth(month, year);
        const monthEndingDay = String(new Date(`${month}-${NoOfDays}-${year}`)).slice(0,3);
        this.PrependOrAppendFillerDatesForUnfilledDays(monthEndingDay, month, year, true);
        this.addrightBorderToLastDivInDateRow();
    }

    addrightBorderToLastDivInDateRow() {
        const dayDivs = $(".day-num");
        [...dayDivs].forEach((day,i) =>{
            const j=i+1;
            if(j%7==0) {
                $(day).addClass("border-inject");
            }
        })
    }

    numberOfDaysInMonth(month, year ) {
        const ThirtyDaysMonth=["4", "6", "9", "11"]
        if(month==2 && (year%4==0 || (year%100==0 && year%400==0))) {
            return 29;
        } else if (month==2 && (year%4!=0 || year%400!=0)) {
            return 28;
        } else if (ThirtyDaysMonth.indexOf(month)>-1) {
            return 30;
        } else {
            return 31;
        }
    }

    addLeadingZeros(num) {
        return num = num < 10 ? "0"+String(num) : num;
    }

    renderFullDate(year, month, day) {
        return `${year}-${this.addLeadingZeros(month)}-${this.addLeadingZeros(day)}`
    }

    appendDaysInTheMonthToCal(month, year, elem) {
        const NoOfDays = this.numberOfDaysInMonth(month, year);
        for(let i=0; i<NoOfDays; i++) {
            const fullDate = this.renderFullDate(year, month, i+1)
            elem.append(
                `<div id="day${this.addLeadingZeros(i+1)}${this.addLeadingZeros(month)}${year}"
                    data-fulldate="${fullDate}" class="day-num"><span class="date-jbct">${i+1}</span></div>`);
        }
    }

    PrependOrAppendFillerDatesForUnfilledDays(day, month, year, forNextMonth=false) {
        const ThirtyDaysMonth=[4, 6, 9, 11];
        const theMonth = !forNextMonth ? Number(month)-1 : Number(month);
        if(day=="Sun" && theMonth==2 && (year%4==0 || (year%100==0 && year%400==0))) {
            forNextMonth ? this.appendValUsingLoop(6, $("#jbct--date")) : '';
        } else if(day=="Sun" && theMonth==2 && (year%4!=0 || year%400!=0)) {
            forNextMonth ? this.appendValUsingLoop(6, $("#jbct--date")) : '';
        } else if(day=="Sun" && ThirtyDaysMonth.indexOf(theMonth)>-1) {
            forNextMonth ? this.appendValUsingLoop(6, $("#jbct--date")) : '';
        } else if(day=="Sun") {
            forNextMonth ? this.appendValUsingLoop(6, $("#jbct--date")) : '';
        }

        if(day=="Mon" && theMonth==2 && (year%4==0 || (year%100==0 && year%400==0))) {
            !forNextMonth ? $("#jbct--date").prepend(`<div class="day-num placeholder">29</div>`)
                : this.appendValUsingLoop(5, $("#jbct--date"));
        } else if(day=="Mon" && theMonth==2 && (year%4!=0 || year%400!=0)) {
            !forNextMonth ? $("#jbct--date").prepend(`<div class="day-num placeholder">28</div>`)
                : this.appendValUsingLoop(5, $("#jbct--date"));
        } else if(day=="Mon" && ThirtyDaysMonth.indexOf(theMonth)>-1) {
            !forNextMonth ? $("#jbct--date").prepend(`<div class="day-num placeholder">30</div>`)
                : this.appendValUsingLoop(5, $("#jbct--date"));
        } else if(day=="Mon") {
            !forNextMonth ? $("#jbct--date").prepend(`<div class="day-num placeholder">31</div>`)
                : this.appendValUsingLoop(5, $("#jbct--date"));
        }

        if(day=="Tue" && theMonth==2 && (year%4==0 || (year%100==0 && year%400==0))) {
            !forNextMonth ? this.prependValUsingLoop(2, $("#jbct--date"), 29) : this.appendValUsingLoop(4, $("#jbct--date"));
        } else if(day=="Tue" && theMonth==2 && (year%4!=0 || year%400!=0)) {
            !forNextMonth ? this.prependValUsingLoop(2, $("#jbct--date"), 28) : this.appendValUsingLoop(4, $("#jbct--date"));
        } else if(day=="Tue" && ThirtyDaysMonth.indexOf(theMonth)>-1) {
            !forNextMonth ? this.prependValUsingLoop(2, $("#jbct--date"), 30) : this.appendValUsingLoop(4, $("#jbct--date"));
        } else if(day=="Tue") {
            !forNextMonth ? this.prependValUsingLoop(2, $("#jbct--date"), 31) : this.appendValUsingLoop(4, $("#jbct--date"));
        }

        if(day=="Wed" && theMonth==2 && (year%4==0 || (year%100==0 && year%400==0))) {
            !forNextMonth ? this.prependValUsingLoop(3, $("#jbct--date"), 29) : this.appendValUsingLoop(3, $("#jbct--date"));
        } else if(day=="Wed" && theMonth==2 && (year%4!=0 || year%400!=0)) {
            !forNextMonth ? this.prependValUsingLoop(3, $("#jbct--date"), 28) : this.appendValUsingLoop(3, $("#jbct--date"));
        } else if(day=="Wed" && ThirtyDaysMonth.indexOf(theMonth)>-1) {
            !forNextMonth ? this.prependValUsingLoop(3, $("#jbct--date"), 30) : this.appendValUsingLoop(3, $("#jbct--date"));
        } else if(day=="Wed") {
            !forNextMonth ? this.prependValUsingLoop(3, $("#jbct--date"), 31) : this.appendValUsingLoop(3, $("#jbct--date"));
        }

        if(day=="Thu" && theMonth==2 && (year%4==0 || (year%100==0 && year%400==0))) {
            !forNextMonth ? this.prependValUsingLoop(4, $("#jbct--date"), 29) : this.appendValUsingLoop(2, $("#jbct--date"));
        } else if(day=="Thu" && theMonth==2 && (year%4!=0 || year%400!=0)) {
            !forNextMonth ? this.prependValUsingLoop(4, $("#jbct--date"), 28) : this.appendValUsingLoop(2, $("#jbct--date"));
        } else if(day=="Thu" && ThirtyDaysMonth.indexOf(theMonth)>-1) {
            !forNextMonth ? this.prependValUsingLoop(4, $("#jbct--date"), 30) : this.appendValUsingLoop(2, $("#jbct--date"));
        } else if(day=="Thu") {
            !forNextMonth ? this.prependValUsingLoop(4, $("#jbct--date"), 31) : this.appendValUsingLoop(2, $("#jbct--date"));
        }

        if(day=="Fri" && theMonth==2 && (year%4==0 || (year%100==0 && year%400==0))) {
            !forNextMonth ? this.prependValUsingLoop(5, $("#jbct--date"), 29) : this.appendValUsingLoop(1, $("#jbct--date"));
        } else if(day=="Fri" && theMonth==2 && (year%4!=0 || year%400!=0)) {
            !forNextMonth ? this.prependValUsingLoop(5, $("#jbct--date"), 28) : this.appendValUsingLoop(1, $("#jbct--date"));
        } else if(day=="Fri" && ThirtyDaysMonth.indexOf(theMonth)>-1) {
            !forNextMonth ? this.prependValUsingLoop(5, $("#jbct--date"), 30) : this.appendValUsingLoop(1, $("#jbct--date"));
        } else if(day=="Fri") {
            !forNextMonth ? this.prependValUsingLoop(5, $("#jbct--date"), 31) : this.appendValUsingLoop(1, $("#jbct--date"));
        }

        if(day=="Sat" && theMonth==2 && (year%4==0 || (year%100==0 && year%400==0))) {
            !forNextMonth ? this.prependValUsingLoop(6, $("#jbct--date"), 29) : '';
        } else if(day=="Sat" && theMonth==2 && (year%4!=0 || year%400!=0)) {
            !forNextMonth ? this.prependValUsingLoop(6, $("#jbct--date"), 28) : '';
        } else if(day=="Sat" && ThirtyDaysMonth.indexOf(theMonth)>-1) {
            !forNextMonth ? this.prependValUsingLoop(6, $("#jbct--date"), 30) : '';
        } else if(day=="Sat") {
            !forNextMonth ? this.prependValUsingLoop(6, $("#jbct--date"), 31) : '';
        }

    }

    prependValUsingLoop(loopTimes, elem, startVal) {
        for(let i=0; i<loopTimes; i++) {
            elem.prepend(`<div class="day-num placeholder"><span class="date-jbct ph-date">${startVal}</span></div>`);
            startVal=Number(startVal)-1;
        }
    }

    appendValUsingLoop(loopTimes, elem) {
        for(let i=0; i<loopTimes; i++) {
            elem.append(`<div class="day-num placeholder"><span class="date-jbct ph-date">${i+1}</span></div>`);
        }
    }
}

const cal = new Calendar;