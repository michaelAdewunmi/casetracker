<?php
use DevignersPlace\CaseTracker\PublicDir\Classes\RegistrationFormUtil;
use DevignersPlace\CaseTracker\Includes\RegisterLawyer;

$reg_util = new RegistrationFormUtil();
$gfont_base = "https://fonts.googleapis.com/css2?family=";
get_header();
?>
<section id="add-lawyer-page">
    <?php
    if ($reg_util::isUserCreationRedirect()) :
        if (!get_userdata($_REQUEST["u"])) : ?>
            <div class="record-added errored">Sorry! You cannot view this page at this time.</div>
        <?php else : ?>
            <div class="record-added">Lawyer Successfully Added</div>
            <div class="added-info">
                <p class="lawyer-info-wrapper">Full Name: <?php echo $reg_util::$created_user_full_name; ?></p>
                <p class="lawyer-info-wrapper">Phone: <?php echo $reg_util::$created_user_phone; ?></p>
                <p class="lawyer-info-wrapper">Email: <?php echo $reg_util::$created_user_email; ?></p>
                <p class="lawyer-info-wrapper">Username: <?php echo $reg_util::$created_user_login; ?></p>
                <p class="lawyer-info-wrapper">Password: the password typed</p>
            </div>
        <?php endif;
    else :
        ?>
        <div class="section-body-wrapper-boxed">
            <h3 class="cstrck-form-heading"><?php _e('Register New Lawyer or Judge'); ?></h3>
            <?php $reg_util->getFormErrors();?>
            <form id="reg-form" action="" method="POST">
                <fieldset>
                    <div class="flexed-wrapper">
                        <p class="cstrck-input-wrapper">
                            <?php echo $reg_util::$fname_error_string ?>
                            <label for="lawyer-first-name"><?php _e('Lawyer First Name'); ?></label>
                            <input name="lawyer-first-name" type="text"
                                class="casetracker-input <?php echo $reg_util::$fname_error_class; ?>"
                                value="<?php echo $reg_util::$fname_value ?>"
                            />
                        </p>
                        <p class="cstrck-input-wrapper">
                            <?php ; echo $reg_util::$lname_error_string?>
                            <label for="lawyer-last-name"><?php _e('Lawyer Last Name'); ?></label>
                            <input name="lawyer-last-name" type="text"
                                class="casetracker-input <?php echo $reg_util::$lname_error_class ?>"
                                value="<?php echo $reg_util::$lname_value ?>"
                            />
                        </p>
                    </div>

                    <div class="flexed-wrapper">
                        <?php echo $gender_value = $reg_util::$gender_value; ?>
                        <p class="cstrck-input-wrapper">
                            <select name="lawyer-gender"
                                class="casetracker-select-input <?php echo $reg_util::$gender_error_class ?>"
                            >
                                <option value="<?php echo $gender_value ? $gender_value : ""; ?>" default>
                                    <?php echo $gender_value ? $gender_value : "Select Lawyer Gender"; ?>
                                </option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </p>
                        <p class="cstrck-input-wrapper">
                            <?php echo $reg_util::$username_error_string; ?>
                            <label for="jbct_username"><?php _e('Lawyer Username'); ?></label>
                            <input name="lawyer-username" type="text"
                                class="casetracker-input <?php echo $reg_util::$username_error_class; ?>"
                                value="<?php echo $reg_util::$username_value; ?>"
                            />
                        </p>
                    </div>
                    <div class="flexed-wrapper">
                        <p class="cstrck-input-wrapper">
                            <?php echo $reg_util::$email_error_string; ?>
                            <label for="lawyer-email"><?php _e('Lawyer Email'); ?></label>
                            <input name="lawyer-email" type="email"
                                class="casetracker-input <?php echo $reg_util::$email_error_class; ?>"
                                value="<?php echo $reg_util::$email_value; ?>"
                            />
                        </p>
                        <p class="cstrck-input-wrapper">
                            <?php echo $reg_util::$phone_error_string; ?>
                            <label for="lawyer-phone"><?php _e('Lawyer Phone Number'); ?></label>
                            <input name="lawyer-phone" type="text"
                                class="casetracker-input <?php echo $reg_util::$phone_error_class; ?>"
                                value="<?php echo $reg_util::$phone_value; ?>"
                            />
                        </p>
                    </div>
                    <div class="flexed-wrapper">
                        <p class="cstrck-input-wrapper">
                            <?php echo $reg_util::$pw_error_string; ?>
                            <label for="lawyer-password"><?php _e('Lawyer Password'); ?></label>
                            <input name="lawyer-password" type="password"
                                class="casetracker-input <?php echo $reg_util::$pw_error_class; ?>"
                                value="<?php echo $reg_util::$pw_value; ?>"
                            />
                        </p>
                        <p class="cstrck-input-wrapper">
                            <?php echo $reg_util::$pw_confirm_error_string; ?>
                            <label for="lawyer-password-confirm"><?php _e('Retype Lawyer Password'); ?></label>
                            <input name="lawyer-password-confirm" type="password"
                                class="casetracker-input <?php echo $reg_util::$pw_confirm_error_class; ?>"
                                value="<?php echo $reg_util::$pw_confirm_value; ?>"
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
        </div>
    <?php endif; ?>
</section>
<?php
get_footer();
