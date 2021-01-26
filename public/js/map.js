// Initialize and add the map
function initMap() {
  const home_town = { lat: 49.2488091, lng: -122.9805104 };
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 4,
    center: home_town,
  });
  // The marker, positioned at home town
  const marker = new google.maps.Marker({
    position: home_town,
    map: map,
  });
}