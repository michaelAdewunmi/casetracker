<?php
use DevignersPlace\CaseTracker\PublicDir\Classes\AddCourtCase;
use DevignersPlace\CaseTracker\Includes\TasksPerformer;
use DevignersPlace\CaseTracker\Includes\PostGlobalVariableGetter;

$add_case_instance = new AddCourtCase(wp_get_current_user(), new \WP_Error);
$add_case_instance->setUp(new PostGlobalVariableGetter);
if (AddCourtCase::$user_unauthorised) {
    wp_redirect(esc_url(site_url()));
    exit;
}
get_header();
?>
<section id="case-adder">
    <?php
    if ($add_case_instance::isCaseAdderRedirect()) :
        if (!$add_case_instance::isAValidNonce()) :
            ?>
            <div class="record-added errored">Sorry! You cannot view this page at this time.</div>
        <?php else : ?>
            <div class="record-added">Case Successfully Added!</div>
            <p class="tagline big">Court Case has been successfully added. You can Go Home to check case details.</p>
        <?php endif;
    else :
        ?>
    <div id="notification">
        <div class="notifier">
            <div id="msg"></div>
            <div id="notification-btn" class="mkinnig-rounded-btn close-notify">Close Notification</div>
        </div>
    </div>
    <div class="section-body-wrapper-boxed">
        <h3 class="cstrck-form-heading"><?php _e('Add Court Case'); ?></h3>
        <?php echo $add_case_instance->getFormErrors();?>
        <form action="" method="POST">
            <fieldset>
                <div class="flexed-wrapper">
                    <p class="cstrck-input-wrapper">
                        <?php echo AddCourtCase::getInputErrorString('suit-number'); ?>
                        <label for="suit-number"><?php _e('Suit Number'); ?></label>
                        <input name="suit-number" type="text"
                            class="casetracker-input
                                <?php echo $add_case_instance->getInputErrorClass('suit-number'); ?>"
                            value="<?php echo AddCourtCase::getInputPostValue('suit-number') ?>"
                        />
                    </p>
                    <?php
                    $lawyer_id = AddCourtCase::getInputPostValue('lawyer-assigned');
                    $lawyer_name = TasksPerformer::getUserFullName($lawyer_id);
                    ?>
                    <p class="cstrck-input-wrapper">
                        <select name="lawyer-assigned"
                            class="casetracker-select-input
                                <?php echo $add_case_instance->getInputErrorClass('lawyer-assigned'); ?>"
                        >
                            <option value="<?php echo $lawyer_id ? $lawyer_id : ""; ?>" default>
                                <?php echo $lawyer_name ?? _e('Assign a Lawyer'); ?>
                            </option>
                            <?php
                            $all_users = get_users();
                            foreach ($all_users as $user_obj) {
                                echo '<option value="'.$user_obj->ID.'">'.$user_obj->data->display_name.'</option>';
                            }
                            ?>
                        </select>
                    </p>
                </div>
                <div class="flexed-wrapper">
                    <p class="cstrck-input-wrapper">
                        <?php echo AddCourtCase::getInputErrorString('court-name'); ?>
                        <label for="court-name"><?php _e('Court Name'); ?></label>
                        <input name="court-name" type="text"
                            class="casetracker-input <?php echo $add_case_instance->getInputErrorClass('court-name'); ?>"
                            value="<?php echo AddCourtCase::getInputPostValue('court-name'); ?>"
                        />
                    </p>
                    <p class="cstrck-input-wrapper">
                        <?php echo AddCourtCase::getInputErrorString('court-address'); ?>
                        <label for="court-address"><?php _e('Court Address'); ?></label>
                        <input name="court-address" type="text"
                            class="casetracker-input <?php echo $add_case_instance->getInputErrorClass('court-address'); ?>"
                            value="<?php echo AddCourtCase::getInputPostValue('court-address') ?>"
                        />
                    </p>
                </div>
                <div class="flexed-wrapper">
                    <p class="cstrck-input-wrapper">
                        <?php echo AddCourtCase::getInputErrorString('case-description'); ?>
                        <label for="case-description"><?php _e('Case Description'); ?></label>
                        <input name="case-description" type="text"
                            class="casetracker-input
                                <?php echo $add_case_instance->getInputErrorClass('case-description'); ?>"
                            value="<?php echo AddCourtCase::getInputPostValue('case-description'); ?>"
                        />
                    </p>
                    <p class="cstrck-input-wrapper">
                        <?php echo AddCourtCase::getInputErrorString('case-start-date'); ?>
                        <input name="case-start-date" type="date"
                            class="casetracker-input
                                <?php echo $add_case_instance->getInputErrorClass('case-start-date'); ?>"
                            value="<?php echo AddCourtCase::getInputPostValue('case-start-date'); ?>"
                        />
                    </p>
                </div>
                <div class="flexed-wrapper">
                    <div class="tag-wrapper">
                        <label for="">Select tag(s) from the list below or add a new tag using the input</label>
                        <div id="present-tags">
                            <?php
                            $all_tags = get_tags(array('hide_empty'=>0));
                            foreach ($all_tags as $tag_obj) {
                                if ($tag_obj->name!="Uncategorized") {
                                    $slug = str_replace(" ", "_", strtolower($tag_obj->name));
                                    echo '<div class="checkbox-wrapper">';
                                    echo '<input onChange="regscript.addTagFromRadio(this)" type="checkbox" id="'.$slug.
                                        '" data-termid="'.$tag_obj->name.'" class="cb-inputs cb-tags" data-slug="'.
                                        $slug.'" data-name="'.$tag_obj->name.'"/>';
                                    echo '<label for="'.$slug.'" style="cursor: pointer;">' . $tag_obj->name . '</label>';
                                    echo '</div>';
                                }

                            }
                            ?>
                            <ul id="tags-list"></ul>
                        </div>
                        <input
                            id="tag-input" type="text" class="casetracker-tags-input"
                            placeholder="Type a new tag here and press enter"
                        />
                    </div>
                </div>
                <p class="cstrck-input-wrapper">
                    <input type="hidden" name="case-adder-nonce"
                        value="<?php echo wp_create_nonce('case-adder-nonce'); ?>"
                    />
                    <input type="hidden" name="case-tags" id="case-tags" />
                    <input id="submit-btn" type="submit" value="<?php _e('Add Case'); ?>" />
                </p>
            </fieldset>
        </form>
    </div>
    <?php endif; ?>
</section>
<?php
get_footer();
