$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#productsTable')) {
        $('#productsTable').DataTable().destroy();
    }

    var table = $('#productsTable').DataTable({
        "paging": true,
        "pageLength": 10, 
        "lengthChange": false, 
        "searching": true, 
        "ordering": true, 
        "info": true, 
        "autoWidth": false,
    });
});

function blockInvalidInput(event) {
    if (["e", "E", "+", "-"].includes(event.key)) {
        event.preventDefault();
    }
}