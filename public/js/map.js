// Initialize and add the map
function initMap() {
  var latitude = document.getElementById("latitude").value;
  var longitude = document.getElementById("longitude").value;
  latitude = parseFloat(latitude);
  longitude = parseFloat(longitude);
  if (isNaN(latitude) || isNaN(longitude)) {
    // Default to Cooperstown
    latitude = 42.7006303;
    longitude = -74.92432099999999;
  }
  const home_town = { lat: latitude, lng: longitude };

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