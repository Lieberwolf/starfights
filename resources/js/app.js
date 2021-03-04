require('./bootstrap');

var $select = $('.js-planet-select'),
    $planetBefore = $('.js-trigger-before'),
    $planetNext = $('.js-trigger-next');

$select.attr('data-url', window.location.pathname.split('/')[0]);

$planetBefore.on('click touch', function() {
    if($select.find('option[selected]').prev('option').length > 0) {
        window.location.href = $select.find('option[selected]').prev('option').val();
    } else {
        window.location.href = $select.find('option').last().val();
    }
});

$planetNext.on('click touch', function() {
    if($select.find('option[selected]').next('option').length > 0) {
        window.location.href = $select.find('option[selected]').next('option').val();
    } else {
        window.location.href = $select.find('option').first().val();
    }
});


// native stuff
function Trenner(number) {
    // Info: Die '' sind zwei Hochkommas
    number = '' + number;
    if (number.length > 3) {
        var mod = number.length % 3;
        var output = (mod > 0 ? (number.substring(0,mod)) : '');
        for (i=0 ; i < Math.floor(number.length / 3); i++) {
            if ((mod == 0) && (i == 0))
                output += number.substring(mod+ 3 * i, mod + 3 * i + 3);
            else
            // hier wird das Trennzeichen festgelegt mit '.'
                output+= '.' + number.substring(mod + 3 * i, mod + 3 * i + 3);
        }
        return (output);
    }
    else return number;
}

function startTimer(timeRemaining, element) {
    var timestamp = timeRemaining;

    function component(x, v) {
        return Math.floor(x / v);
    }

    setInterval(function() {

        timestamp--;

        var days    = component(timestamp, 24 * 60 * 60),
            hours   = component(timestamp,      60 * 60) % 24,
            minutes = component(timestamp,           60) % 60,
            seconds = component(timestamp,            1) % 60,
            suffix  = ':';

        if(days > 0)
        {
            days =  days < 10 ? '0' + days +" d, " : days +" d, ";
        } else {
            days = '';
        }

        hours = hours < 10 ? '0' + hours + suffix : hours + suffix;
        minutes = minutes < 10 ? '0' + minutes + suffix : minutes + suffix;
        seconds = seconds < 10 ? '0' + seconds : seconds;

        if(timestamp <= 0)
        {
            element.innerText = "-";
        } else {
            element.innerText = days + hours + minutes + seconds;
        }

    }, 1000);
}

function startCounter(counter) {
    var ratePerSec = counter.dataset.rate / 36000,
        stored = parseFloat(counter.dataset.stored),
        maxStored = parseFloat(counter.dataset.storedMax);
    setInterval(function() {
        if(stored >= maxStored) {
            stored = maxStored;
            counter.dataset.stored = maxStored;
        } else {
            stored += ratePerSec;
            counter.dataset.stored = stored;
        }

        counter.innerText = Trenner(Math.floor(counter.dataset.stored));
    }, 100);
}

window.onload = function () {
    var timers = document.querySelectorAll('.js-add-countdown');
    for(var i = 0; i < timers.length; i++) {
        startTimer(timers[i].dataset.secondsToCount, timers[i]);
    }

    var counters = document.querySelectorAll('.js-ress-calc');
    for(var i = 0; i < counters.length; i++) {
        startCounter(counters[i]);
    }
};
