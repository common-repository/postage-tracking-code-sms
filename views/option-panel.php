<div class="container">
    <div class="row m-5"></div>
    <div class="row">

        <div class="alert alert-success text-center" style="display: none;" role="alert" id="alertOrderDetails">
            <p>سفارش شماره: <span class="text-black" id="orderNumberTitle"></span> برای <span id="orderFullName"></span> با شماره همراه <span id="orderPhone"></span> می باشد.</p>
        </div>

        <div class="alert alert-danger text-center" style="display: none;" role="alert" id="alertError">
            <p id="alertErrorText"></p>
        </div>

        <div class="alert alert-success text-center" style="display: none;" role="alert" id="alertSuccess">
            <p id="alertSuccessText"></p>
        </div>

    </div>

    <div class="row">
        <div class="col-1 offset-6">
            <div class="spinner-grow text-center" style="display: none;" id="loading" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="mb-3">
            <label for="orderNumberInput" class="form-label">شماره سفارش</label>
            <input type="text" class="form-control form-control-lg" id="orderNumberInput" placeholder="12345">
        </div>
    </div>

    <div class="row" id="bottomSection" style="display: none;">
        <div class="mb-3">
            <label for="trackingCode" class="form-label">شناسه مرسوله</label>
            <input type="text" class="form-control form-control-lg" id="trackingCode" placeholder="123456791234567">
        </div>
        <div class="d-grid gap-2 col-6 mx-auto m-3 btn-lg">
            <button type="submit" class="btn btn-outline-primary full-width" id="sendSms">ارسال پیامک برای مشتری</button>
            <button type="submit" class="btn btn-primary full-width" id="confirmSendSms" style="display: none;">برای تایید ارسال کلیک کنید</button>
        </div>

    </div>

    <div class="row m-5"></div>

    <div class="row">
        <p class="h2">آخرین پیام های ارسالی: </p>
        <hr>
        <table class="table table-hover" id="tbl">
            <tbody>
            </tbody>
        </table>
        <div class="row">
            <div class="col-1 offset-6">
                <div class="spinner-grow text-center" style="display: none;" id="loadingLatestData" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>