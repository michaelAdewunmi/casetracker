<?php
/*
 * Template Name: Login and Registration Template
 * Description: A Template For the Login and Registration Page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

    get_header();
?>
<link href="https://fonts.googleapis.com/css?family=Ubuntu:300,400,500&display=swap&subset=latin-ext" rel="stylesheet">
<link rel="stylesheet"
    href="<?php echo plugin_dir_url(null) ?>/case-track-calendar/assets/css/jbct-cal.css?ver=20191208" type="text/css" media="all">
<section id="case-cal--container">
    <div id="case-cal--wrapper">
        <header>
            <p>This is the case calendar viewer</p>
        </header>
        <div id="calendar-holder">
            <div id="year-header">
                <div class="btn--change" id="prev--btn"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                <p id="ct-year" data-ctyear="2020">2020</p>
                <span class="btn--change" id="next--btn"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
            </div>
            <div id="month-header">
                <div class="btn--change month-change" id="prev--mnth"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
                <p id="ct-month" data-ctmonth="01">January</p>
                <span class="btn--change month-change" id="next--mnth"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
            </div>
            <div id="jbct--days">
                <div class="jbct--day-of-week">Sunday</div><div class="jbct--day-of-week">Monday</div>
                <div class="jbct--day-of-week">Tuesday</div><div class="jbct--day-of-week">Wednesday</div>
                <div class="jbct--day-of-week">Thursday</div><div class="jbct--day-of-week">Friday</div>
                <div class="jbct--day-of-week">Saturday</div>
            </div>
            <div id="jbct--date"></div>
        </div>
    </div>
</section>