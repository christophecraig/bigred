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

    FB.Event.subscribe('messenger_checkbox', function (e) {
        console.log("messenger_checkbox event");
        console.log(e);

        if (e.event == 'rendered') {
            console.log("Plugin was rendered");
        } else if (e.event == 'checkbox') {
            var checkboxState = e.state;
            if (checkboxState) {
                window.confirmOptin();
            }
            console.log("Checkbox state: " + checkboxState);
        } else if (e.event == 'not_you') {
            console.log("User clicked 'not you'");
        } else if (e.event == 'hidden') {
            console.log("Plugin was hidden");
        }

    });
};