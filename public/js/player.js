document.getElementById("first_name").focus();

if (document.getElementById("position").value == 'P') {
    toggle_stats('batting', 'hide');
    toggle_stats('pitching', 'show');
} else {
    toggle_stats('batting', 'show');
    toggle_stats('pitching', 'hide');
}

document.getElementsByClassName("batting-div")[0].addEventListener("click", function() {
    var heading = document.getElementById("batting-link").text;
    heading = heading.split(' ');
    toggle_stats('batting', heading[0]);
});

document.getElementsByClassName("pitching-div")[0].addEventListener("click", function() {
    var heading = document.getElementById("pitching-link").text;
    heading = heading.split(' ');
    toggle_stats('pitching', heading[0]);
});

document.getElementById("ids").style.display = 'none';
document.getElementById("other_ids").style.display = 'none';

document.getElementById("mlt-link").addEventListener("click", function() {
  if (this.text == 'show ids') {
    this.text = 'hide ids';
    document.getElementById("ids").style.display = 'flex';
  } else {
    this.text = 'show ids';
    document.getElementById("ids").style.display = 'none';
  }
});

function toggle_stats(type, display) {
   var rows = document.getElementsByClassName(type + "-row");

    for (var i = 0; i < rows.length; i++) {
        if (display == 'hide') {
            rows[i].style.display = 'none';
        } else {
            rows[i].style.display = 'flex';
        }

    }
    if (display == 'hide') {
        document.getElementById(type + "-link").text = 'show ' + type + ' stats';
    } else {
        document.getElementById(type + "-link").text = 'hide ' + type + ' stats';
    }
}

$(function() {
    var items;
    fetch('/minor-league-teams/ajax')
        .then(
            function(response) {
                if (response.status !== 200) {
                    console.log('Looks like there was a problem. Status Code: ' + response.status);
                    return;
                }
                response.json().then(function(data) {
                    var current_teams = $("#minor_league_teams").val().split(',');
                    $.each( current_teams, function( index, value ) {
                      $.each( data, function( key, team ) {
                        if (team != undefined && team.id == value) {
                          data.splice(key, 1);
                        }
                      });
                    });
                    items = data;
                });
            }
        )
        .catch(function(err) {
            console.log('Fetch Error: ', err);
    });

    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
    function removeFromAvailableTeams( id ) {
       $.each( items, function( key, team ) {
          if (team.id == id) {
            items.splice(key, 1);
            return false;
          }
      });
    }
    $( "#minor_league_teams_display" )
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          response( $.ui.autocomplete.filter(
            items, extractLast( request.term ) ) );
        },
        focus: function() {
          return false;
        },
        select: function( event, ui ) {

          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          // Remove from future search
          removeFromAvailableTeams(ui.item.id);

          $("#minor_league_teams").val(function() {
              if (this.value.length == 0) {
                  return ui.item.id;
              } else {
              return this.value + ',' + ui.item.id;
              }
             
          });

          return false;
        }
    });
});

document.getElementById("other-link").addEventListener("click", function() {
  if (this.text == 'show ids') {
    this.text = 'hide ids';
    document.getElementById("other_ids").style.display = 'flex';
  } else {
    this.text = 'show ids';
    document.getElementById("other_ids").style.display = 'none';
  }
});

$(function() {
    var items;
    fetch('/other-teams/ajax')
        .then(
            function(response) {
                if (response.status !== 200) {
                    console.log('Looks like there was a problem. Status Code: ' + response.status);
                    return;
                }
                response.json().then(function(data) {
                    var current_teams = $("#other_teams").val().split(',');
                    $.each( current_teams, function( index, value ) {
                      $.each( data, function( key, team ) {
                        if (team != undefined && team.id == value) {
                          data.splice(key, 1);
                        }
                      });
                    });
                    items = data;
                });
            }
        )
        .catch(function(err) {
            console.log('Fetch Error: ', err);
    });

    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
    function removeFromAvailableTeams( id ) {
       $.each( items, function( key, team ) {
          if (team.id == id) {
            items.splice(key, 1);
            return false;
          }
      });
    }

    $( "#other_teams_display" )
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          response( $.ui.autocomplete.filter(
            items, extractLast( request.term ) ) );
        },
        focus: function() {
          return false;
        },
        select: function( event, ui ) {

          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ", " );
          // Remove from future search
          removeFromAvailableTeams(ui.item.id);

            $("#other_teams").val(function() {
                if (this.value.length == 0) {
                    return ui.item.id;
                } else {
                return this.value + ',' + ui.item.id;
                }

            });

          return false;
        }
    });
});