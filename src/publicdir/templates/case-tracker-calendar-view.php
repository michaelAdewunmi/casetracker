<?php
/*
 * Template Name: CaseTracker Calendar View Template
 * Description: A Template to show the cases using the calendar UI
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

get_header();
?>
<section id="case-cal--container">
    <div id="case-cal--wrapper">
        <header>
            <p>This is the case calendar viewer</p>
        </header>
        <div id="calendar-holder">
            <div id="year-header">
                <div class="btn--change" id="prev--btn">
                    <!-- <i class="fa fa-chevron-left" aria-hidden="true"></i> -->
                    <i class="fas fa-angle-left"></i>
                </div>
                <p id="ct-year" data-ct-year=""></p>
                <span class="btn--change" id="next--btn">
                    <!-- <i class="fa fa-chevron-right" aria-hidden="true"></i> -->
                    <i class="fas fa-angle-right"></i>
                </span>
            </div>
            <div id="month-header">
                <div class="btn--change month-change" id="prev--mnth">
                <i class="fas fa-angle-left"></i>
                </div>
                <p id="ct-month" data-ct-month=""></p>
                <span class="btn--change month-change" id="next--mnth">
                <i class="fas fa-angle-right"></i>
                </span>
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
<?php
get_footer();