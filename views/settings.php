<div class="container">
    <div class="row m-5"></div>
    <div class="row">
        <div class="col">
            <form method="post" action="">
                <div class="form-group">
                    <label for="textMessage">نمونه متن ارسالی</label>
                    <textarea class="form-control" name="textMessage" id="textMessage" rows="5"><?php echo esc_html( $data['text'] ); ?></textarea>
                </div>
                <input type="submit" class="btn btn-primary full-width mt-2" id="submit" value="ذخیره کردن"/>
            </form>
        </div>
    </div>
    <div class="row m-5"></div>
    <div class="row">
        <div class="col">
            <p>شورت کد های قابل استفاده: </p>
            <p>نام کامل مشتری: <code>{orderfullname}</code></p>
            <p>شماره سفارش: <code>{oredernumber}</code></p>
            <p>کد مرسوله: <code>{trackingcode}</code></p>
        </div>
    </div>
    <div class="row m-5"></div>
    <div class="row">
        <div class="col">
            <p>اگر از سرویس خدماتی پیامک ملی استفاده می کنید میتوانید شورت کد های بالا را در نمونه متن ارسالی استفاده کنید.</p>
            <p>برای نمونه: <code>@33117@{orderfullname};{oredernumber};{trackingcode}##shared</code></p>
        </div>
    </div>
</div>