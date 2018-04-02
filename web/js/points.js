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
        showPopupPanel('.pointsPopup-outer');
    });

    $('.btnWon').click(function () {
        currentGroup = $(this).data('group');
        teamID = $(this).data('teamid');
        addPoints(currentGroup, teamID, 2);
    });

    $('.selectPoints').click(function () {
        var points = $(this).data('points');
        addPoints(currentGroup, teamID, points);
        $('.popup-panel').hide();
    });

    $('.btnMorePoints').click(function () {
        var points = $('.inputMorePoints').val();
        addPoints(currentGroup, teamID, points);
        $('.popup-panel').hide();
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
                var groupSelector = '#group' + groupNr + 'PointWrapper';
                $(groupSelector).append(resp.html);
                calcPoints();

                if (resp.gameState.state === 2) {
                    $('#winnerText').text(resp.gameState.winner);
                    showPopupPanel('.winnerPopup-outer');
                }

            }
        }).fail(function (resp) {
            console.log(resp);
        });
    };

    var calcPoints = function () {
        var pointsGroup1 = calcPointsForGroup('#group1PointWrapper');
        var pointsGroup2 = calcPointsForGroup('#group2PointWrapper');
        $('.group1Points').text(pointsGroup1);
        $('.group2Points').text(pointsGroup2);

        if (pointsGroup1 === maxPoints || pointsGroup2 === maxPoints) {
            hideButtons();
        } else {
            checkNoBet(pointsGroup1, '#group1PointWrapper', '#btnAddGroup1', '#btnWonGroup1');
            checkNoBet(pointsGroup2, '#group2PointWrapper', '#btnAddGroup2', '#btnWonGroup2');
        }
    };

    var hideButtons = function () {
        $('#btnAddGroup1').hide();
        $('#btnWonGroup1').hide();
        $('#btnAddGroup2').hide();
        $('#btnWonGroup2').hide();
    };

    var calcPointsForGroup = function (groupID) {
        var points = 0;

        $(groupID).find('.points-outer').each(function (index, element) {
            var elemPoints = $(element).data('points');
            points += elemPoints;
        });

        return points;
    };

    var maxPoints = $('#gameData').data('maxpoints');
    var noBet = maxPoints - 2;

    console.log('MaxPoints: ', maxPoints, ' NoBet: ', noBet);
    var checkNoBet = function (pointsGroup, wrapperElement, btnAddElement, btnWonElement) {
        console.log('CheckNoBet: ', pointsGroup, wrapperElement, btnAddElement);
        if (pointsGroup >= noBet) {
            console.log('Disable btn...');
            $(wrapperElement).addClass('noBet');
            $(btnAddElement).addClass('nodisplay');
            $(btnWonElement).removeClass('nodisplay');
        } else {
            console.log('Enabled btn...');
            $(wrapperElement).removeClass('noBet');
            $(btnAddElement).removeClass('nodisplay');
            $(btnWonElement).addClass('nodisplay');
        }
    };

    $('body').on("click", '.points-outer', function (event) {
        var pointsElement = $(this);
        var entryID = $(pointsElement).data('id');
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
                    $.ajax({
                        method: "POST",
                        url: removePointsUrl,
                        dataType: "json",
                        data: {
                            gameID: gameID,
                            entryID: entryID
                        }
                    }).done(function (resp) {
                        console.log(resp);
                        if (resp.state === 'ok') {
                            pointsElement.remove();
                            calcPoints();
                        }
                    }).fail(function (resp) {
                        console.log(resp);
                    });
                }
            }
        });
    });

    var showPopupPanel = function (element) {
        //   first hide elements
        $('.pointsPopup-outer').hide();
        $('.winnerPopup-outer').hide();

        $('.popup-panel').show();
        $(element).show();
    };

    calcPoints();
});

