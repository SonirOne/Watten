$(document).ready(function () {

    var data = [];
    data.currentteam = 0;
    data.teamids = [];
    data.teamids[1] = [];
    data.teamids[2] = [];

    $('.addUser').click(function () {
        data.currentteam = $(this).data('teamid');
        showPopupPanel('.user-outer');
        $("#userSearchText").focus();
    });

    $('.addTeam').click(function () {
        data.currentteam = $(this).data('teamid');
        showPopupPanel('.team-outer');
        $("#teamSearchText").focus();
    });

    $('#frmNewGame').ajaxForm(function (resp) {
        console.log(resp);
        if (resp.state === 'ok') {
            location.href = resp.gameurl;
        }
    });

    var userSearchResponse = function (resp) {
        console.log(resp);
        if (resp.status === 'ok') {
            $('#userResultTable tbody').find('tr').remove();
            $('#userResultTable tbody').append(resp.content);
        }
    };

    var userSearchOptions = {
        success: userSearchResponse,
        dataType: 'json'
    };
    $('#frmUserSearch').ajaxForm(userSearchOptions);

    var teamSearchResponse = function (resp) {
        console.log(resp);
        if (resp.status === 'ok') {
            $('#teamResultTable tbody').find('tr').remove();
            $('#teamResultTable tbody').append(resp.content);
        }
    };

    var teamSearchOptions = {
        success: teamSearchResponse,
        dataType: 'json'
    };
    $('#frmTeamSearch').ajaxForm(teamSearchOptions);

    $("#userResultTable").on('click', '.userentry', function () {
        console.log('User row clicked...');
        var userid = $(this).data('userid');
        var username = $(this).data('username');
        setDataType(data.currentteam, 'users');
        addTeamData(userid);
        addTeamEntry(data.currentteam, userid, username);
        hidePopupPanel('.user-outer');
    });

    $("#teamResultTable").on('click', '.teamentry', function () {
        console.log('Team row clicked...');
        var teamid = $(this).data('teamid');
        var teamname = $(this).data('teamname');
        setDataType(data.currentteam, 'team');
        addTeamData(teamid);
        addTeamEntry(data.currentteam, teamid, teamname);
        hidePopupPanel('.team-outer');
    });

    var setDataType = function (teamID, typeText) {
        $('#team' + teamID + 'datatype').val(typeText);
    };

    var addTeamData = function (dataid) {
        data.teamids[data.currentteam].push(dataid);
        $('#team' + data.currentteam + 'data').val(data.teamids[data.currentteam].toString());
    };

    var addTeamEntry = function (teamID, entryID, name) {
        var element = $('#team' + teamID);
        var content = '<li class="dataentry" data-teamid="' + teamID + '" data-entryid="' + entryID + '">' + name + '</li>';
        $(element).append(content);
    };

    $(".entryList").on('click', 'li', function () {
        console.log('Entry clicked...');
        data.currentteam = $(this).data('teamid');
        var entryid = $(this).data('entryid');

        var entryIndex = data.teamids[data.currentteam].indexOf(entryid);
        if (entryIndex > -1) {
            data.teamids[data.currentteam].splice(entryIndex, 1);
        }
        $('#team' + data.currentteam + 'data').val(data.teamids[data.currentteam].toString());
        $(this).remove();
    });

    var hidePopupPanel = function (element) {
        $('.popup-panel').hide();
        $(element).hide();
    };

    var showPopupPanel = function (element) {
        $('.user-outer').hide();
        $('.team-outer').hide();

        $('.popup-panel').show();
        $(element).show();
    };
});