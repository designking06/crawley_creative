The first time an anonymous user visits the site, a custom modal is displayed by module example_location,
that modal requests the user location then stores that value as a cookie, 'location'.
When the user visits the channel lineup page, this module uses the LocationBuilder service to grab that cookie value,
sends that value to the API to grab that location's channels lineup data, which is returned as json.
When that data is returned, this module builds and displays a table showcasing the channel name and
the channel number based on the channel package.
There are also filters for this table with filters, built in Form/FiltersForm.php and works with
js/example_lineup.js to provide radio buttons for the user to select different package combinations to view channels
which update the page without having to reload.