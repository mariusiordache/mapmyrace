{include file="web/dashboardV2/structure/user-profile-header.tpl"}

<main id="content" role="main" >
    <div class="container-fluid change-password-wrapper">
        <h3>Change password</h3>
        <div class="form-container">
            <form class="change-password-form">
                <p>
                    <input placeholder="type the old password" class="form-control clean-input" name="old_password" value="" /><img class="status hide" src="/assets/dashboardv2/checkmark_green.svg">
                </p>
                <p>
                    <input placeholder="type the new password" class="form-control clean-input" name="new_password" value="" /><img class="status hide" src="/assets/dashboardv2/checkmark_green.svg">
                </p>
                <p>
                    <input placeholder="retype the new password" class="form-control clean-input" name="retype_password" value="" /><img class="status hide" src="/assets/dashboardv2/checkmark_green.svg">
                </p>
            </form>
            <div class="alert fade alert-success" role="alert" id="changePassword">Password changed succesfully</div>
        </div>
        <div class="change-password">
            <a href="/account/change_password" class="flat-success-btn changePasswordModal">change</a>
            <a href="/account/profile" class="flat-primary-btn">cancel</a>
        </div>
    </div>
</main>
{include file="backbone/dashboardv2/logout.tpl"}
