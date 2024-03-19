var initData = Telegram.WebApp.initData || '';
var initDataUnsafe = Telegram.WebApp.initDataUnsafe || {};
if (initDataUnsafe.query_id && initData) {
    Telegram.WebApp.expand();
}
