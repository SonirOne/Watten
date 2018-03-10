$(document).ready(function () {
    $(".sortableLists").sortable({
        connectWith: ".sortableLists"
    }).disableSelection();

    var newGameUrl = $('.btnNewGame').data('url');
    console.log('NewGameUrl: ', newGameUrl);
    $('.btnNewGame').click(function (event) {
        event.preventDefault();

        var maxPoints = $('#maxPoints').val();
        var team1IDs = [];
        var team2IDs = [];

        var team1 = $('#team1').find('li');
        team1.each(function (index, element) {
            var userid = $(element).data('userid');
            team1IDs.push(userid);
        });

        var team2 = $('#team2').find('li');
        team2.each(function (index, element) {
            var userid = $(element).data('userid');
            team2IDs.push(userid);
        });

        if (team1IDs.length !== 2 || team2IDs.length !== 2) {
            $('#errorPanel').show();
        } else {
            $('#errorPanel').hide();
            console.log('Ajax call...');
            $.ajax({
                method: "POST",
                url: newGameUrl,
                dataType: "json",
                data: {
                    team1: team1IDs,
                    team2: team2IDs,
                    maxPoints: maxPoints
                }
            }).done(function (resp) {
                console.log(resp);
                if (resp.state === 'ok') {
                    location.href = resp.gameurl;
                }
            }).fail(function (resp) {
                console.log(resp);
            });
        }
    });
});