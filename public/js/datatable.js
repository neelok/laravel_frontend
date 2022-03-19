/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************!*\
  !*** ./resources/js/datatable.js ***!
  \***********************************/
$(function () {
  var table = $('#table_cars_deal').DataTable({
    'processing': true,
    'stateSave': false,
    'order': [0, 'desc'],
    'ajax': {
      "url": "http://54.205.62.218/data/0",
      "type": 'GET'
    },
    'columns': [{
      "data": "id"
    }, {
      "data": "year"
    }, {
      "data": "make"
    }, {
      "data": "model"
    }, {
      "data": "trim"
    }, {
      "data": "mileage"
    }, {
      "data": "drive_type"
    }, {
      "data": "time_posted"
    }, {
      "data": "price"
    }],
    columnDefs: [{
      target: -1,
      className: 'dt-body-right'
    }]
  });
  var rowsloaded = $("tr:first-child td:first-child").text();

  var getnewrows = function getnewrows() {
    var id = $("tr:first-child td:first-child").text(); // console.log(id)

    if (id > rowsloaded) {
      rowsloaded = id;
      var url = "http://54.205.62.218/data/" + id;
      $.ajax({
        url: url,
        type: 'GET',
        data: "",
        dataType: "json",
        contentType: false,
        processData: false,
        success: function success(response) {
          table.rows.add(response).draw(false);
          var rows = Object.keys(response).length;

          if (rows > 0) {
            $(".newcarsadded").text(rows + " Cars added").slideDown('slow', function () {
              setTimeout(function () {
                $(".newcarsadded").slideUp('slow');
              }, 5000);
            });
          }
        }
      });
    }
  };

  setInterval(getnewrows, 5000);
});
/******/ })()
;