@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li>Studieroutes</li>
    </ul>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Studieroutes</div>
                <div class="card-tools">
                    @can('create studieroute')
                        <a href="{{ route('studieroute.create') }}"><i class="fas fa-plus"></i></a>
                    @endcan
                </div>
            </div>

            <div class="card-body">

                <form action="{{ route('studieroute.search') }}" method="POST" class="ajaxSearch">
                    <div class="form-group inline">
                        <input type="search" name="query" placeholder="Type something to search">
                        <input type="submit" value="Search" class="btn btn-primary">
                    </div>
                </form>

                <div id="results">
                    <span>Loading...</span>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var searchTimer = null;

        $('body').on('submit', 'form.ajaxSearch', function (e) {
            var form = $(this);

            ajaxSearch(form);
            return false;
        });

        $('body').on('keyup', 'form.ajaxSearch input[type=search]', function () {
            var form = $(this).closest('form.ajaxSearch');

            if (form) {
                if (searchTimer !== null) clearInterval(searchTimer);

                searchTimer = setTimeout(function () {
                    ajaxSearch(form);
                }, 800);
            }
        });

        $(document).ready(function () {
            ajaxSearch($('form.ajaxSearch'));
        });

        var ajaxSearch = function (form) {
            $.ajax({
                url: form.attr('action') + '{{ (getenv("USE_SLASH") == "true" ? "/" : "") }}',
                type: form.attr('method'),
                method: form.attr('method'),
                data: {"query": form.find('input[name=query]').val(), "_token": "{{ csrf_token() }}"}
            }).done(function (res) {
                $('#results').html(res);
            }).fail(function (res) {
                alert('Failed search');
            });
        }
    </script>
@endsection