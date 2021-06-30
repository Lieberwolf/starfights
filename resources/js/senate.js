senate = {
    origin: window.location.origin,
    init: () => {
        setInterval(() => {
            $.get(origin + '/overview');
        }, 10000);
        setInterval(() => {
            $('.js-drop').each(function() {
                senate.checkForBuildings($(this));
            });
            console.log('checked');
        }, 10000);
        $('.js-planet').each(function() {
            senate.getCurrentBuilding($(this));
        });
        $('.js-drop').on('drop', function() {
            //senate.checkForBuildings($(this));
        });
        $('.js-drop').on('dragover', function(ev) {
            ev.preventDefault();
        });
    },
    getCurrentBuilding: function($element) {
        $.get(origin + '/api/v1/getCurrentConstruction/' + $element.data('planetId'), (data) => {
            data = JSON.parse(data);
            if(!data.empty) {
                var timestamp = data.finished_at - data.now;
                $element.removeClass('btn-danger').addClass('btn-success disabled').attr('draggable', false);
                var days    = senate.component(timestamp, 24 * 60 * 60),
                    hours   = senate.component(timestamp,      60 * 60) % 24,
                    minutes = senate.component(timestamp,           60) % 60,
                    seconds = senate.component(timestamp,            1) % 60,
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
                    $element.find('.time').text('-');
                    senate.getCurrentBuilding($element);
                    senate.checkForBuildings($element.parent());
                } else {
                    $element.find('.time').text(days + hours + minutes + seconds);
                }
            } else {
                $element.removeClass('btn-success disabled').addClass('btn-danger').attr('draggable', true);
            }
        });
    },
    startConstruction: function($planet, bId) {
        $.get(origin + '/construction/' + $planet.data('planetId') + '/' + bId, () => {
            senate.getCurrentBuilding($planet);
        });
    },
    checkForBuildings: function($card) {
        if($card.data('buildingId')) {
            var bId = $card.data('buildingId');
            $card.find('.js-planet').each(function() {
                if($(this).hasClass('btn-danger')) {
                    senate.startConstruction($(this), bId);
                } else {
                    senate.getCurrentBuilding($(this));
                }
            });
        } else {
            $card.find('.js-planet').each(function() {
                senate.getCurrentBuilding($(this));
            });
        }
    },
    component: function(x, v) {
        return Math.floor(x / v);
    }
};

senate.init();
