{include file="web/includes/barebones-header.tpl"}
<div class="container">
    <div class="row">
        <div class="">
            <form id="login-form" class="fade in login-form">
                <input type="hidden" name="goback" value="{if isset($goback)}{$goback|form_prep}{/if}" />
                <legend> Log in <span class="login-subtitle"> {if isset($goback)}{$goback|form_prep}{/if} </span> </legend>
                 <p>Use your T-Me Themes account</p>
                <fieldset>
                    <div class="form-group">
                        <div class='col-md-4'>
                            <label for="username">Username <sup>*</sup></label>
                        </div>
                        <div class='col-md-8'>
                            <i class="glyphicon glyphicon-user"></i>
                            <input class="form-control" type="text" name="username" id="username" placeholder="">
                        </div>
                     </div>
                    <div class="form-group">
                       <div class='col-md-4'>
                           <label for="password">Password <sup>*</sup></label>
                       </div>
                       <div class='col-md-8'>
                           <i class="glyphicon glyphicon-asterisk"></i>
                           <input class="form-control" type="password" name="password" id="password" placeholder="">
                       </div>
                    </div>
                    <div class="form-group">
                       <div class='col-md-4'>

                       </div>
                       <div class='col-md-8'>
                           <div class='col-bg-cb'>
                               <label class="csscheckbox csscheckbox-primary"><input type="checkbox" name="checkbox" value="1" class="csscheckbox" /><span></span></label>
                               
                           </div>
                           Keep me logged in 

                       </div>
                    </div>

                    <div class="alert alert-warning fade text-danger"></div>
                    <div class="text-center">
                        <button type="submit" data-loading-text="logging in ..." class="btn btn-primary">Log me in</button>
                    </div>

                    <a href="/user/forgot">Unable to access your account?</a> <br/>
                    <a href="/user/register">Register</a>
                </fieldset>
            </form>
                
            <div id="thanks" class="text-success">Welcome back! We're redirecting you to your dashboard...</div>
        </div>
    </div>
                 
</div>