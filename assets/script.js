jQuery(document).ready(function () {

    let orderNumberInput = jQuery("#orderNumberInput");

    let alertOrderDetails = jQuery("#alertOrderDetails");
    let alertOrderError = jQuery("#alertError");
    let alertOrderErrorText = jQuery("#alertErrorText")
    let alertSuccess = jQuery("#alertSuccess");
    let alertSuccessText = jQuery("#alertSuccessText")
    let trackingCode = jQuery("#trackingCode");
    let orderNumberTitle = jQuery("#orderNumberTitle");
    let orderFullName = jQuery("#orderFullName");
    let orderPhone = jQuery("#orderPhone");
    let loading = jQuery("#loading");
    let loadingLatestData = jQuery("#loadingLatestData");
    let tbl = jQuery("#tbl");
    let bottomSection = jQuery("#bottomSection");
    let sendSms = jQuery("#sendSms");
    let confirmSendSms = jQuery("#confirmSendSms");

    var orderNumber = "";
    var phoneNumber = "";
    var orderfullname = "";

    alertOrderError.hide()
    alertOrderDetails.hide();
    confirmSendSms.hide();
    loading.hide();

    orderNumberInput.change(function () {
        clearFields();

        orderNumber = orderNumberInput.val();

        loading.show();

        const data = {
            'action': 'postage_tracking_code_sms_get_order_details',
            'order_number': orderNumber
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function (response) {
            const obj = JSON.parse(response);
            loading.hide();
            if (obj.order_number) {
                phoneNumber = obj.phone_number;
                orderNumber = obj.order_number;

                alertOrderDetails.show()
                bottomSection.show()
                sendSms.show()

                orderNumberTitle.text(obj.order_number)
                orderfullname = obj.first_name + " " + obj.last_name;
                orderFullName.text(orderfullname);
                orderPhone.text(obj.phone_number);
            } else {
                alertOrderError.show()
                alertOrderErrorText.text("متاسفانه شماره سفارش صحیح نمی باشد.")
            }
        });

    });

    sendSms.click(function (e) {
            e.preventDefault()
            alertOrderError.hide();
            if (!trackingCode.val()) {
                alertOrderError.show();
                alertOrderErrorText.text("لطفا شماره مرسوله را وارد نمایید.");
            } else {
                sendSms.hide();
                confirmSendSms.show();
                trackingCode.prop('disabled', true);
                orderNumberInput.prop('disabled', true);
            }
        });

    confirmSendSms.click(function (e) {
        e.preventDefault();
        confirmSendSms.prop('disabled', true);

        const data = {
            'action': 'postage_tracking_code_sms_send_sms',
            'order_number': orderNumber,
            'phone_number': phoneNumber,
            'tracking_code': trackingCode.val(),
            'full_name': orderfullname
        };
        loading.show()
        // since 2.8 ajax url is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function (response) {
            const obj = JSON.parse(response);
            loading.hide()
            confirmSendSms.prop('disabled', false);
            if (obj.is_success === true) {
                clearFields()
                alertSuccess.show()
                alertSuccessText.text("پیام با موفقیت برای مشتری ارسال شد.")
                orderNumberInput.val("")
                getList()
            }
        });
    })

    function getList() {
        tbl.empty();
        loadingLatestData.show();
        const data = {
            'action': 'postage_tracking_code_sms_latest_list',
        };

        jQuery.get(ajaxurl, data, function (response) {
            const obj = JSON.parse(response);
            if (obj.length === 0) {
                jQuery("<tr><td><p class='h4'>داده ای برای نمایش وجود ندارد!</p></td></tr>").appendTo(tbl);
            } else {
                obj.forEach(function (element) {
                    jQuery("<tr><td>" + element.replace(/(?:\r\n|\r|\n)/g, '<br>') + "<hr></td></tr>").appendTo(tbl);
                });
            }
            loadingLatestData.hide();
        });
    }

    getList();

    function clearFields() {
        trackingCode.prop('disabled', false);
        orderNumberInput.prop('disabled', false);
        bottomSection.hide();
        alertOrderError.hide()
        alertOrderDetails.hide();
        confirmSendSms.hide();
        alertSuccess.hide();
        loading.hide()
        orderNumber = '';
        phoneNumber = '';
    }
});
