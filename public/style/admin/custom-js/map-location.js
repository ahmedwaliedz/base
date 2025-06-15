/**
 * Map Location JavaScript
 * This file handles the Google Maps integration for the location settings
 */

// Global variables
let map;
let marker;
let searchBox;
let geocoder;

/**
 * Initialize the map with default location or current location if coordinates not provided
 * @param {string} defaultLat - Default latitude
 * @param {string} defaultLng - Default longitude
 * @param {string} apiKey - Google Maps API key
 */
function initMap(defaultLat, defaultLng, apiKey) {
    // Check if valid coordinates are provided
    const hasValidCoordinates = defaultLat && defaultLng &&
                               !isNaN(parseFloat(defaultLat)) &&
                               !isNaN(parseFloat(defaultLng));

    // Store the parameters in global variables to ensure they're available to the callback
    window.mapParams = {
        defaultLat: defaultLat,
        defaultLng: defaultLng,
        hasValidCoordinates: hasValidCoordinates
    };

    // Load Google Maps API dynamically
    if (!window.google || !window.google.maps) {
        loadGoogleMapsAPI(apiKey, function() {
            // Use the stored parameters
            if (window.mapParams.hasValidCoordinates) {
                initializeMapComponents(window.mapParams.defaultLat, window.mapParams.defaultLng);
            } else {
                // Use current location if coordinates not provided
                getCurrentLocation(function(position) {
                    initializeMapComponents(
                        position.coords.latitude.toString(),
                        position.coords.longitude.toString()
                    );
                }, function(error) {
                    // Fallback to default location if geolocation fails
                    console.warn("Geolocation error:", error.message);
                    initializeMapComponents("25.276987", "55.296249"); // Default coordinates
                });
            }
        });
    } else {
        if (hasValidCoordinates) {
            initializeMapComponents(defaultLat, defaultLng);
        } else {
            // Use current location if coordinates not provided
            getCurrentLocation(function(position) {
                initializeMapComponents(
                    position.coords.latitude.toString(),
                    position.coords.longitude.toString()
                );
            }, function(error) {
                // Fallback to default location if geolocation fails
                console.warn("Geolocation error:", error.message);
                initializeMapComponents("25.276987", "55.296249"); // Default coordinates
            });
        }
    }
}

/**
 * Load Google Maps API dynamically
 * @param {string} apiKey - Google Maps API key
 * @param {function} callback - Callback function to execute after API is loaded
 */
function loadGoogleMapsAPI(apiKey, callback) {
    const script = document.createElement('script');
    script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places`;
    script.async = true;
    script.defer = true;
    script.onload = callback;
    document.head.appendChild(script);
}

/**
 * Initialize map components
 * @param {string} defaultLat - Default latitude
 * @param {string} defaultLng - Default longitude
 */
function initializeMapComponents(defaultLat, defaultLng) {
    // Convert string coordinates to numbers
    const lat = parseFloat(defaultLat);
    const lng = parseFloat(defaultLng);

    // Initialize geocoder
    geocoder = new google.maps.Geocoder();

    // Initialize map
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat, lng },
        zoom: 12,
        mapTypeControl: true,
        streetViewControl: true,
        fullscreenControl: true
    });

    // Initialize marker
    marker = new google.maps.Marker({
        position: { lat, lng },
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP
    });

    // Update hidden input fields with initial coordinates
    const latLng = new google.maps.LatLng(lat, lng);
    updateCoordinates(latLng);

    // Update coordinates when marker is dragged
    google.maps.event.addListener(marker, 'dragend', function() {
        updateCoordinates(marker.getPosition());
    });

    // Initialize search box
    const searchInput = document.getElementById('map-search');
    searchBox = new google.maps.places.SearchBox(searchInput);

    // Bias the SearchBox results towards current map's viewport
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place
    searchBox.addListener('places_changed', function() {
        const places = searchBox.getPlaces();

        if (places.length === 0) {
            return;
        }

        // For each place, get the location
        const bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            if (!place.geometry || !place.geometry.location) {
                console.log("Returned place contains no geometry");
                return;
            }

            // Update marker position
            marker.setPosition(place.geometry.location);

            // Update coordinates
            updateCoordinates(place.geometry.location);

            // Update map view
            if (place.geometry.viewport) {
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });

        map.fitBounds(bounds);
    });

    // Allow clicking on map to set marker
    google.maps.event.addListener(map, 'click', function(event) {
        marker.setPosition(event.latLng);
        updateCoordinates(event.latLng);
    });
}

/**
 * Get user's current location using browser's geolocation API
 * @param {function} successCallback - Function to call on success
 * @param {function} errorCallback - Function to call on error
 */
function getCurrentLocation(successCallback, errorCallback) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            successCallback,
            errorCallback,
            {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            }
        );
    } else {
        errorCallback({ message: "Geolocation is not supported by this browser." });
    }
}

/**
 * Update latitude and longitude input fields
 * @param {object} location - Google Maps LatLng object
 */
function updateCoordinates(location) {
    document.getElementById('lat').value = location.lat().toFixed(6);
    document.getElementById('lng').value = location.lng().toFixed(6);

    // Center the map on the marker
    map.setCenter(location);

    // Get address from coordinates and update map_desc fields
    if (geocoder) {
        // Get address in Arabic
        geocoder.geocode({
            'location': location,
            'language': 'ar'
        }, function(results, status) {
            if (status === 'OK' && results[0]) {
                const arAddress = results[0].formatted_address;

                // Update Arabic map_desc field if it exists
                const mapDescAr = document.querySelector('input[name="map_desc[ar]"]');
                if (mapDescAr) mapDescAr.value = arAddress;
            }
        });

        // Get address in English
        geocoder.geocode({
            'location': location,
            'language': 'en'
        }, function(results, status) {
            if (status === 'OK' && results[0]) {
                const enAddress = results[0].formatted_address;

                // Update English map_desc field if it exists
                const mapDescEn = document.querySelector('input[name="map_desc[en]"]');
                if (mapDescEn) mapDescEn.value = enAddress;
            }
        });
    }
}
