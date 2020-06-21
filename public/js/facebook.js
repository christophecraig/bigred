window.fbAsyncInit = function () {
    FB.init({
        appId: '2743452592557910',
        autoLogAppEvents: true,
        xfbml: true,
        version: 'v7.0'
    });

    // Check if user is connected to fb
    console.log('Check if user is connected to fb')
    FB.getLoginStatus(function (res) {
        console.log('entering the getLogin')
        console.log(res)
    });

    FB.Event.subscribe('send_to_messenger', function (e) {
        // callback for events triggered by the plugin
        console.log(e)
    });
};