$(document).ready(function () {
    console.log('Document ready...');

    var addPointsUrl = $('.addPointsUrl').data('url');
    var removePointsUrl = $('.removePointsUrl').data('url');
    var gameID = $('#gameData').data('gameid');
    console.log('GameID: ', gameID, ' AddUrl: ', addPointsUrl, ' RemoveUrl: ', removePointsUrl);

    var currentGroup = 0;
    var teamID = 0;
    $('.btnAddPoints').click(function () {
        console.log('Show popup...');
        currentGroup = $(this).data('group');
        teamID = $(this).data('teamid');
        console.log('Group: ', currentGroup);
        $('.inputMorePoints').val('');
        $('.pointsPopup-outer').show();
    });

    $('.selectPoints').click(function () {
        var points = $(this).data('points');
        addPoints(currentGroup, teamID, points);
        $('.pointsPopup-outer').hide();
    });

    $('.btnMorePoints').click(function () {
        var points = $('.inputMorePoints').val();
        addPoints(currentGroup, teamID, points);      
        $('.pointsPopup-outer').hide();
    });

    var addPoints = function (groupNr, teamID, points) {
        console.log('Group: ', groupNr, ' TeamID: ', teamID, ' Points: ', points);

        $.ajax({
            method: "POST",
            url: addPointsUrl,
            dataType: "json",
            data: {
                gameID: gameID,
                groupNr: groupNr,
                teamID: teamID,
                points: points
            }
        }).done(function (resp) {
            console.log(resp);
            if (resp.state === 'ok') {
                var groupSelector = '.group' + groupNr + 'PointWrapper';
                $(groupSelector).append(resp.html);
                calcPoints();
            }
        }).fail(function (resp) {
            console.log(resp);
        });
    };

    var calcPoints = function () {

        var pointsGroup1 = 0;
        var pointsGroup2 = 0;

        $('.points-outer').each(function (index, element) {
            var groupNr = $(element).data('group');
            var points = $(element).data('points');
            if (groupNr === 1) {
                pointsGroup1 += points;
            } else {
                pointsGroup2 += points;
            }

        });

        $('.group1Points').text(pointsGroup1);
        $('.group2Points').text(pointsGroup2);
        checkNoBet(pointsGroup1, pointsGroup2);
    };

    var maxPoints = $('#gameData').data('maxpoints');
    var noBet = maxPoints - 2;
    console.log('MaxPoints: ', maxPoints, ' NoBet: ', noBet);
    var checkNoBet = function (pointsGroup1, pointsGroup2) {
        if (pointsGroup1 >= noBet) {
            $('.group1PointWrapper').addClass('noBet');
        } else {
            $('.group1PointWrapper').removeClass('noBet');
        }


        if (pointsGroup2 >= noBet) {
            $('.group2PointWrapper').addClass('noBet');
        } else {
            $('.group2PointWrapper').removeClass('noBet');
        }
    }

    $('body').on("click", '.points-outer', function (event) {
        var pointsElement = $(this);
        bootbox.confirm({
            size: "large",
            message: "Punkte löschen",
            buttons: {
                confirm: {
                    label: 'Ja',
                    className: 'btn-danger'
                },
                cancel: {
                    label: 'Nein',
                    className: 'btn-default'
                }
            },
            callback: function (result) {
                if (result === true) {
                    pointsElement.remove();
                    calcPoints();
                }
            }
        });
    });
});

