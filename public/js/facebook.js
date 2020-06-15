window.fbAsyncInit = function () {
    FB.init({
        appId: '2743452592557910',
        autoLogAppEvents: true,
        cookie: true,
        xfbml: true,
        version: 'v7.0'
    });

    FB.Event.subscribe('send_to_messenger', function (e) {
        console.log(e)
    });
};