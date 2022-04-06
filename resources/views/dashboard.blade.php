<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scrapped Data') }}
        </h2>
    </x-slot>

    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    
    @endpush

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    @endpush

    
    <div class="newcarsadded"></div>
    <table id="table_cars_deal" class="display">
        
        <thead>
            <tr>
                <th>Id</th>
                <th>Year</th>
                <th>Make</th>
                <th>Model</th>
                <th>Trim</th>
                <th>Mileage</th>
                <th>Drive Type</th>
                <th>Time Posted</th>
                <th>Price</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Id</th>
                <th>Year</th>
                <th>Make</th>
                <th>Model</th>
                <th>Trim</th>
                <th>Mileage</th>
                <th>Drive Type</th>
                <th>Time Posted</th>
                <th>Price</th>
            </tr>
        </tfoot>
    </table>
</x-app-layout>
