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

function toggle_stats(type, display) {
   var rows = document.getElementsByClassName(type + "-row");

    for (var i = 0; i < rows.length; i++) {
        if (display == 'hide') {
            console.log('hiding');
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