$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#productsTable')) {
        $('#productsTable').DataTable().destroy();
    }

    var table = $('#productsTable').DataTable({
        "paging": true,
        "pageLength": 5, 
        "lengthChange": false, 
        "searching": true, 
        "ordering": true, 
        "info": true, 
        "autoWidth": false,
    });
});

$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#categoriesTable')) {
        $('#categoriesTable').DataTable().destroy();
    }

    var table = $('#categoriesTable').DataTable({
        "paging": true,
        "pageLength": 5, 
        "lengthChange": false, 
        "searching": true, 
        "ordering": true, 
        "info": true, 
        "autoWidth": true,
    });
});

$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#subcategoriesTable')) {
        $('#subcategoriesTable').DataTable().destroy();
    }

    var table = $('#subcategoriesTable').DataTable({
        "paging": true,
        "pageLength": 5, 
        "lengthChange": false, 
        "searching": true, 
        "ordering": true, 
        "info": true, 
        "autoWidth": true,
    });
});

function blockInvalidInput(event) {
    if (["e", "E", "+", "-"].includes(event.key)) {
        event.preventDefault();
    }
}

// Tooltip initializer for BS5
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))