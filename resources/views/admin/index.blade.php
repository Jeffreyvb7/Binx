@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('home') }}">Dashboard</a></li>
        <li>Admin page</li>
    </ul>

    <div class="fluid-container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Users</div>
                <div class="card-tools">
                    <a href="{{ route('admin.create') }}"><i class="fas fa-plus"></i></a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.search') }}" method="POST" class="ajaxSearch">
                    <div class="form-group inline">
                        <input type="search" name="query" placeholder="Type something to search">
                        <input type="submit" value="Search" class="btn btn-primary">
                    </div>
                </form>
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">First name</th>
                        <th scope="col">Last name</th>
                        <th scope="col">Age</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phonenr</th>
                        <th scope="col">Role</th>
                        <th scope="col" colspan="2">Actions</th>
                    <tbody id="results">
                    <tr>
                        <td colspan="8">Loading...</td>
                    </tr>
                    </tbody>
                </table>
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

        $('body').on('keyup', 'form.ajaxSearch input[name=query]', function () {
            var form = $(this).closest('form.ajaxSearch');

            if (form) {
                if (searchTimer !== null) clearInterval(searchTimer);

                searchTimer = setTimeout(function () {
                    ajaxSearch(form);
                }, 800);
            }
        });

        $(document).ready(function () {
            ajaxSearch($('form.ajaxSearch').first());
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