$(function() {
    var table = $('#table_cars_deal').DataTable({
        'processing': true,
        'stateSave': false,
        'order': [0, 'desc'],
        'ajax': {
            "url": "http://127.0.0.1:8000/data/0",
            "type": 'GET'
        },
        'columns': [
            { "data": "id" },
            { "data": "year" },
            { "data": "make" },
            { "data": "model" },
            { "data": "trim" },
            { "data": "mileage" },
            { "data": "drive_type" },
            { "data": "time_posted" },
            { "data": "price" }
        ],
        columnDefs: [{
            target: -1,
            className: 'dt-body-right'
        }]
    });



    var getnewrows = function() {
        var id = $("tr:first-child td:first-child").text()
        var url = "http://127.0.0.1:8000/data/" + "18"



        $.ajax({
            url: url,
            type: 'GET',
            data: "",
            dataType: "json",
            contentType: false,
            processData: false,
            success: function(response) {
                table.rows.add(response).draw(false)
                var rows = Object.keys(response).length
                if (rows > 0) {
                    $(".newcarsadded").text(rows + " Cars added").slideDown('slow', function() {
                        setTimeout(function() { $(".newcarsadded").slideUp('slow') }, 5000)
                    })
                }

            }
        })
    }

    // setInterval(getnewrows, 8000)

    table.on("select", function() {
        console.log("clicked")
    })




})