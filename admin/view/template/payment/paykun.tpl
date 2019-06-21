<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if (empty($curl_version)) { ?>
    <div class="warning"><?php echo $text_curl_disabled; ?></div>
    <?php } ?>
    <?php if ($warning) { ?>
    <div class="warning"><?php echo $warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form-paykun').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
    </div>
    <div class="content">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-paykun" class="form-horizontal">

            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            </ul>
            <table class="form">
                <tr>
                    <td><label class="control-label col-sm-3" for="is_enabled">
                            <span data-toggle="tooltip" title="Is extension enabled?">Is Enabled</span>
                        </label></td>
                    <td>  <select name="paykun_status" class="form-control">
                            <?php if ($paykun_status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?> </option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label class="control-label col-sm-3" for="paykun_merchant_id">
                            <span data-toggle="tooltip" title="<?php echo $entry_merchant_id_help; ?>"><?php echo $entry_merchant_id; ?></span>
                        </label></td>
                    <td><input type="text" name="paykun_merchant_id" id="paykun_merchant_id" value="<?php echo $paykun_merchant_id; ?>" class="form-control"/>
                        <?php if ($error_merchant_id) { ?>
                        <div class="text-danger"><?php echo $error_merchant_id; ?></div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td><label class="control-label col-sm-3" for="paykun_access_token">
                            <span data-toggle="tooltip" title="<?php echo $entry_access_token_help; ?>"><?php echo $entry_access_token; ?></span>
                        </label></td>
                    <td> <input type="text" name="paykun_access_token" id="paykun_access_token" value="<?php echo $paykun_access_token; ?>" class="form-control"/>
                        <?php if ($error_access_token) { ?>
                        <div class="text-danger"><?php echo $error_access_token; ?></div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td> <label class="control-label col-sm-3" for="paykun_enc_key">
                            <span data-toggle="tooltip" title="<?php echo $entry_enc_key_help; ?>"> <?php echo $entry_enc_key; ?></span>
                        </label></td>
                    <td> <input type="text" name="paykun_enc_key" id="paykun_enc_key" value="<?php echo $paykun_enc_key; ?>" class="form-control"/>
                        <?php if ($error_enc_key) { ?>
                        <div class="text-danger"><?php echo $error_enc_key; ?></div>
                        <?php } ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="control-label col-sm-3" for="paykun_order_success_status_id">
                      <span data-toggle="tooltip" title="<?php echo $entry_order_success_status_help; ?>">
                        <?php echo $entry_order_success_status; ?>
                      </span>
                        </label></td>
                    <td>
                        <select name="paykun_order_success_status_id" id="paykun_order_success_status_id" class="form-control">
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <?php if ($order_status['order_status_id'] == $paykun_order_success_status_id) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>"> <?php echo $order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td> <label class="control-label col-sm-3" for="paykun_order_failed_status_id">
                            <span data-toggle="tooltip" title="<?php echo $entry_order_failed_status_help; ?>"><?php echo $entry_order_failed_status; ?></span>
                        </label></td>
                    <td>
                        <select name="paykun_order_failed_status_id" id="paykun_order_failed_status_id" class="form-control">
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <?php if ($order_status['order_status_id'] == $paykun_order_failed_status_id) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>"> <?php echo $order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label class="control-label col-sm-3" for="paykun_log_status">
                            <span data-toggle="tooltip" title="<?php echo $entry_log_status_help ?>"><?php echo $entry_log_status ?></span>
                        </label></td>
                    <td> <select name="paykun_log_status" class="form-control">
                            <?php if ($paykun_log_status){ ?>
                            <option value="1" selected="selected"><?php echo $text_enabled ?></option>
                            <option value="0"><?php echo $text_disabled ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled ?></option>
                            <?php } ?>
                        </select></td>
                </tr>
                <tr>
                    <td><label class="col-sm-3 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                    </td>
                    <td> <input type="text" name="paykun_sort_order" value="<?php echo $paykun_sort_order; ?>"  id="input-sort-order" class="form-control" />
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
</div>
</div>
<style>.hidden{ display:none;}</style>
<script type="text/javascript"><!--
    var default_callback_url = "<?php echo $default_callback_url; ?>";

    function toggleCallbackUrl(){
        if($("select[name=\"Paykun_callback_url_status\"]").val() == "1"){
            $(".callback_url_group").removeClass("hidden");
            $("input[name=\"Paykun_callback_url\"]").prop("readonly", false);
        } else {
            $(".callback_url_group").addClass("hidden");
            $("#Paykun_callback_url").val(default_callback_url);
            $("input[name=\"Paykun_callback_url\"]").prop("readonly", true);
        }
    }

    $(document).on("change", "select[name=\"Paykun_callback_url_status\"]", function(){
        toggleCallbackUrl();
    });
    toggleCallbackUrl();
</script>
<?php echo $footer; ?>

