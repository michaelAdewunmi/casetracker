<?php
use DevignersPlace\CaseTracker\PublicDir\Classes\RegistrationFormUtil;
use DevignersPlace\CaseTracker\Includes\RegisterLawyer;

$reg_util = new RegistrationFormUtil();
get_header();
?>
<link href="https://fonts.googleapis.com/css2?family=Recursive:wght@300;400;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;700&family=Recursive:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo plugin_dir_url(NULL) . '/case-tracker/src/publicdir/css/public-style.css'; ?>">
<div id="add-lawyer-page">
    <?php if (isset($_REQUEST["done"]) && isset($_REQUEST["u"]) && wp_verify_nonce($_REQUEST["pass"], 'allow-user-view')) : ?>

        <?php if (!get_userdata($_REQUEST["u"])) : ?>
            <div class="lawyer-added errored">Sorry! You cannot view this page at this time.</div>
        <?php else : ?>
            <?php
            $user_object = get_user_by('ID', $_REQUEST["u"]);
            $ct_id = $user_object->data->ID;
            $ct_email = $user_object->data->user_email;
            $ct_first_name = get_the_author_meta('first_name', $ct_id);
            $ct_last_name = get_the_author_meta('last_name', $ct_id);
            $ct_gender = get_the_author_meta('gender', $ct_id);
            $ct_phone = get_the_author_meta('phone_number', $ct_id);
            $ct_login = $user_object->data->user_login;
            ?>
            <div class="lawyer-added">Lawyer Successfully Added</div>
            <div class="added-info">
                <p class="lawyer-info-wrapper">Full Name: <?php echo $ct_first_name ." ".$ct_last_name; ?></p>
                <p class="lawyer-info-wrapper">Phone: <?php echo $ct_phone; ?></p>
                <p class="lawyer-info-wrapper">Email: <?php echo $ct_email; ?></p>
                <p class="lawyer-info-wrapper">Username: <?php echo $ct_login; ?></p>
                <p class="lawyer-info-wrapper">Password: the password typed</p>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <h3 class="cstrxk-form-heading"><?php _e('Register New Lawyer or Judge'); ?></h3>
        <?php $reg_util->getFormErrors(); ?>
        <form id="reg-form" action="" method="POST">
            <fieldset>
                <div class="flexed-wrapper">
                    <p class="cstrck-input-wrapper">
                        <?php $reg_util->handleErrorsIfAny('empty_lawyer-first-name'); ?>
                        <label for="lawyer-first-name"><?php _e('Lawyer First Name'); ?></label>
                        <input name="lawyer-first-name" type="text"
                            class="casetracker-input <?php $reg_util->addErrorClassOnError('empty_lawyer-first-name'); ?>"
                            value="<?php echo $reg_util->getInputValue('lawyer-first-name'); ?>"
                        />
                    </p>
                    <p class="cstrck-input-wrapper">
                        <?php $reg_util->handleErrorsIfAny('empty_lawyer-last-name'); ?>
                        <label for="lawyer-last-name"><?php _e('Lawyer Last Name'); ?></label>
                        <input name="lawyer-last-name" type="text"
                            class="casetracker-input <?php $reg_util->addErrorClassOnError('empty_lawyer-last-name'); ?>"
                            value="<?php echo $reg_util->getInputValue('lawyer-last-name'); ?>"
                        />
                    </p>
                </div>
                <div class="flexed-wrapper">
                    <p class="cstrck-input-wrapper">
                        <select name="lawyer-gender"
                            class="casetracker-select-input <?php $reg_util->addErrorClassOnError('empty_lawyer-gender'); ?>"
                        >
                            <?php $gender_value = $reg_util->getInputValue('lawyer-gender'); ?>
                            <option value="<?php echo $gender_value ? $gender_value : ""; ?>" default>
                                <?php echo $gender_value ? $gender_value : "Select Lawyer Gender"; ?>
                            </option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </p>
                    <p class="cstrck-input-wrapper">
                        <?php $reg_util->handleErrorsIfAny('empty_lawyer-username, username_invalid, username_unavailable'); ?>
                        <label for="jbct_username"><?php _e('Lawyer Username'); ?></label>
                        <input name="lawyer-username" type="text"
                            class="casetracker-input
                                <?php
                                $reg_util->addErrorClassOnError(
                                    'username_invalid, username_unavailable, empty_lawyer-username,'
                                );
                                ?>"
                            value="<?php echo $reg_util->getInputValue('lawyer-username'); ?>"
                        />
                    </p>
                </div>
                <div class="flexed-wrapper">
                    <p class="cstrck-input-wrapper">
                        <?php $reg_util->handleErrorsIfAny('empty_lawyer-email, email_invalid'); ?>
                        <label for="lawyer-email"><?php _e('Lawyer Email'); ?></label>
                        <input name="lawyer-email" type="email"
                            class="casetracker-input <?php $reg_util->addErrorClassOnError('email_invalid, empty_lawyer-email'); ?>"
                            value="<?php echo $reg_util->getInputValue('lawyer-email'); ?>"
                        />
                    </p>
                    <p class="cstrck-input-wrapper">
                        <?php $reg_util->handleErrorsIfAny('empty_lawyer-phone'); ?>
                        <label for="lawyer-phone"><?php _e('Lawyer Phone Number'); ?></label>
                        <input name="lawyer-phone" type="text"
                            class="casetracker-input <?php $reg_util->addErrorClassOnError('lawyer-phone'); ?>"
                            value="<?php echo $reg_util->getInputValue('lawyer-phone'); ?>"
                        />
                    </p>
                </div>
                <div class="flexed-wrapper">
                    <p class="cstrck-input-wrapper">
                        <?php $reg_util->handleErrorsIfAny('empty_lawyer-password, passwords_mismatch'); ?>
                        <label for="lawyer-password"><?php _e('Lawyer Password'); ?></label>
                        <input name="lawyer-password" type="password"
                            class="casetracker-input <?php $reg_util->addErrorClassOnError('passwords_mismatch, lawyer-password'); ?>"
                            value="<?php echo $reg_util->getInputValue('lawyer-password'); ?>"
                        />
                    </p>
                    <p class="cstrck-input-wrapper">
                        <?php $reg_util->handleErrorsIfAny('empty_lawyer-password-confirm, passwords_mismatch'); ?>
                        <label for="lawyer-password-confirm"><?php _e('Retype Lawyer Password'); ?></label>
                        <input name="lawyer-password-confirm" type="password"
                            class="casetracker-input <?php $reg_util->addErrorClassOnError('passwords_mismatch, lawyer-password-confirm'); ?>"
                            value="<?php echo $reg_util->getInputValue('lawyer-password-confirm'); ?>"
                        />
                    </p>
                </div>
                <p class="cstrck-input-wrapper">
                    <input type="hidden" name="lawyer-registration-nonce"
                        value="<?php echo wp_create_nonce('casetracker-lawyer-registration-nonce'); ?>"
                    />
                    <input id="submit-btn" type="submit" value="<?php _e('Register Lawyer'); ?>" />
                </p>
            </fieldset>
        </form>
    <?php endif; ?>
</div>
<?php
get_footer();
?>
<script>
    const $ = jQuery;

    $(window).on("load", function() {
        $(".casetracker-input").each(moveLabelUpIfNotEmpty);
        $("label").on("click", focusInputSibling);
        $(".casetracker-input").on("focus", moveLabelUp);
        $(".casetracker-input").on("blur", returnLabelIfEmpty);
        //$(".casetracker-input").on("keyup", typingUtilitiesHandler);
        $("select").on("focus", removeErroredClass);

        function removeErroredClass(e) {
            typingUtilitiesHandler(e);
            $(e.currentTarget).removeClass("errored");
        }

        function typingUtilitiesHandler(e) {
            $(e.currentTarget.parentNode).find(".error-string").css({
                "transform": "scale(0)"
            })
        }

        function showErrorIfNecessary(e) {
            if (e.currentTarget.value.trim()==="") {
                $(e.currentTarget).addClass("errored");
                $(e.currentTarget.parentNode).find(".error-string").css({
                    "transform": "scale(1)"
                })
            }
        }

        function moveLabelUpIfNotEmpty(e, item) {
            var input = item
            var inputVal = $(item).context.value;
            var inputLabel = $($(item).context.previousElementSibling);

            if(inputVal.trim()!="") {
                adjustLabelCss(inputLabel);
            }
        }

        function adjustLabelCss(elem)
        {
            elem.css({
                "top": "-30px",
                "left": 0,
                "font-size": "10px"
            })
        }

        function focusInputSibling(e) {
            const elementSibling = $(e.target.nextElementSibling);
            if (elementSibling.context && elementSibling.context.nodeName==="INPUT") {
                //Foucsing the Input should trigger the moveLabelUp Function
                elementSibling.focus();
            }
        }

        function moveLabelUp(e) {
            removeErroredClass(e);
            const input = e.currentTarget;
            const inputLabel = $(input.previousElementSibling)

            adjustLabelCss(inputLabel);
        }

        function returnLabelIfEmpty(e) {
            showErrorIfNecessary(e);
            const input = e.target;
            const inputVal = $(input).val();

            if (inputVal=="" || inputVal.trim()=="") {
                $(input.previousElementSibling).css({
                    "top": "18px",
                    "left": "15px",
                    "font-size": "1.6rem"
                })
            }
        }
    })
</script>