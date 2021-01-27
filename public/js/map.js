// Initialize and add the map
function initMap() {
  var latitude = document.getElementById("latitude").value;
  var longitude = document.getElementById("longitude").value;
  const home_town = { lat: parseFloat(latitude), lng: parseFloat(longitude)};

  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 6,
    center: home_town,
  });
  // The marker, positioned at home town
  const marker = new google.maps.Marker({
    position: home_town,
    map: map,
  });
}