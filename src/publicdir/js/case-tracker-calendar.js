if($==undefined) {
    var $ = jQuery;
}

class Calendar {
    constructor() {
        this.attachEvents();
        this.updateMonthDivToPresent();
        this.updateYearDivToPresent();
        this.loadCalendarUI();
    }

    attachEvents() {
        console.log(this);
        $("#next--btn").on('click', this.increaseYear.bind(this));
        $("#prev--btn").on('click', this.reduceYear.bind(this));
        $("#prev--mnth").on('click', this.prevMonth.bind(this));
        $("#next--mnth").on('click', this.nextMonth.bind(this));
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

    numberOfDaysInMonth(month, year ) {
        const ThirtyDaysMonth=["4", "6", "9", "11"]
        if(month==2 && (year%4==0 || (year%100==0 && year%400==0))) {
            return 29;
        } else if (month==2 && (year%4!=0 || year%400!=0)) {
            return 28;
        } else if (ThirtyDaysMonth.indexOf(String(month))>-1) {
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

    increaseYear() {
        const presentYear = $("#ct-year").attr("data-ct-year");
        console.log(presentYear);
        const increaseYearByOne = Number(presentYear)+1;
        $("#ct-year").attr("data-ct-year", increaseYearByOne).html(increaseYearByOne);
        this.loadCalendarUI();
    }

    reduceYear() {
        console.log("HERE WE ARE!!!");
        const presentYear = $("#ct-year").attr("data-ct-year");
        const reduceYearByOne = Number(presentYear)-1;
        $("#ct-year").attr("data-ct-year", reduceYearByOne).html(reduceYearByOne);
        this.loadCalendarUI();
    }

    prevMonth() {
        const monthsArr = this.monthAndVal();
        if($("#ct-month").attr("data-ct-month")=="1") {
            $("#ct-month").attr("data-ct-month", 12).html(monthsArr[11]);
            this.reduceYear();
        } else {
            const presentMonth = $("#ct-month").attr("data-ct-month");
            const newMonth = Number(presentMonth)-1
            $("#ct-month").attr("data-ct-month", newMonth).html(monthsArr[newMonth-1]);
            this.loadCalendarUI();
        }
    }

    nextMonth() {
        const monthsArr = this.monthAndVal();
        if($("#ct-month").attr("data-ct-month")=="12") {
            $("#ct-month").attr("data-ct-month", 1).html(monthsArr[0]);
            this.increaseYear();
        } else {
            const presentMonth = $("#ct-month").attr("data-ct-month");
            const newMonth = Number(presentMonth)+1
            $("#ct-month").attr("data-ct-month", newMonth).html(monthsArr[newMonth-1]);
            this.loadCalendarUI();
        }
    }

    updateMonthDivToPresent() {
        const presentMonth = new Date().getMonth();
        const monthsArr = this.monthAndVal();
        $("#ct-month").attr("data-ct-month", presentMonth+1).html(monthsArr[presentMonth])
    }

    updateYearDivToPresent() {
        const presentYear = new Date().getFullYear();
        $("#ct-year").attr("data-ct-year", presentYear).html(presentYear)
    }

    loadCalendarUI() {
        const selectedMonth = $("#ct-month").attr("data-ct-month");
        const selectedYear = $("#ct-year").attr("data-ct-year");
        const startingdayForSelectedMonth = String(new Date(`${selectedMonth}-01-${selectedYear}`)).slice(0,3);

        this.RenderCalendarDays(startingdayForSelectedMonth, selectedMonth, selectedYear);
        //this.getCasesInTheMonth();
    }

    RenderCalendarDays(startingDay, month, year) {
        $("#jbct--date")
        $("#jbct--date").html("");
        this.addCalendarPlaceholderDates(startingDay, month, year);
        this.appendDaysInTheMonthToCal(month, year, $("#jbct--date"));
        this.appendDatesIntoCalendarBlankWeekDays(month, year)
    }

    appendDatesIntoCalendarBlankWeekDays(month, year) {
        const startingdayForSelectedMonth = String(new Date(`${month}-01-${year}`)).slice(0,3);
        this.addCalendarPlaceholderDates(startingdayForSelectedMonth, month, year, true);
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

    appendDaysInTheMonthToCal(month, year, elem) {
        const NoOfDays = this.numberOfDaysInMonth(month, year);
        for(let i=0; i<NoOfDays; i++) {
            const fullDate = this.renderFullDate(year, month, i+1)
            elem.append(
                `<div id="day${this.addLeadingZeros(i+1)}${this.addLeadingZeros(month)}${year}"
                data-fulldate="${fullDate}" class="day-num"><span class="date-jbct">${i+1}</span></div>`
            );
        }
    }

    /**
     * This methods Prepend or Append disabled Placeholder Dates in the calendar UI i.e
     * Adds a preceeding disabled dates (as placeholders) for months whose first day isnt Sunday
     * and Adds suceeding disabled dates (as placeholders) for months  whose last day isn't Sunday.
     *
     * You might need to refer to the UI to undertstand what this method does
     *
     * @param   {string} day    - The first day of the month in the UI's view. Sun, Mon, Tue, Wed, Thu, Fri or Sat.
     * @param   {string} month  - The Month to be viewed in the calendar UI.
     * @param   {string} year   - The Year to be viewed in the calendar UI.
     * @param   {number} append - Append the dates as placeholder
     * @returns {void}
     *
     */
    addCalendarPlaceholderDates(startingDay, month, year, append=false) {
        const casetrackerDate = $("#jbct--date");

        // The variable *theMonth* should hold information about the present month if we want to append placeholder dates.
        // However, if we are prepending dates, then the variable *theMonth* should hold information about the month prior
        // to the month in view in the UI (i.e the previous month).
        const theMonth = !append ? Number(month)-1 : Number(month);
        const numberOfDaysInMonth = this.numberOfDaysInMonth(theMonth, year)


        //No need to prepend any date since sunday comes first. We only need to use the
        // logic below for days ending with a sunday so we can append placeholder dates
        // to complete the calendar UI.
        if (startingDay=="Sun") {
            if(numberOfDaysInMonth===29) {
                append ? this.appendValUsingLoop(6, $("#jbct--date")) : ''
            } else if(numberOfDaysInMonth===28) {
                return '';
            } else if(numberOfDaysInMonth===30) {
                append ? this.appendValUsingLoop(5, $("#jbct--date")) : '';
            } else {
                append ? this.appendValUsingLoop(4, $("#jbct--date")) : '';
            }
        }

        // There is need to consider both Appending and Prepending.
        if (startingDay=="Mon") {
            if (numberOfDaysInMonth===29) {
                !append ? casetrackerDate.prepend(
                    `<div class="day-num placeholder"><span class="date-jbct ph-date">29</span></div>`
                ) : this.appendValUsingLoop(5, casetrackerDate);
            } else if(numberOfDaysInMonth===28) {
                !append ? casetrackerDate.prepend(
                    `<div class="day-num placeholder"><span class="date-jbct ph-date">28</span></div>`
                ) : this.appendValUsingLoop(6, casetrackerDate);
            } else if(numberOfDaysInMonth===30) {
                !append ? casetrackerDate.prepend(
                    `<div class="day-num placeholder"><span class="date-jbct ph-date">30</span></div>`
                ) : this.appendValUsingLoop(4, casetrackerDate);
            } else {
                !append ? casetrackerDate.prepend(
                    `<div class="day-num placeholder"><span class="date-jbct ph-date">31</span></div>`
                ) : this.appendValUsingLoop(3, casetrackerDate);
            }
        }

        if(startingDay=="Tue") {
            if (numberOfDaysInMonth===29) {
                !append ? this.prependValUsingLoop(2, casetrackerDate, 29) : this.appendValUsingLoop(4, casetrackerDate);
            } else if(numberOfDaysInMonth===28) {
                !append ? this.prependValUsingLoop(2, casetrackerDate, 28) : this.appendValUsingLoop(5, casetrackerDate);
            } else if(numberOfDaysInMonth===30) {
                !append ? this.prependValUsingLoop(2, casetrackerDate, 30) : this.appendValUsingLoop(3, casetrackerDate);
            } else {
                !append ? this.prependValUsingLoop(2, casetrackerDate, 31) : this.appendValUsingLoop(2, casetrackerDate);
            }
        }

        if (startingDay=="Wed") {
            if(numberOfDaysInMonth===29) {
                !append ? this.prependValUsingLoop(3, casetrackerDate, 29) : this.appendValUsingLoop(3, casetrackerDate);
            } else if(numberOfDaysInMonth===28) {
                !append ? this.prependValUsingLoop(3, casetrackerDate, 28) : this.appendValUsingLoop(4, casetrackerDate);
            } else if(numberOfDaysInMonth===30) {
                !append ? this.prependValUsingLoop(3, casetrackerDate, 30) : this.appendValUsingLoop(2, casetrackerDate);
            } else {
                !append ? this.prependValUsingLoop(3, casetrackerDate, 31) : this.appendValUsingLoop(1, casetrackerDate);
            }
        }

        if(startingDay=="Thu") {
            if (numberOfDaysInMonth===29) {
                !append ? this.prependValUsingLoop(4, casetrackerDate, 29) : this.appendValUsingLoop(2, casetrackerDate);
            } else if(numberOfDaysInMonth===28) {
                !append ? this.prependValUsingLoop(4, casetrackerDate, 28) : this.appendValUsingLoop(1, casetrackerDate);
            } else if(numberOfDaysInMonth===30) {
                !append ? this.prependValUsingLoop(4, casetrackerDate, 30) : this.appendValUsingLoop(6, casetrackerDate);
            } else {
                !append ? this.prependValUsingLoop(4, casetrackerDate, 31) : '';
            }
        }

        if(startingDay=="Fri") {
            if (numberOfDaysInMonth===29) {
                !append ? this.prependValUsingLoop(5, casetrackerDate, 29) : this.appendValUsingLoop(1, casetrackerDate);
            } else if(numberOfDaysInMonth===28) {
                !append ? this.prependValUsingLoop(5, casetrackerDate, 28) : this.appendValUsingLoop(2, casetrackerDate);
            } else if(numberOfDaysInMonth===30) {
                !append ? this.prependValUsingLoop(5, casetrackerDate, 30) : '';
            } else {
                !append ? this.prependValUsingLoop(5, casetrackerDate, 31) : this.appendValUsingLoop(1, casetrackerDate);
            }
        }

        //No need to append any date after saturday since saturday comes last in the UI.
        if(startingDay=="Sat") {
            if (numberOfDaysInMonth===29) {
                !append ? this.prependValUsingLoop(6, casetrackerDate, 29) : '';
            } else if(numberOfDaysInMonth===28) {
                !append ? this.prependValUsingLoop(6, casetrackerDate, 28) : this.appendValUsingLoop(1, casetrackerDate);
            } else if(numberOfDaysInMonth===30) {
                !append ? this.prependValUsingLoop(6, casetrackerDate, 30) : this.appendValUsingLoop(6, casetrackerDate);
            } else {
                !append ? this.prependValUsingLoop(6, casetrackerDate, 31) :  this.appendValUsingLoop(5, casetrackerDate);
            }
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

    getCasesInTheMonth() {
        const yr = $("#ct-year").attr("data-ct-year");
        const month = $("#ct-month").attr("data-ct-month");
        const monthDays =  this.numberOfDaysInMonth(String(month), yr);
        const monthObjectForCaseCall = {}
        for (let i=0; i<monthDays; i++) {
            //const dateInfo = `day${this.addLeadingZeros(i+1)}${this.addLeadingZeros(month)}${yr}`
            monthObjectForCaseCall[`day${this.addLeadingZeros(i+1)}`] = this.renderFullDate(yr, month, i+1)
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
}

const cal = new Calendar;